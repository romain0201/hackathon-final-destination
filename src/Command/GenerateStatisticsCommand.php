<?php

namespace App\Command;

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
    private $openaiService;
    private $entityManager;

    public function __construct(MessageRepository $messageRepository, SymptomRepository $symptomRepository, OpenAiService $openaiService, EntityManagerInterface $entityManager)
    {
        $this->messageRepository = $messageRepository;
        $this->symptomRepository = $symptomRepository;
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

        $data = [
            'messages' => array_map(function ($message) {
                return [
                    'id' => $message->getId(),
                    'content' => $message->getContent(),
                    'author' => $message->getAuthor()->getId(),
                ];
            }, $messages),
            'symptoms' => array_map(function ($symptom) {
                return [
                    'id' => $symptom->getId(),
                    'name' => $symptom->getName(),
                    'patients' => array_map(function ($patient) {
                        return $patient->getId();
                    }, $symptom->getPatient()->toArray()),
                ];
            }, $symptoms),
        ];

        // Local statistics
        $symptomCount = [];
        foreach ($symptoms as $symptom) {
            if (!isset($symptomCount[$symptom->getName()])) {
                $symptomCount[$symptom->getName()] = 0;
            }
            $symptomCount[$symptom->getName()] += count($symptom->getPatient());
        }
        arsort($symptomCount);

        $numberOfSevereCases = 0;
        foreach ($symptoms as $symptom) {
            if ($symptom->getCode() === 'grave') {
                $numberOfSevereCases += count($symptom->getPatient());
            }
        }

        $totalMessages = count($messages);
        $totalUsers = count(array_unique(array_map(function ($message) {
            return $message->getAuthor()->getId();
        }, $messages)));
        $averageMessagesPerUser = $totalUsers > 0 ? $totalMessages / $totalUsers : 0;

        $context = [
            'task' => 'Generate global statistics on patient symptoms and messages',
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
                $response['local_statistics'] = $context['local_statistics']; // Ensure local statistics are added to the response
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