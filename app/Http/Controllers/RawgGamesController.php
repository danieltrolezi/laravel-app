<?php

namespace App\Http\Controllers;

use App\Services\RawgAchievementService;
use App\Services\RawgGameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RawgGamesController extends Controller
{
    public function __construct(
        private RawgGameService $rawgGameService
    ) {
    }

    /**
     * @return JsonResponse
     */
    public function recommendations(Request $request, string $genre): JsonResponse
    {
        // @TODO Validate Data
        $response = $this->rawgGameService->getRecommendations($genre, $request->all());

        return response()->json($response->getContents());
    }

    /**
     * @return JsonResponse
     */
    public function upcomingReleases(Request $request, string $period): JsonResponse
    {
        // @TODO Validate Data
        $response = $this->rawgGameService->getUpcomingReleases($period, $request->all());

        return response()->json($response->getContents());
    }

    /**
     * @param Request $request
     * @param string $game
     * @return JsonResponse
     */
    public function achievements(Request $request, string $game): JsonResponse
    {
        // @TODO Validate Data
        $rawgAchievementService = resolve(RawgAchievementService::class);
        $response = $rawgAchievementService->getGameAchievements($game, $request->all());

        return response()->json($response);
    }
}
