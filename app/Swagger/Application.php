<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(title: APP_NAME, version: APP_VERSION)]
#[OA\Server(url: APP_URL)]
class Application
{
    // @TODO Add Authentication

    #[OA\Tag(
        name: 'application',
        description: 'health and other application routes'
    )]
    #[OA\Tag(
        name: 'domain',
        description: 'rawg domain routes'
    )]
    #[OA\Tag(
        name: 'games',
        description: 'rawg games routes'
    )]
    public function tags()
    {
    }

    #[OA\Get(
        path: '/health',
        tags: ['application'],
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
    public function health()
    {
    }
}
