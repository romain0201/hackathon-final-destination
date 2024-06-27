<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('front/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/admin/', name: 'app_admin_home')]
    public function indexAdmin(Request $request, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $patient = [];

        foreach ($users as $user)
            if (in_array('ROLE_PATIENT', $user->getRoles()))
                $patient[] = $user;

        return $this->render('back/home/index.html.twig', [
            'users' => $patient,
            'urlForMercure' => $request->getUri() . 'symptomAfterAnalyse'
        ]);
    }
}
