<?php

use App\Http\Controllers\RawgController;
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

        Route::controller(RawgController::class)->group(function () {
            Route::get('/recommendations/{genre}', 'recommendations');
            Route::get('/upcoming-releases/{period}', 'upcomingReleases')->where('period', 'week|month|year');
            Route::get('/compare-games', 'compareGames');
        });
    });
});