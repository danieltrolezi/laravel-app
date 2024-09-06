<?php

namespace App\Http\Middleware;

use App\Services\Discord\DiscordAppService;
use Closure;
use Illuminate\Http\Request;

class VerifyDiscordSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        resolve(DiscordAppService::class)->verifyDiscordSignature(
            $request->header('X-Signature-Ed25519'),
            $request->header('X-Signature-Timestamp'),
            $request->getContent()
        );

        return $next($request);
    }
}
