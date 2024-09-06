<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscordInteractionRequest;
use App\Services\Discord\DiscordAppService;
use App\Services\Discord\DiscordInteractionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DiscordController extends Controller
{
    public function __construct(
        private DiscordAppService $appService,
        private DiscordInteractionsService $interactionsService
    ) {
    }

    public function interactions(DiscordInteractionRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $user = $this->appService->findOrCreateUser($payload);
        Auth::setUser($user);

        return response()->json(
            $this->interactionsService->handleInteractions($payload)
        );
    }
}
