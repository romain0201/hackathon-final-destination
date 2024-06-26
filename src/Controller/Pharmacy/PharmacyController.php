<?php

namespace App\Controller\Pharmacy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PharmacyController extends AbstractController
{
    #[Route('/pharmacie', name: 'app_pharmacy')]
    public function index(): Response
    {
        return $this->render('back/pharmacy/index.html.twig', [
            'controller_name' => 'PharmacyController',
        ]);
    }
}
