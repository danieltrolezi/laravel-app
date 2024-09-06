<?php

namespace App\Services\Discord;

use App\Enums\Discord\Acknowledge;
use App\Enums\Discord\InteractionType;
use App\Services\Discord\Commands\ReleasesCommand;
use App\Services\Discord\Commands\ClearCommand;
use App\Services\Discord\Commands\Contracts\CallbackCommandInterface;
use App\Services\Discord\Commands\Contracts\CommandInterface;
use App\Services\Discord\Commands\SettingsCommand;
use Exception;
use Illuminate\Support\Arr;
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
        $command = Arr::get($payload, 'data.name');

        return $this->makeCommand($command)
                    ->exec($payload);
    }

    /**
     * @param string $command
     * @return CommandInterface
     */
    private function makeCommand(string $command): CommandInterface
    {
        return match ($command) {
            'settings' => resolve(SettingsCommand::class),
            'releases' => resolve(ReleasesCommand::class),
            'clear'      => resolve(ClearCommand::class),
            //'help'     => resolve(HelpCommand::class),
            default    => throw new Exception('Command not found: ' . $command)
        };
    }

    private function callbackCommand(array $payload): array
    {
        $customId = $this->parseCustomId(
            Arr::get($payload, 'data.custom_id')
        );

        return $this->makeCallbackCommand($customId['command'])
                    ->callback($payload);
    }

    /**
     * @param string $command
     * @return CallbackCommandInterface
     */
    private function makeCallbackCommand(string $command): CallbackCommandInterface
    {
        return match ($command) {
            'settings' => resolve(SettingsCommand::class),
            default    => throw new Exception('Callback Command not found: ' . $command)
        };
    }
}
