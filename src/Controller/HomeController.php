<?php

namespace App\Controller;

use App\Service\TwilioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private TwilioService $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('front/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/send-sms', name: 'send_sms')]
    public function sendSms(): Response
    {
        $to = '+33604444761';
        $body = 'test challenge';

        try {
            $sid = $this->twilioService->sendSms($to, $body);
            return new Response("Message sent with SID: $sid");
        } catch (\Exception $e) {
            return new Response("Error: " . $e->getMessage());
        }
    }

}
