<?php

namespace App\Controller\SMS;

use App\Entity\Channel;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TwilioService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SMSController extends AbstractController
{
    private TwilioService $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    #[Route('/envoyer-sms/{id}', name: 'app_sms')]
    public function index(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $channel = new Channel();

        $medecine = $userRepository->find($this->getUser());
        $patient = $userRepository->find($user);

        $channel->setName("Échange entre {$patient->getName()} et {$medecine->getName()}");
        $channel->setMedicine($medecine);
        $channel->setPatient($patient);

        $entityManager->persist($channel);
        $entityManager->flush();

        $channelId = $channel->getId();

        $to = '+330604444761';
        $body = "Bonjour, ici l'équipe du Calmedica !
        Vous avez été opéré dernièrement, comment vous sentez-vous ? Dites nous tout !
        RDV sur : https://localhost/conversations/{$channelId}
        ";

        try {
            $sid = $this->twilioService->sendSms($to, $body);
            return $this->redirectToRoute('chat', ['id' => $channelId]);
        } catch (\Exception $e) {
            return new Response("Error: " . $e->getMessage());
        }
    }
}
