<?php

namespace App\Services\Discord;

use App\Enums\Discord\Acknowledge;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class DiscordBaseService
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
     * @param string $userId
     * @return string
     */
    public function sendMessage(string $userId): string
    {
        $res = $this->makeRequest(
            uri: "users/$userId/messages",
            payload: ['content' => 'This is a notification about upcoming game releases!']
        );

        return $res->getBody()->getContents();
    }

    /**
     * Undocumented function
     *
     * @param string $channelId
     * @param integer $limit
     * @return array
     */
    public function getMessagesFromBot(string $channelId, int $limit = 50): array
    {
        $res = $this->makeRequest(
            method: 'GET',
            uri: "channels/$channelId/messages",
            query: ['limit' => $limit]
        );

        $messages = json_decode($res->getBody()->getContents(), true);

        return array_filter($messages, function ($message) {
            return $message['author']['id'] === $this->appId;
        });
    }

    /**
     * @param string $channelId
     * @return void
     */
    public function deleteMessagesFromBot(string $channelId): array
    {
        $results = [];
        $messages = $this->getMessagesFromBot($channelId);

        foreach ($messages as $message) {
            $res = $this->makeRequest(
                method: 'DELETE',
                uri: "/channels/{$channelId}/messages/" . $message['id'],
            );

            $results[] = [
                'message_id'  => $message['id'],
                'status_code' => $res->getStatusCode(),
                'contents'    => $res->getBody()->getContents()
            ];
        }

        return $results;
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
