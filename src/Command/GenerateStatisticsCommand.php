<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\MessageRepository;
use App\Repository\SymptomRepository;
use App\Service\OllamaClient;
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
    private $ollamaClient;
    private $entityManager;

    public function __construct(MessageRepository $messageRepository, SymptomRepository $symptomRepository, OllamaClient $ollamaClient, EntityManagerInterface $entityManager)
    {
        $this->messageRepository = $messageRepository;
        $this->symptomRepository = $symptomRepository;
        $this->ollamaClient = $ollamaClient;
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
                    'author' => $message->getAuthor()->getId() ,
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
        foreach($symptoms as $symptom) {
            if(!isset($symptomCount[$symptom->getName()])) {
                $symptomCount[$symptom->getName()] = 0;
            }
            $symptomCount[$symptom->getName()] += count($symptom->getPatient());
        }
        arsort($symptomCount);

        $numberOfSevereCases = 0;
        foreach($symptoms as $symptom) {
            if($symptom->getCode() === 'grave') {
                $numberOfSevereCases += count($symptom->getPatient());
            }
        }

        $context = json_encode([
            'task' => 'Generate global statistics on patient symptoms and messages',
            'data' => $data,
        ]);

        try {
            $response = $this->ollamaClient->getResponse($context, 'mistral');
            if($response) {
                $response['local_statistics'] =  [
                    'symptom_count' => $symptomCount,
                    'number_of_severe_cases' => $numberOfSevereCases,
                ];

                foreach($response as $statType => $statData) {
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
