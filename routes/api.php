<?php

use App\Enums\Period;
use App\Enums\Permission;
use App\Enums\RawgGenre;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RawgGamesController;
use App\Http\Controllers\RawgDomainController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/docs', '/swagger/index.html');

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'ability:' . Permission::Default->value])->group(function () {
    Route::prefix('rawg')
        ->middleware(['ability:' . Permission::Admin->value])
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