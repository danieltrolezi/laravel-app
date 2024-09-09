<?php

namespace App\Services\Discord;

use App\Enums\Discord\Acknowledge;
use App\Enums\Discord\InteractionType;
use App\Services\Discord\Commands\Contracts\CallbackCommandInterface;
use App\Services\Discord\Commands\Contracts\CommandInterface;
use App\Services\Discord\Utils\DiscordCallbackUtils;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class DiscordInteractionsService extends DiscordBaseService
{
    use DiscordCallbackUtils;

    /**
     * @param array $payload
     * @return array
     */
    public function handleInteractions(array $payload): array
    {
        switch ($payload['type']) {
            case InteractionType::Ping:
                return $this->makeResponse(
                    Acknowledge::Pong
                );

            case InteractionType::Command:
                return $this->makeResponse(
                    Acknowledge::ChannelMessageWithSource,
                    $this->execCommand($payload)
                );

            case InteractionType::MessageComponent:
                return $this->makeResponse(
                    Acknowledge::UpdateMessage,
                    $this->callbackCommand($payload)
                );

            default:
                throw new InvalidArgumentException('Interaction not supported: ' . $payload['type']);
        }
    }

    /**
     * @param string $command
     * @param array $payload
     * @return array
     */
    private function execCommand(array $payload): array
    {
        $commandName = Arr::get($payload, 'data.name');

        return $this->makeCommand($commandName)
                    ->exec($payload);
    }

    /**
     * @param array $payload
     * @return array
     */
    private function callbackCommand(array $payload): array
    {
        $customId = $this->parseCustomId($payload);

        return $this->makeCommand($customId['command'])
                    ->callback($payload);
    }

    /**
     * @param string $commandName
     * @return CommandInterface|CallbackCommandInterface
     */
    private function makeCommand(string $commandName): CommandInterface|CallbackCommandInterface
    {
        return resolve(
            sprintf(
                'App\Services\Discord\Commands\%sCommand',
                Str::studly($commandName)
            )
        );
    }
}
