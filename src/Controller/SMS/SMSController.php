<?php

namespace App\Controller\SMS;

use App\Service\TwilioService;
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

    #[Route('/envoyer-sms', name: 'app_sms')]
    public function index(): Response
    {
        $to = '+330604444761';
        $body = "Bonjour, ici l'équipe du hackathon ! Vous avez été opéré dernièrement, comment vous sentez-vous ? Dites nous tout ! ";

        try {
            $sid = $this->twilioService->sendSms($to, $body);
            return new Response("Message sent with SID: $sid");
        } catch (\Exception $e) {
            return new Response("Error: " . $e->getMessage());
        }
    }
}
