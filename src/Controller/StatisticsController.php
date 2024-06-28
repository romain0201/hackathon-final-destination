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
            $type = $statistic->getType();
            $data = $statistic->getData();
            
            if ($data !== null) {
                if ($type === 'detailed_analysis') {
                    $statsData[$type] = $data; 
                } else {
                    $statsData[$type] = json_decode($data, true);
                }
            }
        }

        return $this->render('statistics/index.html.twig', [
            'statistics' => $statsData,
        ]);
    }
}
