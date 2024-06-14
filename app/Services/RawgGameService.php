<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Support\Collection;

class RawgGameService extends RawgBaseService
{
    public function getRecommendations(
        string $genre,
        int $count = 5
    ): Collection {
        $response = $this->call(uri: 'games', data: [
            'query' => [
                'dates'      => date('Y-m-d', strtotime('-1 year')) . ',' . date('Y-m-d'),
                'genre'      => $genre,
                'platforms'  => $this->platforms,
                'ordering'   => 'updated',
                'page_size'  => $count
            ]
        ]);

        return $this->parseGames($response);
    }

    /**
     * @return array
     */
    public function getUpcomingReleases(
        string $period = 'week',
        int $count = 25
    ): Collection {
        $response = $this->call(uri: 'games', data: [
            'query' => [
                'dates'      => date('Y-m-d') . ',' . date('Y-m-d', strtotime('+1 ' . $period)),
                'platforms'  => $this->platforms,
                'ordering'   => 'released',
                'page_size'  => $count
            ]
        ]);

        return $this->parseGames($response);
    }

    public function compare(): Collection
    {
        // @TODO
        $collection = collect([]);
        return $collection;
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
