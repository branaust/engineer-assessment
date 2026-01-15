<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Film Controller
 * 
 * Handles API requests for Star Wars films.
 * 
 * This is an EXAMPLE controller to guide your implementation.
 * Similar structure to PersonController but for films.
 * 
 * Endpoints:
 * - GET    /api/films       - List all films (with search/filter)
 * - GET    /api/films/{id}  - Get specific film
 * - POST   /api/films       - Create new film
 * - PUT    /api/films/{id}  - Update film
 * - DELETE /api/films/{id}  - Delete film
 */
class FilmController extends Controller
{
    /**
     * Display a listing of films.
     * 
     * Supports search via ?search=query parameter.
     */
    public function index(Request $request): JsonResponse
    {
        // TODO: Implement this method
        return response()->json([
            'data' => [],
            'message' => 'TODO: Implement FilmController@index',
        ]);
    }

    /**
     * Display the specified film.
     */
    public function show(string $id): JsonResponse
    {
        // TODO: Implement this method
        return response()->json([
            'data' => null,
            'message' => 'TODO: Implement FilmController@show',
        ]);
    }

    /**
     * Store a newly created film.
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: Implement this method
        return response()->json([
            'data' => null,
            'message' => 'TODO: Implement FilmController@store',
        ], 201);
    }

    /**
     * Update the specified film.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        // TODO: Implement this method
        return response()->json([
            'data' => null,
            'message' => 'TODO: Implement FilmController@update',
        ]);
    }

    /**
     * Remove the specified film.
     */
    public function destroy(string $id): JsonResponse
    {
        // TODO: Implement this method
        return response()->json([
            'message' => 'TODO: Implement FilmController@destroy',
        ]);
    }
}

