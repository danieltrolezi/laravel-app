<?php

namespace App\Services;

use App\Models\Game;
use App\Models\PaginatedResponse;
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
        int $perPage = 5,
        int $page = 1
    ): PaginatedResponse {
        $response = $this->call(uri: 'games', data: [
            'query' => [
                'dates'      => date('Y-m-d', strtotime('-1 year')) . ',' . date('Y-m-d'),
                'genre'      => $genre,
                'platforms'  => $this->platforms,
                'ordering'   => 'updated',
                'page_size'  => $perPage,
                'page'       => $page
            ]
        ]);

        return new PaginatedResponse(
            $this->parseGames($response),
            $perPage,
            $page,
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
        int $perPage = 25,
        int $page = 1
    ): PaginatedResponse {
        $response = $this->call(uri: 'games', data: [
            'query' => [
                'dates'      => date('Y-m-d') . ',' . date('Y-m-d', strtotime('+1 ' . $period)),
                'platforms'  => $this->platforms,
                'ordering'   => 'released',
                'page_size'  => $perPage,
                'page'       => $page
            ]
        ]);

        return new PaginatedResponse(
            $this->parseGames($response),
            $perPage,
            $page,
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
