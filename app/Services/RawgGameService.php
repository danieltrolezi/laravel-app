<?php

namespace App\Services;

use App\Enums\Platform;
use App\Enums\RawgField;
use App\Models\Game;
use App\Models\PaginatedResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RawgGameService extends RawgBaseService
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
        $query = $this->getQueryFilters(params: $filters, default: [
            RawgField::Dates->value    => date('Y-m-d', strtotime('-1 year')) . ',' . date('Y-m-d'),
            RawgField::Genre->value    => $genre,
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
        string $period = 'week',
        array $filters = []
    ): PaginatedResponse {
        $query = $this->getQueryFilters(params: $filters, default: [
            RawgField::Dates->value    => date('Y-m-d') . ',' . date('Y-m-d', strtotime('+1 ' . $period)),
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
     * @param array $params
     * @param array $default
     * @return array
     */
    private function getQueryFilters(array $params, array $default): array
    {
        $params = Arr::only($params, $this->getAvailableApiFilters());
        $filters = array_merge($default, $params);

        $platforms = implode(', ', Platform::values());
        $filters[RawgField::Platforms->value] = Arr::get($params, 'platforms', $platforms);

        return $filters;
    }

    /**
     * @return array
     */
    private function getAvailableApiFilters(): array
    {
        return [
            RawgField::Genre->value,
            RawgField::Platforms->value,
            RawgField::Ordering->value,
            RawgField::PageSize->value,
            RawgField::Page->value
        ];
    }

    /**
     * @param array $data
     * @return Collection
     */
    private function parseGames(array $data): Collection
    {
        $collection = collect([]);

        // @TODO Use Enum
        foreach ($data['results'] as $game) {
            $collection->push(
                new Game([
                    'id'               => $game['id'],
                    'name'             => $game['name'],
                    'slug'             => $game['slug'],
                    'background_image' => $game['background_image'],
                    'released'         => $game['released'],
                    'platforms'        => array_column($game['platforms'] ?: [], 'platform'),
                    'stores'           => array_column($game['stores'] ?: [], 'store'),
                    'genres'           => $game['genres']
                ])
            );
        }

        return $collection;
    }
}
