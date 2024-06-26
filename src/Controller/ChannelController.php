<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Repository\ChannelRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Routing\Attribute\Route;

class ChannelController extends AbstractController
{
    #[Route('/conversations', name: 'channel')]
    public function getChannels(ChannelRepository $channelRepository): Response
    {
        $channels = $channelRepository->findAll();

        return $this->render('channel/index.html.twig', [
            'channels' => $channels ?? []
        ]);
    }

    #[Route('/conversation/{id}', name: 'chat')]
    public function chat(
        Request $request,
        Channel $channel,
        MessageRepository $messageRepository,
        Discovery $discovery
    ): Response
    {
        $messages = $messageRepository->findBy([
            'channel' => $channel
        ], ['createdAt' => 'ASC']);

        $discovery->addLink($request);

        return $this->render('channel/chat.html.twig', [
            'channel' => $channel,
            'messages' => $messages,
            'urlForMercure' => $request->getUri()
        ]);
    }
}
