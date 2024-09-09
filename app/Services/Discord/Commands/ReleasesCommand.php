<?php

namespace App\Services\Discord\Commands;

use App\Models\PaginatedResponse;
use App\Services\Discord\Commands\Contracts\CallbackCommandInterface;
use App\Services\Discord\Utils\DiscordComponentUtils;
use App\Services\Discord\Utils\DiscordEmbedUtils;
use App\Services\Rawg\RawgGamesService;
use Illuminate\Support\Arr;

class ReleasesCommand extends BaseCommand implements CallbackCommandInterface
{
    use DiscordComponentUtils;
    use DiscordEmbedUtils;

    /**
     * @param array $payload
     * @return array
     */
    public function exec(array $payload): array
    {
        return $this->getGameReleases($payload);
    }

    /**
     * @param array $payload
     * @return array
     */
    public function callback(array $payload): array
    {
        $customId = $this->parseCustomId($payload);
        $currentPage = $this->getCurrentPage($payload);

        return match ($customId['component']) {
            self::COMPONENT_PREV_PAGE => $this->getGameReleases($payload, $currentPage - 1),
            self::COMPONENT_NEXT_PAGE => $this->getGameReleases($payload, $currentPage + 1),
        };
    }

    /**
     * @param array $payload
     * @param integer $page
     * @return array
     */
    private function getGameReleases(array $payload, int $page = 1): array
    {
        $period = Arr::get(
            $payload,
            'data.options.0.value',
            $this->user->settings->period->value
        );

        $rawgGamesService = resolve(RawgGamesService::class);
        $releases = $rawgGamesService->getUpcomingReleases(
            period: $period,
            filters: [
                'platforms' => implode(',', $this->user->settings->platforms),
                'genres'    => implode(',', $this->user->settings->genres),
                'page'      => $page,
                'page_size' => 10,
            ]
        );

        $friendlyPeriod = str_replace('-', ' ', $period);

        if ($releases->data->isEmpty()) {
            return [
                'content' => 'No upcoming releases found for the ' . $friendlyPeriod
            ];
        }

        return [
            'content'    => '**Here are the upcoming releases for the ' . $friendlyPeriod . ':**',
            'embeds'     => $this->makeGameEmbeds($releases),
            'components' => [
                $this->makeActionRow(
                    $this->makePaginationComponents($releases)
                )
            ]
        ];
    }

    /**
     * @param PaginatedResponse $response
     * @return void
     */
    private function makeGameEmbeds(PaginatedResponse $response)
    {
        $embeds = [];

        foreach ($response->data as $game) {
            $platforms = array_map(fn($platform) => $platform['name'], $game->platforms);
            $platforms = implode(', ', $platforms);

            $genres = array_map(fn($genre) => $genre['name'], $game->genres);
            $genres = implode(', ', $genres);

            $embeds[] = $this->makeEmbed(
                title: $game->name,
                description: 'Release Date: ' . $game->released->format('F j, Y'),
                url: 'https://rawg.io/games/' . $game->slug,
                fields: [
                    'Platforms' => $platforms,
                    'Genres'    => $genres
                ],
                image: $game->background_image
            );
        }

        return $embeds;
    }
}
