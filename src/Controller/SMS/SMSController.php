<?php

namespace App\Controller\SMS;

use App\Entity\Channel;
use App\Entity\User;
use App\Entity\Message;
use App\Repository\UserRepository;
use App\Service\OllamaClient;
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
        $this->ollamaClient = new OllamaClient();
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

        $input = "Dans le cadre d'une application de santé,
        tu vas devoir incarné le rôle d'une IA venant prendre des nouvelles post-opératoire chez le patient.
        Tu vas te pencher sur deux axes : les symptômes et le bien être actuel de la personne.
        Tu vas lui demander comment il va, s'il a des symptômes, s'il souhaite partager des choses avec nous.
        Il peut envoyer des photos ou des textes.
        Précise lui bien qu'il s'agit d'une conversation sécurisée et s'il demande qu'il a affaire à une IA.
        Fait des messages assez courts, type SMS, c'est une conversation écrite, il ne faut pas perdre l'utilisateur avec du contenu trop long.
        Ne répond pas à mon message et introduit la conversation pour attendre une réponse de la part de l'utilisateur.";

        try {
            $iaResponse = $this->ollamaClient->getResponse($input);
        } catch (\Exception $e) {
            return new Response("Erreur: " . $e->getMessage());
        }

        $message = new Message();
        $message->setAuthor($userRepository->find(1));
        $message->setContent($iaResponse["response"]);
        $message->setChannel($channel);

        $entityManager->persist($message);
        $entityManager->flush();

        try {
            $this->twilioService->sendSms($to, $body);
            return $this->redirectToRoute('chat', ['id' => $channelId]);
        } catch (\Exception $e) {
            return new Response("Error: " . $e->getMessage());
        }
    }
}
