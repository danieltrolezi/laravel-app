<?php

use App\Exceptions\ExceptionHandler;
use App\Http\Middleware\AuthenticateOnceWithBasicAuth;
use App\Http\Middleware\LogContextMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(LogContextMiddleware::class);
        $middleware->redirectGuestsTo(fn() => response());

        $middleware->alias([
            'auth.once.basic' => AuthenticateOnceWithBasicAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        new ExceptionHandler($exceptions);
    })->create();
