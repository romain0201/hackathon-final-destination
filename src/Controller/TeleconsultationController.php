<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\TwilioService;

class TeleconsultationController extends AbstractController
{
    private TwilioService $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    #[Route('/teleconsultation', name: 'app_teleconsultation')]
    public function index(): Response
    {
        return $this->render('teleconsultation/index.html.twig');
    }

    #[Route('/teleconsultation/demarrer', name: 'app_teleconsultation_demarrer', methods: ['POST'])]
    public function startTeleconsultation(Request $request, SessionInterface $session): Response
    {
        $phoneNumber = $request->get('phone');
        $verificationCode = rand(100000, 999999);
        $link = $this->generateUrl('app_teleconsultation_verification', [], true);

        $this->twilioService->sendSms(
            '+33' . $phoneNumber,
            "Votre code de vérification est $verificationCode. Cliquez sur ce lien pour démarrer la téléconsultation: $link"
        );

        $session->set('verification_code', $verificationCode);
        $session->set('phone_number', $phoneNumber);

        return $this->redirectToRoute('app_teleconsultation_video_praticien');
    }

    #[Route('/teleconsultation/verification', name: 'app_teleconsultation_verification')]
    public function verify(Request $request, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $code = $request->get('code');
            $storedCode = $session->get('verification_code');

            if ($code !== $storedCode) {
                $this->addFlash('error', 'Code de vérification invalide.');
                return $this->redirectToRoute('app_teleconsultation_verification');
            }

            return $this->redirectToRoute('app_teleconsultation_video');
        }

        return $this->render('teleconsultation/verify.html.twig');
    }

    #[Route('/teleconsultation/video', name: 'app_teleconsultation_video')]
    public function videoTeleconsultation(SessionInterface $session): Response
    {
        return $this->render('teleconsultation/video.html.twig');
    }

    #[Route('/teleconsultation/video/praticien', name: 'app_teleconsultation_video_praticien')]
    public function videoTeleconsultationPractitioner(): Response
    {
        return $this->render('teleconsultation/video.html.twig');
    }
}
