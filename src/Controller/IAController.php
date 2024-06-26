<?php

namespace App\Controller;

use App\Service\OllamaClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IAController extends AbstractController
{
    public function __construct()
    {
        $this->ollamaClient = new OllamaClient();
    }

    #[Route('/chat-intelligent', name: 'mistral')]
    public function askMistral()
    {
        $input = "Dans le cadre d'une application de santé,
        tu vas devoir incarné le rôle d'une IA venant prendre des nouvelles post-opératoire chez le patient.
        Tu vas te pencher sur deux axes : les symptômes et le bien être actuel de la personne/
        Tu vas lui demander comment il va, s'il a des symptômes, s'il souhaite partager des choses avec nous.
        Il peut envoyer des photos ou des textes.
        Précise lui bien qu'il s'agit d'une conversation sécurisée et s'il demande qu'il a affaire à une IA.
        Fait des messages assez courts, type SMS, c'est une conversation écrite, il ne faut pas perdre l'utilisateur avec du contenu trop long.
        Ne répond pas à mon message et introduit la conversation.";

        try {
            $response = $this->ollamaClient->getResponse($input);
            return new Response($response);
        } catch (\Exception $e) {
            return new Response("Erreur: " . $e->getMessage());
        }
    }
}
