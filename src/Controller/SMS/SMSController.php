<?php

namespace App\Controller\SMS;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SMSController extends AbstractController
{
    #[Route('/envoyer-sms', name: 'app_sms')]
    public function index(): Response
    {
        return $this->render('sms/index.html.twig', [
            'controller_name' => 'SMSController',
        ]);
    }
}
