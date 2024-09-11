<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(title: APP_NAME, version: APP_VERSION)]
#[OA\Server(url: APP_URL)]
class Application
{
    #[OA\Tag(
        name: 'application',
        description: 'health and other application routes'
    )]
    public function tags()
    {
    }

    #[OA\Get(
        path: '/up',
        tags: ['application'],
        security: [],
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
    public function up()
    {
    }
}
