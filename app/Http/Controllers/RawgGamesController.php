<?php

namespace App\Http\Controllers;

use App\Http\Requests\RawgAchievementRequest;
use App\Http\Requests\RawgGamesRequest;
use App\Services\RawgAchievementService;
use App\Services\RawgGamesService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class RawgGamesController extends Controller
{
    public function __construct(
        private RawgGamesService $rawgGamesService
    ) {
    }

    // @TODO Add Enums in Swagger

    /**
     * @return JsonResponse
     */
    #[OA\Get(
        path: '/api/rawg/games/recommendations/{genre}',
        tags: ['games'],
        parameters: [
            new OA\Parameter(
                name: 'genre',
                in: 'path',
                description: 'Filter by genres',
                required: true,
                schema: new OA\Schema(
                    ref: '#/components/schemas/RawgGenre'
                )
            ),
            new OA\Parameter(
                name: 'platforms',
                in: 'query',
                description: 'Filter by platforms (accepts comma separated list)',
                style: 'form',
                explode: false,
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        enum: 'App\Enums\Platform'
                    )
                )
            ),
            new OA\Parameter(name: 'ordering', in: 'query', description: 'Rawg field to order by'),
            new OA\Parameter(name: 'page', in: 'query', description: 'Page number to request'),
            new OA\Parameter(name: 'page_size', in: 'query', description: 'How many items per page')

        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of RAWG games',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PaginatedResponse'
                )
            )
        ]
    )]
    public function recommendations(RawgGamesRequest $request, string $genre): JsonResponse
    {
        $response = $this->rawgGamesService->getRecommendations($genre, $request->validated());

        return response()->json($response->getContents());
    }

    /**
     * @return JsonResponse
     */
    #[OA\Get(
        path: '/api/rawg/games/upcoming-releases/{period}',
        tags: ['games'],
        parameters: [
            new OA\Parameter(
                name: 'period',
                in: 'path',
                description: 'Get releases for selected period',
                required: true,
                schema: new OA\Schema(
                    ref: '#/components/schemas/Period'
                )
            ),
            new OA\Parameter(
                name: 'genres',
                in: 'query',
                description: 'Filter by genres (accepts comma separated list)',
                style: 'form',
                explode: false,
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        enum: 'App\Enums\RawgGenre'
                    )
                )
            ),
            new OA\Parameter(
                name: 'platforms',
                in: 'query',
                description: 'Filter by platforms (accepts comma separated list)',
                style: 'form',
                explode: false,
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        enum: 'App\Enums\Platform'
                    )
                )
            ),
            new OA\Parameter(name: 'ordering', in: 'query', description: 'Rawg field to order by'),
            new OA\Parameter(name: 'page', in: 'query', description: 'Page number to request'),
            new OA\Parameter(name: 'page_size', in: 'query', description: 'How many items per page')

        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of RAWG games',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PaginatedResponse'
                )
            )
        ]
    )]
    public function upcomingReleases(RawgGamesRequest $request, string $period): JsonResponse
    {
        $response = $this->rawgGamesService->getUpcomingReleases($period, $request->validated());

        return response()->json($response->getContents());
    }

    /**
     * @param Request $request
     * @param string $game
     * @return JsonResponse
     */
    #[OA\Get(
        path: '/api/rawg/games/{game}/achievements',
        tags: ['games'],
        parameters: [
            new OA\Parameter(
                name: 'game',
                in: 'path',
                description: 'Rawg slug of the game',
                required: true
            ),
            new OA\Parameter(name: 'order_by', in: 'query', description: 'Field to order by'),
            new OA\Parameter(
                name: 'sort_order',
                in: 'query',
                description: 'Sorting order',
                schema: new OA\Schema(
                    ref: '#/components/schemas/SortOrder'
                )
            ),
            new OA\Parameter(name: 'page', in: 'query', description: 'Page number to request'),
            new OA\Parameter(name: 'page_size', in: 'query', description: 'How many items per page')
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of RAWG game\'s achievements',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(
                                property: "id",
                                type: "integer"
                            ),
                            new OA\Property(
                                property: "name",
                                type: "string",
                            ),
                            new OA\Property(
                                property: "description",
                                type: "string",
                            ),
                            new OA\Property(
                                property: "image",
                                type: "string",
                            ),
                            new OA\Property(
                                property: "percent",
                                type: "string",
                            )
                        ]
                    )
                )
            )
        ]
    )]
    public function achievements(RawgAchievementRequest $request, string $game): JsonResponse
    {
        $rawgAchievementService = resolve(RawgAchievementService::class);
        $response = $rawgAchievementService->getGameAchievements($game, $request->all());

        return response()->json($response);
    }
}
