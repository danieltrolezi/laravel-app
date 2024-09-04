<?php

namespace App\Services\Discord;

use App\Enums\Discord\Acknowledge;
use App\Enums\Discord\InteractionType;
use App\Exceptions\InvalidDiscordSignatureException;
use App\Services\Discord\Commands\CommandInterface;
use App\Services\Discord\Commands\SettingsCommand;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

class DiscordService
{
    private string $appId;
    private string $botToken;
    private string $publicKey;
    private string $apiUrl;

    public function __construct(
        private Client $client
    ) {
        $this->appId = config('services.discord.app_id');
        $this->botToken = config('services.discord.bot_token');
        $this->publicKey = config('services.discord.public_key');
        $this->apiUrl = config('services.discord.host') . '/api/v10/';
    }

    /**
     * @param string $signature
     * @param string $timestamp
     * @param string $body
     * @return void
     */
    public function verifyDiscordSignature(
        ?string $signature,
        ?string $timestamp,
        ?string $body
    ): void {
        if (!$signature || !$timestamp) {
            throw new InvalidDiscordSignatureException();
        }

        $message = $timestamp . $body;
        $signature = hex2bin($signature);
        $publicKey = hex2bin($this->publicKey);

        $isValid = sodium_crypto_sign_verify_detached($signature, $message, $publicKey);

        if (!$isValid) {
            throw new InvalidDiscordSignatureException();
        }
    }

    /**
     * @param string $commandName
     * @return string
     */
    public function registerCommand(string $commandName): string
    {
        $command = Arr::first(
            Arr::where(
                config('discord.commands'),
                fn($command) => $command['name'] === $commandName
            )
        );

        if (empty($command)) {
            throw new Exception('Command not found.');
        }

        $res = $this->makeRequest(
            uri: "applications/$this->appId/commands",
            payload: $command
        );

        return $res->getBody()->getContents();
    }

    /**
     * @param array $payload
     * @return array
     */
    public function handleInteractions(array $payload): array
    {
        switch ($payload['type']) {
            case InteractionType::Ping->value:
                return $this->makeResponse(
                    Acknowledge::Pong
                );

            case InteractionType::Command->value:
                return $this->makeResponse(
                    Acknowledge::ChannelMessageWithSource,
                    $this->execCommand($payload)
                );

            default:
                throw new Exception('Interation not supported: ' . $payload['type']);
        }
    }

    /**
     * @param integer $userId
     * @return string
     */
    public function sendMessage(int $userId): string
    {
        $res = $this->makeRequest(
            uri: "users/$userId/messages",
            payload: ['content' => 'This is a notification about upcoming game releases!']
        );

        return $res->getBody()->getContents();
    }

    /**
     * @param string $uri
     * @param array $payload
     * @param string $method
     * @return ResponseInterface
     */
    private function makeRequest(
        string $uri,
        array $payload,
        string $method = 'POST',
    ): ResponseInterface {
        $endpoint = $this->apiUrl . $uri;

        return $this->client->request($method, $endpoint, [
            'headers' => [
                'Authorization' => 'Bot ' . $this->botToken,
                'Content-Type'  => 'application/json'
            ],
            'body' => json_encode($payload),
            'http_errors' => false
        ]);
    }

    /**
     * @param Acknowledge $type
     * @param array $data
     * @return array
     */
    private function makeResponse(Acknowledge $type, array $data = []): array
    {
        return [
            'type' => $type->value,
            'data' => $data
        ];
    }

    /**
     * @param array $payload
     * @return array
     */
    private function execCommand(array $payload): array
    {
        return $this->makeCommand($payload['data']['name'])
                    ->exec($payload);
    }

    /**
     * @param string $commandName
     * @return CommandInterface
     */
    private function makeCommand(string $commandName): CommandInterface
    {
        return match ($commandName) {
            'settings' => resolve(SettingsCommand::class),
            default    => throw new Exception('Command not found: ' . $commandName)
        };
    }
}
