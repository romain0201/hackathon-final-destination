<?php

namespace App\Controller;

use App\Service\OllamaClient;
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
        $this->ollamaClient = new OllamaClient();

    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('front/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/admin/', name: 'app_admin_home')]
    public function indexAdmin(): Response
    {
        return $this->render('back/home/index.html.twig', [
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

    #[Route('/ask-mistral', name: 'mistral')]
    public function askMistral(): Response
    {
        $input = "presentez-vous svp je connais pas";

        try {
            $response = $this->ollamaClient->getResponse($input);
        } catch (\Exception $e) {
            return new Response("Erreur: " . $e->getMessage());
        }
        var_dump($response);
        return new Response("Réponse de l'API: " . json_encode($response));

    }
}
