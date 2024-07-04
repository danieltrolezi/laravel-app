<?php

namespace App\Http\Controllers;

use App\Http\Requests\RawgAchievementRequest;
use App\Http\Requests\RawgGamesRequest;
use App\Services\RawgAchievementService;
use App\Services\RawgGamesService;
use Illuminate\Http\JsonResponse;

class RawgGamesController extends Controller
{
    public function __construct(
        private RawgGamesService $rawgGamesService
    ) {
    }

    /**
     * @return JsonResponse
     */
    public function recommendations(RawgGamesRequest $request, string $genre): JsonResponse
    {
        $response = $this->rawgGamesService->getRecommendations($genre, $request->validated());

        return response()->json($response->getContents());
    }

    /**
     * @return JsonResponse
     */
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
    public function achievements(RawgAchievementRequest $request, string $game): JsonResponse
    {
        $rawgAchievementService = resolve(RawgAchievementService::class);
        $response = $rawgAchievementService->getGameAchievements($game, $request->all());

        return response()->json($response);
    }
}
