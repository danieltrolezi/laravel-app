<?php

namespace App\Http\Controllers;

use App\Services\RawgDomainService;
use Illuminate\Http\JsonResponse;

class RawgDomainController extends Controller
{
    public function __construct(
        private RawgDomainService $rawgDomainService
    ) {
    }

    public function genres(): JsonResponse
    {
        $data = $this->rawgDomainService->getGenres();

        return response()->json($data);
    }

    public function tags(): JsonResponse
    {
        $data = $this->rawgDomainService->getTags();

        return response()->json($data);
    }

    public function platforms(): JsonResponse
    {
        $data = $this->rawgDomainService->getPlatforms();

        return response()->json($data);
    }
}
