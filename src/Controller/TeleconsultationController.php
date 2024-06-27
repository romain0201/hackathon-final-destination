<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/teleconsultation/initialisation/{id}', name: 'app_init_teleconsultation')]
    public function startTeleconsultation(User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $verificationCode = rand(100000, 999999);
        $link = $this->generateUrl('app_teleconsultation_verification', [], true);

        try {
            $this->twilioService->sendSms(
                $userRepository->find($user)->getPhone(),
                "Votre code de vérification est $verificationCode. Cliquez sur ce lien pour démarrer la téléconsultation: $link"
            );
        } catch (\Exception $e) {
            return new Response("Erreur: " . $e->getMessage());
        }

        $user->setOdp($verificationCode);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_teleconsultation_video');
    }

    #[Route('/teleconsultation/verification/code', name: 'app_teleconsultation_verification_code', methods: ["POST"])]
    public function verifyCode(Request $request, UserRepository $userRepository): Response
    {
        $code = $request->get('code');
        $storedCode = $userRepository->find($this->getUser())->getOdp();

        if ($code !== $storedCode) {
            $this->addFlash('error', 'Code de vérification invalide.');
            return $this->redirectToRoute('app_teleconsultation_verification');
        }

        return $this->redirectToRoute('app_teleconsultation_video');
    }

    #[Route('/teleconsultation/verification', name: 'app_teleconsultation_verification')]
    public function verify(): Response
    {
        return $this->render('teleconsultation/verify.html.twig');
    }

    #[Route('/teleconsultation/video', name: 'app_teleconsultation_video')]
    public function videoTeleconsultation(): Response
    {
        return $this->render('teleconsultation/video.html.twig');
    }
}
