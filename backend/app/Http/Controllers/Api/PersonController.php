<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Person Controller
 *
 * Handles API requests for Star Wars people/characters.
 *
 * This is an EXAMPLE controller to guide your implementation.
 * You'll need to implement the actual CRUD operations.
 *
 * Endpoints:
 * - GET    /api/people       - List all people (with search/filter)
 * - GET    /api/people/{id}  - Get specific person
 * - POST   /api/people       - Create new person
 * - PUT    /api/people/{id}  - Update person
 * - DELETE /api/people/{id}  - Delete person
 */
class PersonController extends Controller
{
    /**
     * Display a listing of people.
     *
     * Supports search via ?search=query parameter.
     *
     * Example: GET /api/people?search=Luke
     */
    public function index(Request $request): JsonResponse
    {
        // TODO: Implement this method
        // Consider:
        // - Search functionality
        // - Pagination
        // - Eager loading relationships
        // - Logging searches for statistics

        return response()->json([
            'data' => [],
            'message' => 'TODO: Implement PersonController@index',
        ]);
    }

    /**
     * Display the specified person.
     *
     * Example: GET /api/people/1
     */
    public function show(string $id): JsonResponse
    {
        // TODO: Implement this method
        // Consider:
        // - Loading related films
        // - Handling not found

        return response()->json([
            'data' => null,
            'message' => 'TODO: Implement PersonController@show',
        ]);
    }

    /**
     * Store a newly created person.
     *
     * Example: POST /api/people
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: Implement this method
        // Consider:
        // - Validation
        // - Handling relationships

        return response()->json([
            'data' => null,
            'message' => 'TODO: Implement PersonController@store',
        ], 201);
    }

    /**
     * Update the specified person.
     *
     * Example: PUT /api/people/1
     */
    public function update(Request $request, string $id): JsonResponse
    {
        // TODO: Implement this method
        // Consider:
        // - Validation
        // - Handling not found
        // - Updating relationships

        return response()->json([
            'data' => null,
            'message' => 'TODO: Implement PersonController@update',
        ]);
    }

    /**
     * Remove the specified person.
     *
     * Example: DELETE /api/people/1
     */
    public function destroy(string $id): JsonResponse
    {
        // TODO: Implement this method
        // Consider:
        // - Handling not found
        // - Cascade deleting relationships

        return response()->json([
            'message' => 'TODO: Implement PersonController@destroy',
        ]);
    }
}
