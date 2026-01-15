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

// Health check endpoint - useful for monitoring
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});

/*
|--------------------------------------------------------------------------
| API Routes - To Be Implemented
|--------------------------------------------------------------------------
|
| TODO: Implement the following routes for your Star Wars API:
|
| Resource Routes (RESTful):
| - GET    /api/people           - List all people with search/filter
| - GET    /api/people/{id}      - Get specific person details
| - POST   /api/people           - Create new person
| - PUT    /api/people/{id}      - Update person
| - DELETE /api/people/{id}      - Delete person
|
| - GET    /api/films            - List all films with search/filter
| - GET    /api/films/{id}       - Get specific film details
| - POST   /api/films            - Create new film
| - PUT    /api/films/{id}       - Update film
| - DELETE /api/films/{id}       - Delete film
|
| Statistics Route:
| - GET    /api/statistics       - Get search analytics
|   Returns:
|   - Average request duration
|   - Most popular hour of day (0-23)
|   - Top 5 search queries with percentages
*/

// Resource routes for People and Films
Route::apiResource('people', PersonController::class);
Route::apiResource('films', FilmController::class);

// Statistics endpoint
Route::get('/statistics', [StatisticsController::class, 'index']);
