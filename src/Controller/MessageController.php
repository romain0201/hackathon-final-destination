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
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
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
            sprintf('https://localhost/channel/%s',
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
