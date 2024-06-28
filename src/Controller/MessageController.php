<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\ChannelRepository;
use App\Repository\MessageRepository;
use App\Repository\SymptomRepository;
use App\Repository\UserRepository;
use App\Service\OllamaClient;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    public function __construct()
    {
        $this->ollamaClient = new OllamaClient();
    }

    #[Route('/message', name: 'message', methods: ['POST'])]
    public function sendMessage(
        Request $request,
        ChannelRepository $channelRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        HubInterface $hub): JsonResponse
    {
        $data = \json_decode($request->getContent(), true);
        if (empty($content = $data['content'])) {
            throw new AccessDeniedHttpException('No data sent');
        }

        $channel = $channelRepository->findOneBy([
            'id' => $data['channel']
        ]);
        if (!$channel) {
            throw new AccessDeniedHttpException('Message have to be sent on a specific channel');
        }

        $message = new Message();
        $message->setContent($content);
        $message->setChannel($channel);
        $message->setAuthor($this->getUser());

        $em->persist($message);
        $em->flush();

        $jsonMessage = $serializer->serialize($message, 'json', [
            'groups' => ['message']
        ]);

        $update = new Update(
            sprintf('https://localhost/conversation/%s',
                $channel->getId()),
            $jsonMessage,
            true
        );

        $hub->publish($update);

        return new JsonResponse(
            $jsonMessage,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/message/image', name: 'message-image', methods: ['POST'])]
    public function sendImage(
        Request $request,
        ChannelRepository $channelRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        HubInterface $hub): JsonResponse
    {
        $channelId = $request->request->get('channel');

        $channel = $channelRepository->find($channelId);
        if (!$channel) {
            throw new AccessDeniedHttpException('Message have to be sent on a specific channel');
        }

        $image = $request->files->get('file');
        if (!$image) {
            throw new BadRequestHttpException('No image uploaded');
        }

        $imageFileName = $this->saveImage($image);

        $message = new Message();
        $message->setImage($imageFileName);
        $message->setChannel($channel);
        $message->setAuthor($this->getUser());

        $em->persist($message);
        $em->flush();

        $jsonMessage = $serializer->serialize($message, 'json', [
            'groups' => ['message']
        ]);

        $update = new Update(
            sprintf('https://localhost/conversation/%s', $channel->getId()),
            $jsonMessage,
            true
        );

        $hub->publish($update);

        return new JsonResponse(
            $jsonMessage,
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @throws HttpException
     */
    private function saveImage(UploadedFile $image): string
    {
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images';
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = \transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

        try {
            $image->move($destination, $newFilename);
        } catch (FileException $e) {
            // Gérer l'exception si quelque chose se passe mal lors du déplacement du fichier
            throw new HttpException(500, 'Unable to save the image.');
        }

        return '/uploads/images/'.$newFilename;
    }

    #[Route('/message-ia/image', name: 'message-ia-image', methods: ['POST'])]
    public function askLlava(Request $request,
                               ChannelRepository $channelRepository,
                               UserRepository $userRepository,
                               SerializerInterface $serializer,
                               EntityManagerInterface $em,
                               HubInterface $hub)
    {
        $channelId = $request->request->get('channel');

        $channel = $channelRepository->find($channelId);
        if (!$channel) {
            throw new AccessDeniedHttpException('Message have to be sent on a specific channel');
        }

        $image = $request->request->get('image');
        if (!$image) {
            throw new BadRequestHttpException('No image uploaded');
        }

        if (!$channel->isLock()) return false;

        $prompt = "Analyse l'image et fait en une conclusion.";

        try {
            $content = $this->ollamaClient->getResponse($prompt, 'llava', $image);
        } catch (\Exception $e) {
            return new Response("Erreur: " . $e->getMessage());
        }

        $message = new Message();
        $message->setContent($content["response"]);
        $message->setChannel($channel);
        $message->setAuthor($userRepository->find(1));

        $em->persist($message);
        $em->flush();

        $jsonMessage = $serializer->serialize($message, 'json', [
            'groups' => ['message']
        ]);

        $update = new Update(
            sprintf('https://localhost/conversation/%s',
                $channel->getId()),
            $jsonMessage,
            true
        );

        $hub->publish($update);

        return new JsonResponse(
            $jsonMessage,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/message-ia', name: 'message-ia', methods: ['POST'])]
    public function askMistral(Request $request,
                               ChannelRepository $channelRepository,
                               MessageRepository $messageRepository,
                               UserRepository $userRepository,
                               SymptomRepository $symptomRepository,
                               SerializerInterface $serializer,
                               EntityManagerInterface $em,
                               HubInterface $hub)
    {
        $data = \json_decode($request->getContent(), true);
        if (empty($content = $data['content'])) {
            throw new AccessDeniedHttpException('No data sent');
        }

        $channel = $channelRepository->findOneBy([
            'id' => $data['channel']
        ]);
        if (!$channel) {
            throw new AccessDeniedHttpException('Message have to be sent on a specific channel');
        }

        if (!$channel->isLock()) return false;

        $previousMessages = $messageRepository->findBy(['channel' => $channel]);
        $previousMessagesFormatForIA = null;

        foreach ($previousMessages as $previousMessage) {
            if ($previousMessage->getAuthor()->getId() === 1)
                $previousMessagesFormatForIA .= "IA : " . $previousMessage->getContent();
            else $previousMessagesFormatForIA .= "USER : " . $previousMessage->getContent();
        }


        $symptoms = $symptomRepository->findAll();
        $outputString = "";
        foreach ($symptoms as $symptom) {
            $id = $symptom->getId();
            $name = $symptom->getName();

            $outputString .= "$id | $name\n";
        }

        $messageForAnalyse = "Voici la liste des précédents échanges entre toi (l'IA) et l'utilisateur (USER) :
                                " . $previousMessagesFormatForIA . "USER : " . $content . ".
                                En te basant sur ce tableau de symptômes :
                                " . $outputString  . "
                                Tu vas me dire si tu as assez d'informations pour le ranger (le USER) dans une des catégories présentes.
                                Tu vas me retourner ta réponse (oui ou non) en te basant sur les symptômes en tableau sous le format suivant : Oui (ou) Non - l'id du symptôme (juste le chiffre, pas de texte ; exemple si oui : 'Oui - 1' ou si non : 'Non' ; et tu t'arrêtes là).
                                Soit le plus précis possible, c'est à dire qu'il faut avoir une réponse en rapport avec la santé.
                                Ainsi moi derrière, avec une regex, je vais pouvoir enregistrer le contexte du patient en BDD en fonction de ses symptômes.";

        $responseAfterAnalyse = $this->ollamaClient->getResponse($messageForAnalyse);

        if (preg_match("/\boui\b/i", $responseAfterAnalyse['response']))
            if (preg_match_all("/\b-?\d+(\.\d+)?\b/", $responseAfterAnalyse['response'], $matches))
                if ($symptomRepository->find($matches[0][0])) {
                    $user = $userRepository->find($this->getUser());
                    $user->addSymptom($symptomRepository->find($matches[0][0]));

                    $em->persist($user);
                    $em->flush();

                    if ($symptomRepository->find($matches[0][0])->isActive()) {
                        $jsonSymptom = $serializer->serialize($user, 'json', [
                            'groups' => ['symptom']
                        ]);

                        $updateSymptomAfterAnalyseForAdmin = new Update(
                            'https://localhost/admin/symptomAfterAnalyse',
                            $jsonSymptom,
                            true
                        );

                        $hub->publish($updateSymptomAfterAnalyseForAdmin);
                    }
                }

        $messageForIa = "Voici la liste des précédents échanges entre toi (l'IA) et l'utilisateur (USER). Tu vas t'en servir au fur et à mesure de la conversation pour répondre à l'utilisateur. Voici la liste de message précédents = "
            . $previousMessagesFormatForIA . ". Depuis cette échange, répond à la question suivante : " . $content;

        try {
            $content = $this->ollamaClient->getResponse($messageForIa);
        } catch (\Exception $e) {
            return new Response("Erreur: " . $e->getMessage());
        }

        $message = new Message();
        $message->setContent($content["response"]);
        $message->setChannel($channel);
        $message->setAuthor($userRepository->find(1));

        $em->persist($message);
        $em->flush();

        $jsonMessage = $serializer->serialize($message, 'json', [
            'groups' => ['message']
        ]);

        $update = new Update(
            sprintf('https://localhost/conversation/%s',
                $channel->getId()),
            $jsonMessage,
            true
        );

        $hub->publish($update);

        return new JsonResponse(
            $jsonMessage,
            Response::HTTP_OK,
            [],
            true
        );
    }
}
