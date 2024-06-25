<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TeleconsultationController extends AbstractController
{
    #[Route('/teleconsultation', name: 'app_teleconsultation')]
    public function index(): Response
    {
        return $this->render('teleconsultation/index.html.twig', [
            'controller_name' => 'TeleconsultationController',
        ]);
    }

    #[Route('/teleconsultation/start', name: 'app_teleconsultation_start')]
    public function startTeleconsultation(Request $request, SessionInterface $session): Response
    {
        $verificationCode = rand(100000, 999999);  
        $link = $this->generateUrl('app_teleconsultation_video', ['code' => $verificationCode], true);

        $session->set('verification_code', $verificationCode);
        echo $session->get('verification_code');

        return $this->render('teleconsultation/confirm.html.twig', [
            'link' => $link,
            'code' => $verificationCode
        ]);
    }

    #[Route('/teleconsultation/video', name: 'app_teleconsultation_video')]
    public function videoTeleconsultation(Request $request, SessionInterface $session): Response
    {
        $code = $request->get('code');
        $storedCode = $session->get('verification_code');
        echo $code . ' ' . $storedCode;

        if ($code != $storedCode) {
            throw $this->createAccessDeniedException('Invalid verification code.');
        }

        return $this->render('teleconsultation/video.html.twig');
    }
}
