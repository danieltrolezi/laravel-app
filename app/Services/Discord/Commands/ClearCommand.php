<?php

namespace App\Services\Discord\Commands;

use App\Services\Discord\Commands\Contracts\CommandInterface;
use App\Services\Discord\DiscordBaseService;

class ClearCommand implements CommandInterface
{
    public function exec(array $payload): array
    {
        $discordService = resolve(DiscordBaseService::class);
        $results = $discordService->deleteMessagesFromBot($payload['channel']['id']);

        return [
            'content' => 'Command finished!',
            'results' => $results
        ];
    }
}
