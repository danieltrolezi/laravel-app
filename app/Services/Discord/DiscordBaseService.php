<?php

namespace App\Services\Discord;

use App\Enums\Discord\Acknowledge;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class DiscordBaseService
{
    protected string $appId;
    protected string $botToken;
    protected string $publicKey;
    protected string $apiUrl;

    public function __construct(
        protected Client $client,
        protected UserRepository $userRepository
    ) {
        $this->appId = config('services.discord.app_id');
        $this->botToken = config('services.discord.bot_token');
        $this->publicKey = config('services.discord.public_key');
        $this->apiUrl = config('services.discord.host') . '/api/v10/';
    }

    /**
     * @param string $uri
     * @param array $payload
     * @param string $method
     * @return ResponseInterface
     */
    protected function makeRequest(
        string $uri,
        array $payload = [],
        array $query = [],
        string $method = 'POST',
    ): ResponseInterface {
        $endpoint = $this->apiUrl . $uri;

        $options = [
            'http_errors' => false,
            'headers'     => [
                'Authorization' => 'Bot ' . $this->botToken,
                'Content-Type'  => 'application/json'
            ]
        ];

        if (!empty($payload)) {
            $options['json'] = $payload;
        }

        if (!empty($query)) {
            $options['query'] = $query;
        }

        return $this->client->request($method, $endpoint, $options);
    }

    /**
     * @param Acknowledge $ack
     * @param array $data
     * @return array
     */
    protected function makeResponse(Acknowledge $ack, array $data = []): array
    {
        return [
            'type' => $ack->value,
            'data' => $data
        ];
    }
}
