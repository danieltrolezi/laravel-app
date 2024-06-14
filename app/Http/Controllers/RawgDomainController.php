<?php

namespace App\Http\Controllers;

use App\Services\RawgService;
use Illuminate\Http\JsonResponse;

class RawgDomainController extends Controller
{
    public function __construct(
        private RawgService $rawgService
    ) {
    }

    public function genres(): JsonResponse
    {
        $data = $this->rawgService->getGenres();

        return response()->json($data);
    }

    public function tags(): JsonResponse
    {
        $data = $this->rawgService->getTags();

        return response()->json($data);
    }

    public function platforms(): JsonResponse
    {
        $data = $this->rawgService->getPlatforms();

        return response()->json($data);
    }
}
