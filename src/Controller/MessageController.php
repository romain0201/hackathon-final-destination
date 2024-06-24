<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\ChannelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'message', methods: ['POST'])]
    public function sendMessage(
        Request $request,
        ChannelRepository $channelRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em): JsonResponse
    {
        $data = \json_decode($request->getContent(), true); // On récupère les data postées et on les déserialize
        if (empty($content = $data['content'])) {
            throw new AccessDeniedHttpException('No data sent');
        }

        $channel = $channelRepository->findOneBy([
            'id' => $data['channel'] // On cherche à savoir de quel channel provient le message
        ]);
        if (!$channel) {
            throw new AccessDeniedHttpException('Message have to be sent on a specific channel');
        }

        $message = new Message(); // Après validation, on crée le nouveau message
        $message->setContent($content);
        $message->setChannel($channel);
        $message->setAuthor($this->getUser()); // On lui attribue comme auteur l'utilisateur courant

        $em->persist($message);
        $em->flush(); // Sauvegarde du nouvel objet en DB

        $jsonMessage = $serializer->serialize($message, 'json', [
            'groups' => ['message'] // On serialize la réponse avant de la renvoyer
        ]);

        return new JsonResponse( // Enfin, on retourne la réponse
            $jsonMessage,
            Response::HTTP_OK,
            [],
            true
        );
    }
}
