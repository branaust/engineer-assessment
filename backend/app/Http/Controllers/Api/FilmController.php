<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\SearchLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FilmController extends Controller
{
    /**
     * List films with optional search. Logs searches for analytics.
     * GET /api/films?search=Hope&page=1
     */
    public function index(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $search = $request->query('search');

        $query = Film::with('people');

        if ($search !== null && $search !== '') {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $films = $query->paginate(15);
        $duration = (int) ((microtime(true) - $startTime) * 1000);

        if ($request->has('search')) {
            SearchLog::create([
                'query'         => $search ?: null,
                'resource_type' => 'films',
                'results_count' => $films->total(),
                'duration_ms'   => $duration,
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
                'searched_at'   => now(),
            ]);
        }

        return response()->json([
            'data'  => $films->items(),
            'links' => [
                'first' => $films->url(1),
                'last'  => $films->url($films->lastPage()),
                'prev'  => $films->previousPageUrl(),
                'next'  => $films->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $films->currentPage(),
                'from'         => $films->firstItem(),
                'last_page'    => $films->lastPage(),
                'per_page'     => $films->perPage(),
                'to'           => $films->lastItem(),
                'total'        => $films->total(),
            ],
        ]);
    }

    /**
     * Get a single film with its people.
     * GET /api/films/{id}
     */
    public function show(string $id): JsonResponse
    {
        $film = Film::with('people')->findOrFail($id);

        return response()->json(['data' => $film]);
    }

    /**
     * Create a new film.
     * POST /api/films
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'episode_id'    => 'required|integer|unique:films,episode_id',
            'opening_crawl' => 'nullable|string',
            'director'      => 'nullable|string|max:255',
            'producer'      => 'nullable|string|max:255',
            'release_date'  => 'nullable|date',
            'person_ids'    => 'nullable|array',
            'person_ids.*'  => 'integer|exists:people,id',
        ]);

        $film = Film::create(Arr::except($validated, ['person_ids']));

        if (! empty($validated['person_ids'])) {
            $film->people()->sync($validated['person_ids']);
        }

        return response()->json(['data' => $film->load('people')], 201);
    }

    /**
     * Update an existing film.
     * PUT /api/films/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $film = Film::findOrFail($id);

        $validated = $request->validate([
            'title'         => 'sometimes|required|string|max:255',
            'episode_id'    => "sometimes|required|integer|unique:films,episode_id,{$id}",
            'opening_crawl' => 'nullable|string',
            'director'      => 'nullable|string|max:255',
            'producer'      => 'nullable|string|max:255',
            'release_date'  => 'nullable|date',
            'person_ids'    => 'nullable|array',
            'person_ids.*'  => 'integer|exists:people,id',
        ]);

        $film->update(Arr::except($validated, ['person_ids']));

        if (array_key_exists('person_ids', $validated)) {
            $film->people()->sync($validated['person_ids'] ?? []);
        }

        return response()->json(['data' => $film->load('people')]);
    }

    /**
     * Delete a film. Cascade handles the pivot table.
     * DELETE /api/films/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $film = Film::findOrFail($id);
        $film->delete();

        return response()->json(['message' => 'Film deleted successfully']);
    }
}
