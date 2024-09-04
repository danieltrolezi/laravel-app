<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscordInteractionRequest;
use App\Services\Discord\DiscordService;
use Illuminate\Http\JsonResponse;

class DiscordController extends Controller
{
    public function __construct(
        private DiscordService $discordService
    ) {
    }

    public function interactions(DiscordInteractionRequest $request): JsonResponse
    {
        return response()->json(
            $this->discordService->handleInteractions($request->validated())
        );
    }
}
