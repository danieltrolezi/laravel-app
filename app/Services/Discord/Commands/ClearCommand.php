<?php

namespace App\Services\Discord\Commands;

use App\Services\Discord\Commands\Contracts\CommandInterface;
use App\Services\Discord\DiscordAppService;

class ClearCommand extends BaseCommand implements CommandInterface
{
    /**
     * @param array $payload
     * @return array
     */
    public function exec(array $payload): array
    {
        $discordService = resolve(DiscordAppService::class);
        $results = $discordService->deleteBotMessages($payload['channel']['id']);

        return [
            'content' => 'Command finished!',
            'results' => $results
        ];
    }
}
