<?php

namespace App\Services\Discord;

use App\Exceptions\InvalidDiscordSignatureException;
use App\Models\User;
use Exception;
use Illuminate\Support\Arr;

class DiscordAppService extends DiscordBaseService
{
    use DiscordCallbackUtils;

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
}
