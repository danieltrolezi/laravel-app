<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/docs', '/swagger/index.html');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
