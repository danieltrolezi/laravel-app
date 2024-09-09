<?php

namespace App\Services\Discord\Commands;

use App\Services\Discord\Commands\Contracts\CommandInterface;
use App\Services\Discord\Utils\DiscordEmbedUtils;

class HelpCommand extends BaseCommand implements CommandInterface
{
    use DiscordEmbedUtils;

    /**
     * @param array $payload
     * @return array
     */
    public function exec(array $payload): array
    {
        $repository = 'https://github.com/danieltrolezi/gamewatch';

        $description = 'GameWatch makes it easy to track upcoming game releases.' . "\n\n" .

                       'Customize your notifications by selecting your preferred 
                       platforms and genres, set your frequency, and never miss 
                       a game launch again!' . "\n\n" .

                       "Check the [repository on Github]($repository) for full documentation.";

        return [
            'content' => '',
            'embeds' => [
                $this->makeEmbed(
                    title: config('app.name'),
                    description: $description,
                    url: $repository,
                    fields: [
                        'Version'      => config('app.version'),
                        'Release Date' => '2024-10-01'
                    ]
                )
            ]
        ];
    }
}
