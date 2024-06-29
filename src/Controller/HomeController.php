<?php

namespace App\Controller;

use App\Entity\SymptomUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/admin/patient/{id}', name: 'app_admin_patient')]
    public function patientRecord(User $user): Response
    {
        $symptoms = $user->getSymptomUsers();

        return $this->render('back/home/patient.html.twig', [
            'symptoms' => $symptoms,
            'patient' => $user
        ]);
    }

    #[Route('/admin/patient/update/{id}', name: 'app_admin_patient_update')]
    public function updatePatientRecord(SymptomUser $symptomUser, EntityManagerInterface $entityManager, Request $request): Response
    {
        $route = $request->headers->get('referer');

        $symptomUser->setArchived(true);
        $entityManager->persist($symptomUser);
        $entityManager->flush();

        return $this->redirect($route);
    }
}
