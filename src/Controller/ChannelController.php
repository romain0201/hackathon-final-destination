<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\User;
use App\Repository\ChannelRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Routing\Attribute\Route;

class ChannelController extends AbstractController
{
    #[Route('/conversations/{id}', name: 'channel')]
    public function getChannels(User $user): Response
    {
        $channels = $user->getChannels();

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

    #[Route('/unlock/{id}', name: 'app_unlock_chat')]
    public function unlockChatForMedicine(Channel $channel, ChannelRepository $channelRepository, EntityManagerInterface $entityManager)
    {
        $channel = $channelRepository->find($channel);

        $channel->setLock(false);
        $entityManager->persist($channel);
        $entityManager->flush();

        return $this->redirectToRoute('chat', ['id' => $channel->getId()]);
    }
}
