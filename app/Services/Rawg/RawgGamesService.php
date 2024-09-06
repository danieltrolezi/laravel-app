<?php

namespace App\Services\Rawg;

use App\Enums\Period;
use App\Enums\Rawg\RawgField;
use App\Models\Game;
use App\Models\PaginatedResponse;
use Illuminate\Support\Collection;

class RawgGamesService extends RawgBaseService
{
    /**
     * @param string $genre
     * @param integer|null $perPage
     * @param integer|null $page
     * @return PaginatedResponse
     */
    public function getRecommendations(
        string $genre,
        array $filters = []
    ): PaginatedResponse {
        $query = $this->filterService->getQueryFilters(filters: $filters, default: [
            RawgField::Dates->value    => date('Y-m-d', strtotime('-1 year')) . ',' . date('Y-m-d'),
            RawgField::Genres->value   => $genre,
            RawgField::Ordering->value => 'updated',
            RawgField::PageSize->value => 5,
            RawgField::Page->value     => 1
        ]);

        $response = $this->call(uri: 'games', data: [
            'query' => $query
        ]);

        return new PaginatedResponse(
            $this->parseGames($response),
            $query[RawgField::PageSize->value],
            $query[RawgField::Page->value],
            $response['count']
        );
    }

    /**
     * @param string $period
     * @param integer $perPage
     * @param integer $page
     * @return PaginatedResponse
     */
    public function getUpcomingReleases(
        string $period = Period::Next_7_Days->value,
        array $filters = []
    ): PaginatedResponse {
        $timeUnit = Period::getTimeUnit($period);
        $query = $this->filterService->getQueryFilters(filters: $filters, default: [
            RawgField::Dates->value    => date('Y-m-d') . ',' . date('Y-m-d', strtotime('+1 ' . $timeUnit)),
            RawgField::Ordering->value => 'released',
            RawgField::PageSize->value => 25,
            RawgField::Page->value     => 1
        ]);

        $response = $this->call(uri: 'games', data: [
            'query' => $query
        ]);

        return new PaginatedResponse(
            $this->parseGames($response),
            $query[RawgField::PageSize->value],
            $query[RawgField::Page->value],
            $response['count']
        );
    }

    /**
     * @param array $data
     * @return Collection
     */
    private function parseGames(array $data): Collection
    {
        $collection = collect([]);

        foreach ($data['results'] as $game) {
            $collection->push(
                new Game([
                    'id'               => $game[RawgField::Id->value],
                    'name'             => $game[RawgField::Name->value],
                    'slug'             => $game[RawgField::Slug->value],
                    'background_image' => $game[RawgField::BgImage->value],
                    'released'         => $game[RawgField::Released->value],
                    'platforms'        => array_column($game[RawgField::Platforms->value] ?: [], 'platform'),
                    'stores'           => array_column($game[RawgField::Stores->value] ?: [], 'store'),
                    'genres'           => $game[RawgField::Genres->value]
                ])
            );
        }

        return $collection;
    }
}
