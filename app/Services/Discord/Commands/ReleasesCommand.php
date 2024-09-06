<?php

namespace App\Services\Discord\Commands;

use App\Models\Game;
use App\Models\PaginatedResponse;
use App\Services\Discord\Commands\Contracts\CommandInterface;
use App\Services\Rawg\RawgGamesService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ReleasesCommand implements CommandInterface
{
    /**
     * @param array $payload
     * @return array
     */
    public function exec(array $payload): array
    {
        $user = Auth::user();
        $rawgGamesService = resolve(RawgGamesService::class);

        $period = Arr::get(
            $payload,
            'data.options.0.value',
            $user->settings->period->value
        );

        $response = $rawgGamesService->getUpcomingReleases(
            period: $period,
            filters: [
                'platforms' => implode(',', $user->settings->platforms),
                'genres'    => implode(', ', $user->settings->genres)
            ]
        );

        return [
            'content' => '**Here are the upcoming releases for the ' . str_replace('-', ' ', $period) . ':**',
            'embeds' => $this->makeEmbeds($response)
        ];
    }

    /**
     * @param PaginatedResponse $response
     * @return void
     */
    private function makeEmbeds(PaginatedResponse $response)
    {
        $games = $response->getContents()['data'];
        $embeds = [];

        foreach ($games as $game) {
            $embeds[] = $this->makeGameEmbed($game);
        }

        return $embeds;
    }

    /**
     * @param Game $game
     * @return array
     */
    private function makeGameEmbed(Game $game): array
    {
        $platforms = array_map(fn($platform) => $platform['name'], $game->platforms);
        $platforms = implode(', ', $platforms);

        $genres = array_map(fn($platform) => $platform['name'], $game->genres);
        $genres = implode(', ', $genres);

        return [
            'title'       => $game->name,
            'description' => 'Release Date: ' . $game->released->format('F j, Y'),
            'url'         => 'https://rawg.io/games/' . $game->slug,
            'color'       => hexdec('FF5733'),
            'fields'      => [
                [
                    'name'   => 'Platforms',
                    'value'  => $platforms,
                    'inline' => true
                ],
                [
                    'name'   => 'Genres',
                    'value'  => $genres,
                    'inline' => true
                ]
            ],
            'image' => [
                'url'    => $game->background_image
            ]
        ];
    }
}
