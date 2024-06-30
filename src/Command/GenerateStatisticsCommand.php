<?php

namespace App\Command;

use App\Repository\SymptomUserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\MessageRepository;
use App\Repository\SymptomRepository;
use App\Service\OpenAiService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Statistic;

#[AsCommand(
    name: 'GenerateStatistics',
    description: 'This command generates statistics for the application',
)]
class GenerateStatisticsCommand extends Command
{
    private $messageRepository;
    private $symptomRepository;
    private $symptomsUserRepository;
    private $openaiService;
    private $entityManager;

    public function __construct(MessageRepository $messageRepository, SymptomRepository $symptomRepository, SymptomUserRepository $symptomUserRepository, OpenAiService $openaiService, EntityManagerInterface $entityManager)
    {
        $this->messageRepository = $messageRepository;
        $this->symptomRepository = $symptomRepository;
        $this->symptomsUserRepository = $symptomUserRepository;
        $this->openaiService = $openaiService;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('This command generates statistics for the application');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Global statistics
        $messages = $this->messageRepository->findAll();
        $symptoms = $this->symptomRepository->findAll();
        $symptomsUser = $this->symptomsUserRepository->findAll();

        $data = [
            'messages' => array_map(function ($message) {
                return [
                    'id' => $message->getId(),
                    'content' => $message->getContent(),
                    'author' => $message->getAuthor()->getId(),
                ];
            }, $messages),
            'symptoms' => array_map(function ($symptomsUser) {
                return [
                    'id' => $symptomsUser->getId(),
                    'name' => $symptomsUser->getSymptom()->getName(),
                    'patients' => $symptomsUser->getPatient(),
                ];
            }, $symptomsUser),
        ];

        // Local statistics
        $symptomCount = [];
        foreach ($symptomsUser as $symptom) {
            if (!isset($symptomCount[$symptom->getSymptom()->getName()])) {
                $symptomCount[$symptom->getSymptom()->getName()] = 0;
            }
            $symptomCount[$symptom->getSymptom()->getName()] += count($symptom->getPatient()->getSymptomUsers());
        }
        arsort($symptomCount);

        $numberOfSevereCases = 0;
        foreach ($symptomsUser as $symptom) {
            if ($symptom->getSymptom()->isActive() === true) {
                $numberOfSevereCases += count($symptom->getPatient()->getSymptomUsers());
            }
        }

        $totalMessages = count($messages);
        $totalUsers = count(array_unique(array_map(function ($message) {
            return $message->getAuthor()->getId();
        }, $messages)));
        $averageMessagesPerUser = $totalUsers > 0 ? $totalMessages / $totalUsers : 0;

        $context = [
            'task' => 'Générer une analyse détaillée des données, y compris des conclusions statistiques et des prédictions.',
            'data' => $data,
            'local_statistics' => [
                'symptom_count' => $symptomCount,
                'number_of_severe_cases' => $numberOfSevereCases,
                'total_messages' => $totalMessages,
                'average_messages_per_user' => $averageMessagesPerUser,
            ]
        ];

        try {
            $response = $this->openaiService->getResponse(json_encode($context), 'gpt-3.5-turbo');
            if ($response) {
                $analysis = (new Statistic())
                ->setType('detailed_analysis')
                ->setData($response['choices'][0]['message']['content']);
                $this->entityManager->persist($analysis);

                $response['local_statistics'] = $context['local_statistics'];
                foreach ($response as $statType => $statData) {
                    $statistic = (new Statistic())
                        ->setType($statType)
                        ->setData(json_encode($statData));
                    $this->entityManager->persist($statistic);
                }
                $this->entityManager->flush();
                $io->success('Statistics generated successfully');
            } else {
                $io->error('Failed to generate statistics');
            }
        } catch (\Exception $e) {
            $io->error('API request failed: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
