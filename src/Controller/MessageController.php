<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\ChannelRepository;
use App\Repository\UserRepository;
use App\Service\OllamaClient;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        $date = new DateTime();
        $message->setCreatedAt($date);
        $message->setUpdatedAt($date);

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
                               UserRepository $userRepository,
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

        try {
            $content = $this->ollamaClient->getResponse($content);
        } catch (\Exception $e) {
            return new Response("Erreur: " . $e->getMessage());
        }

        $message = new Message();
        $message->setContent($content["response"]);
        $message->setChannel($channel);
        $message->setAuthor($userRepository->find(5));
        $date = new DateTime();
        $message->setCreatedAt($date);
        $message->setUpdatedAt($date);

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
