<?php

namespace App\Http\Controllers;

use App\Services\Rawg\RawgDomainService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class RawgDomainController extends Controller
{
    public function __construct(
        private RawgDomainService $rawgDomainService
    ) {
    }

    #[OA\Get(
        path: '/api/rawg/domain/genres',
        tags: ['domain'],
        responses: [
            new OA\Response(response: 200, description: 'List of RAWG genres')
        ]
    )]
    public function genres(): JsonResponse
    {
        $data = $this->rawgDomainService->getGenres();

        return response()->json($data);
    }

    #[OA\Get(
        path: '/api/rawg/domain/tags',
        tags: ['domain'],
        responses: [
            new OA\Response(response: 200, description: 'List of RAWG tags')
        ]
    )]
    public function tags(): JsonResponse
    {
        $data = $this->rawgDomainService->getTags();

        return response()->json($data);
    }

    #[OA\Get(
        path: '/api/rawg/domain/platforms',
        tags: ['domain'],
        responses: [
            new OA\Response(response: 200, description: 'List of RAWG platforms')
        ]
    )]
    public function platforms(): JsonResponse
    {
        $data = $this->rawgDomainService->getPlatforms();

        return response()->json($data);
    }
}
