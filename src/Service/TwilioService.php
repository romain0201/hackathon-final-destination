<?php

namespace App\Service;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioService
{
    private Client $client;
    private string $from;

    /**
     * @throws ConfigurationException
     */
    public function __construct(string $sid, string $authToken, string $from)
    {
        $this->client = new Client($sid, $authToken);
        $this->from = $from;
    }

    /**
     * @throws TwilioException
     */
    public function sendSms(string $to, string $body): ?string
    {
        $message = $this->client->messages->create($to, [
            'from' => $this->from,
            'body' => $body,
        ]);

        return $message->sid;
    }
}
