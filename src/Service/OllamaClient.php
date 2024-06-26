<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class OllamaClient
{
    private $httpClient;
    private $apiUrl;

    public function __construct(string $apiUrl = "http://host.docker.internal:11434/api/generate")
    {
        $this->httpClient = HttpClient::create();
        $this->apiUrl = $apiUrl;
    }

    public function getResponse(string $input, string $model = "mistral"): ?array
    {
        $response = $this->httpClient->request('POST', $this->apiUrl, [
            'json' => [
                'model' => $model,
                'prompt' => $input,
                'stream' => false
            ],
        ]);

        try {
            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                throw new \Exception("API request failed with status code: $statusCode");
            }
            return $response->toArray();
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface|RedirectionExceptionInterface $e) {
            throw new \Exception("API request failed: " . $e->getMessage());
        }
    }
}
