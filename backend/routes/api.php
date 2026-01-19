<?php

use App\Http\Controllers\Api\FilmController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
| All routes here are automatically prefixed with /api
|
*/

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Resource routes
Route::apiResource('people', PersonController::class);
Route::apiResource('films', FilmController::class);

// Statistics endpoint
Route::get('/statistics', [StatisticsController::class, 'index']);
