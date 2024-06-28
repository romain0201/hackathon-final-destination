<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\TwilioService;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

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

        return $this->redirectToRoute('app_teleconsultation_video', ["medicine" => $userRepository->find($this->getUser())->getId(), "patient" => $user->getId()]);
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

    #[Route('/teleconsultation/video/{medicine}/{patient}', name: 'app_teleconsultation_video')]
    public function videoTeleconsultation(User $medicine, User $patient): Response
    {
        return $this->render('teleconsultation/video.html.twig', [
            'medicine' => $medicine->getName(),
            'patient' => $patient->getName()
        ]);
    }

    #[Route('/access_token', name: 'access_token', methods: ['POST'])]
    public function generate_token(Request $request)
    {
        $accountSid = $this->getParameter('app.env.TWILIO_ACCOUNT_SID');
        $apiKeySid = $this->getParameter('app.env.TWILIO_API_KEY_SID');
        $apiKeySecret = $this->getParameter('app.env.TWILIO_API_KEY_SECRET');
        $identity = uniqid();

        $roomName = json_decode($request->getContent());

        $token = new AccessToken(
            $accountSid,
            $apiKeySid,
            $apiKeySecret,
            3600,
            $identity
        );

        $grant = new VideoGrant();
        $grant->setRoom($roomName->roomName);
        $token->addGrant($grant);
        return $this->json(['token' => $token->toJWT()], 200);
    }
}
