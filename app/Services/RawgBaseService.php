<?php

namespace App\Services;

use GuzzleHttp\Client;

abstract class RawgBaseService
{
    protected string $apiKey;
    protected string $apiHost;
    protected Client $client;

    public function __construct(
        protected string $platforms = '4, 187, 186, 7'
    ) {
        $this->apiHost = config('services.rawg.host');
        $this->apiKey = config('services.rawg.key');

        $this->client = new Client([
            'base_uri' => $this->apiHost . '/api/'
        ]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return array
     */
    protected function call(
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
