<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

class OpenAiService
{
    private $httpClient;
    private $apiUrl;
    private $apiKey;

    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->httpClient = HttpClient::create();
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function getResponse(string $input, string $model = "gpt-3.5-turbo"): ?array
    {
        $response = $this->httpClient->request('POST', $this->apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Vous êtes un analyste de données. Fournissez une analyse détaillée et des prédictions basées sur les données fournies, en français.'],
                    ['role' => 'user', 'content' => $input]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
                'top_p' => 1,
                'n' => 1,
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