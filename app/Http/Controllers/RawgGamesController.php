<?php

namespace App\Http\Controllers;

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
        $response = $this->rawgGameService->getRecommendations(
            $genre,
            $request->get('per_page', 5),
            $request->get('page', 1)
        );

        return response()->json($response->getContents());
    }

    /**
     * @return JsonResponse
     */
    public function upcomingReleases(Request $request, string $period): JsonResponse
    {
        $response = $this->rawgGameService->getUpcomingReleases(
            $period,
            $request->get('per_page', 25),
            $request->get('page', 1)
        );

        return response()->json($response->getContents());
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
