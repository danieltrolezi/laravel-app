<?php

use App\Enums\Period;
use App\Enums\Rawg\RawgGenre;
use App\Enums\Scope;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\RawgGamesController;
use App\Http\Controllers\RawgDomainController;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;

Route::get('/health', HealthCheckJsonResultsController::class);

Route::permanentRedirect('/docs', '/swagger/index.html');

Route::prefix('discord')
    ->middleware(['discord.sign', 'throttle:discord'])
    ->controller(DiscordController::class)
    ->group(function () {
        Route::post('/interactions', 'interactions');
    });

Route::middleware('throttle:api')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth', 'scopes:' . Scope::Default->value])->group(function () {
        Route::prefix('account')->controller(AccountController::class)->group(function () {
            Route::get('/show', 'show');
            Route::put('/update', 'update');
            Route::put('/settings', 'settings');
        });

        Route::middleware('scopes:' . Scope::Root->value)->group(function () {
            Route::post('/account/register', [AccountController::class, 'register']);
            
            Route::prefix('rawg')
                ->group(function () {
                Route::prefix('domain')->controller(RawgDomainController::class)->group(function() {
                    Route::get('/genres', 'genres');
                    Route::get('/tags', 'tags');
                    Route::get('/platforms', 'platforms');
                });

                Route::prefix('games')->controller(RawgGamesController::class)->group(function () {
                    Route::get('/recommendations/{genre}', 'recommendations')->where('genre', RawgGenre::valuesAsString('|'));
                    Route::get('/upcoming-releases/{period}', 'upcomingReleases')->where('period', Period::valuesAsString('|'));
                    Route::get('/{game}/achievements', 'achievements');
                });
            });
        });
    });
});