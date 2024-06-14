<?php

namespace App\Http\Controllers;

use App\Services\RawgGameService;
use Illuminate\Http\JsonResponse;

class RawgGamesController extends Controller
{
    public function __construct(
        private RawgGameService $rawgGameService
    ) {
    }

    /**
     * @return JsonResponse
     */
    public function recommendations(string $genre): JsonResponse
    {

        $data = $this->rawgGameService->getRecommendations($genre);

        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function upcomingReleases(string $period): JsonResponse
    {
        $data = $this->rawgGameService->getUpcomingReleases($period);

        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function compare(): JsonResponse
    {
        // @TODO
        return response()->json([]);
    }
}
