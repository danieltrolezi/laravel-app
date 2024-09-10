<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class AccountController extends Controller
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    #[OA\Get(
        path: '/api/account/show',
        tags: ['account'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Account data',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/User'
                )
            )
        ]
    )]
    public function show(Request $request)
    {
        return response()->json(
            $request->user()
        );
    }

    #[OA\Post(
        path: '/api/account/register',
        tags: ['account'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Account data',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/User'
                )
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', nullable: false),
                    new OA\Property(property: 'email', type: 'string', nullable: false),
                    new OA\Property(property: 'password', type: 'string', format: 'password', nullable: false)
                ]
            )
        )
    )]
    public function register(RegisterAccountRequest $request): JsonResponse
    {
        return response()->json(
            $this->userRepository->create($request->validated()),
            Response::HTTP_CREATED
        );
    }

    #[OA\Put(
        path: '/api/account/update',
        tags: ['account'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Account data',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/User'
                )
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', nullable: true),
                    new OA\Property(property: 'email', type: 'string', nullable: true),
                    new OA\Property(property: 'username', type: 'string', nullable: true),
                    new OA\Property(property: 'password', type: 'string', format: 'password', nullable: true),
                    new OA\Property(property: 'discord_user_id', type: 'string', nullable: true),
                ]
            )
        )
    )]
    public function update(UpdateAccountRequest $request): JsonResponse
    {
        return response()->json(
            $this->userRepository->update(
                $request->user(),
                $request->validated()
            )
        );
    }

    #[OA\Put(
        path: '/api/account/settings',
        tags: ['account'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Account data',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/User'
                )
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'platforms',
                        type: 'array',
                        items: new OA\Items(
                            type: 'string',
                            enum: 'App\Enums\Platform'
                        ),
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'genres',
                        type: 'array',
                        items: new OA\Items(
                            type: 'string',
                            enum: 'App\Enums\Rawg\RawgGenre'
                        ),
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'period',
                        type: 'string',
                        nullable: true,
                        enum: 'App\Enums\Period'
                    ),
                    new OA\Property(
                        property: 'frequency',
                        type: 'string',
                        nullable: true,
                        enum: 'App\Enums\Frequency'
                    ),
                ]
            )
        )
    )]
    public function settings(UpdateSettingsRequest $request): JsonResponse
    {
        return response()->json(
            $this->userRepository->updateSettings(
                $request->user(),
                $request->validated()
            )
        );
    }
}
