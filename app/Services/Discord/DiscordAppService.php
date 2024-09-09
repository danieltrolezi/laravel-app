<?php

namespace App\Services\Discord;

use App\Exceptions\InvalidDiscordSignatureException;
use App\Models\User;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class DiscordAppService extends DiscordBaseService
{
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
     * @param array $payload
     * @return User
     */
    public function findOrCreateUser(array $payload): User
    {
        $user = $this->userRepository->findByDiscordId(
            $payload['user']['id']
        );

        if (!$user) {
            $user = $this->userRepository->createFromDiscord([
                'name'            => $payload['user']['global_name'],
                'username'        => $payload['user']['username'],
                'discord_user_id' => $payload['user']['id']
            ]);
        }

        return $user->load('settings');
    }

    /**
     * @param string $commandName
     * @return string
     */
    public function registerCommand(string $commandName): string
    {
        $command = $this->getCommandConfig($commandName);

        $res = $this->makeRequest(
            uri: "applications/$this->appId/commands",
            payload: $command
        );

        return $res->getBody()->getContents();
    }

    /**
     * @param string $commandName
     * @return array
     */
    private function getCommandConfig(string $commandName): array
    {
        $command = Arr::first(
            Arr::where(
                config('discord.commands'),
                fn($command) => $command['name'] === $commandName
            )
        );

        if (empty($command)) {
            throw new InvalidArgumentException(
                sprintf('Command %s not found.', $commandName)
            );
        }

        return $command;
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
     * @param string $channelId
     * @param integer $limit
     * @return array
     */
    public function getBotMessages(string $channelId, int $limit = 50): array
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
    public function deleteBotMessages(string $channelId): array
    {
        $results = [];
        $messages = $this->getBotMessages($channelId);

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
}
