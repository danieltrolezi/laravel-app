<?php

use App\Enums\Period;
use App\Enums\RawgGenre;
use App\Http\Controllers\RawgGamesController;
use App\Http\Controllers\RawgDomainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('rawg')->group(function () {
        Route::prefix('domain')->controller(RawgDomainController::class)->group(function() {
            Route::get('/genres', 'genres');
            Route::get('/tags', 'tags');
            Route::get('/platforms', 'platforms');
        });

        Route::prefix('games')->controller(RawgGamesController::class)->group(function () {
            Route::get('/recommendations/{genre}', 'recommendations')->where('genre', RawgGenre::valuesAsString('|'));
            Route::get('/upcoming-releases/{period}', 'upcomingReleases')->where('period', Period::valuesAsString('|'));
            Route::get('/{game}/achievements', 'achievements');
            Route::get('/compare', 'compare');
        });
    });
});