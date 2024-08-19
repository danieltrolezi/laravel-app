<?php

use App\Enums\Period;
use App\Enums\RawgGenre;
use App\Enums\Scope;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RawgGamesController;
use App\Http\Controllers\RawgDomainController;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;

Route::middleware(['throttle:api'])->group(function () {
    Route::permanentRedirect('/docs', '/swagger/index.html');

    Route::get('/health', HealthCheckJsonResultsController::class);

    Route::post('/account/register', [AccountController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth', 'scopes:' . Scope::Default->value])->group(function () {
        Route::prefix('account')->controller(AccountController::class)->group(function () {
            Route::get('/show', 'show');
            Route::put('/update', 'update');
            Route::put('/settings', 'settings');
        });

        Route::prefix('rawg')
            ->middleware(['scopes:' . Scope::Root->value])
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