<?php

namespace App\Http\Controllers;

use App\Services\RawgService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RawgController extends Controller
{
    public function __construct(
        private RawgService $rawgService
    ) {
    }

    /**
     * @return JsonResponse
     */
    public function recommendations(string $genre): JsonResponse
    {

        $data = $this->rawgService->getRecommendations($genre);

        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function upcomingReleases(string $period): JsonResponse
    {
        $data = $this->rawgService->getUpcomingReleases($period);

        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function compareGames(): JsonResponse
    {
        return response()->json([]);
    }
}
