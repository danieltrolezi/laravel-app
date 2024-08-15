<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/auth/login',
        tags: ['auth'],
        security: [],
        responses: [
            new OA\Response(response: 200, description: 'Short-lived Access Token')
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'password', type: 'string', format: 'password')
                ]
            )
        )
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        return response()->json(
            resolve(AuthService::class)->getAccessToken(
                $request->only(['email', 'password'])
            )
        );
    }
}
