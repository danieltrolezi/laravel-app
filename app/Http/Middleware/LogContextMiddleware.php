<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogContextMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::shareContext([
            'app'        => Str::slug(config('app.name')),
            'env'        => config('app.env'),
            'request_id' => $request->header('X-Request-ID', (string) Str::uuid())
        ]);

        return $next($request);
    }
}
