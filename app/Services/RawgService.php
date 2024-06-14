<?php

namespace App\Services;

use GuzzleHttp\Client;

class RawgService
{
    private string $apiKey;
    private string $apiHost;
    private Client $client;

    public function __construct(
        private string $platforms = '4, 187, 186, 7'
    ) {
        $this->apiHost = config('services.rawg.host');
        $this->apiKey = config('services.rawg.key');

        $this->client = new Client([
            'base_uri' => $this->apiHost . '/api/'
        ]);
    }

    public function getRecommendations(
        string $genre,
        int $count = 5
    ): array {
        return $this->call(uri: 'games', data: [
            'query' => [
                'dates'      => date('Y-m-d', strtotime('-1 year')) . ',' . date('Y-m-d'),
                'genre'      => $genre,
                'platforms'  => $this->platforms,
                'ordering'   => 'updated',
                'page_size'  => $count
            ]
        ]);
    }

    /**
     * @return array
     */
    public function getUpcomingReleases(
        string $period = 'week',
        int $count = 25
    ): array {
        return $this->call(uri: 'games', data: [
            'query' => [
                'dates'      => date('Y-m-d') . ',' . date('Y-m-d', strtotime('+1 ' . $period)),
                'platforms'  => $this->platforms,
                'ordering'   => 'released',
                'page_size'  => $count
            ]
        ]);
    }

    public function compareGames()
    {
        // @TODO
    }

    /**
     * @return array
     */
    public function getGenres(): array
    {
        return $this->call(uri: 'genres');
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->call(uri: 'tags');
    }

    /**
     * @return array
     */
    public function getPlatforms(): array
    {
        return $this->call(uri: 'platforms');
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return array
     */
    private function call(
        string $method = 'GET',
        string $uri = '',
        array $data = ['query' => []]
    ): array {
        $res = $this->client->request($method, $uri, [
            'query' => array_merge($data['query'], [
                'key' => $this->apiKey
            ])
        ]);

        return json_decode(
            $res->getBody()->getContents(),
            true
        );
    }
}
