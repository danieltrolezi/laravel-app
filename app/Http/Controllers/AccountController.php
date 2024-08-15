<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
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
        security: [],
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
            $this->userRepository->create($request->all()),
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
                    new OA\Property(property: 'password', type: 'string', format: 'password', nullable: true)
                ]
            )
        )
    )]
    public function update(UpdateAccountRequest $request): JsonResponse
    {
        return response()->json(
            $this->userRepository->update(
                $request->user(),
                $request->all()
            )
        );
    }
}
