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
                'messages' => [['role' => 'user', 'content' => $input]],
                'max_tokens' => 100,
                'temperature' => 0.5,
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