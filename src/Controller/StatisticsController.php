<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\StatisticRepository;

class StatisticsController extends AbstractController
{

    private StatisticRepository $statisticRepository;

    public function __construct(StatisticRepository $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    #[Route('/statistiques', name: 'app_statistiques', methods: ['GET'])]
    public function index(): Response
    {   
        $statistics = $this->statisticRepository->findAll();

        $statsData = [];
        foreach ($statistics as $statistic) {
            $statsData[$statistic->getType()] = json_decode($statistic->getData(), true);
        }

        return $this->render('statistics/index.html.twig', [
            'statistics' => $statsData,
        ]);
    }
}
