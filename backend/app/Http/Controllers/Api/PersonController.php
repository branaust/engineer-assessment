<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\SearchLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PersonController extends Controller
{
    /**
     * List people with optional search. Logs searches for analytics.
     * GET /api/people?search=Luke&page=1
     */
    public function index(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        $search = $request->query('search');

        $query = Person::with('films')->latest();

        if ($search !== null && $search !== '') {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $people = $query->paginate(15);
        $duration = (int) ((microtime(true) - $startTime) * 1000);

        // Only log when the search param is explicitly present
        if ($request->has('search')) {
            SearchLog::create([
                'query'         => $search ?: null,
                'resource_type' => 'people',
                'results_count' => $people->total(),
                'duration_ms'   => $duration,
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
                'searched_at'   => now(),
            ]);
        }

        return response()->json([
            'data'  => $people->items(),
            'links' => [
                'first' => $people->url(1),
                'last'  => $people->url($people->lastPage()),
                'prev'  => $people->previousPageUrl(),
                'next'  => $people->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $people->currentPage(),
                'from'         => $people->firstItem(),
                'last_page'    => $people->lastPage(),
                'per_page'     => $people->perPage(),
                'to'           => $people->lastItem(),
                'total'        => $people->total(),
            ],
        ]);
    }

    /**
     * Get a single person with their films.
     * GET /api/people/{id}
     */
    public function show(string $id): JsonResponse
    {
        $person = Person::with('films')->findOrFail($id);

        return response()->json(['data' => $person]);
    }

    /**
     * Create a new person.
     * POST /api/people
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'height'     => 'nullable|string|max:50',
            'mass'       => 'nullable|string|max:50',
            'hair_color' => 'nullable|string|max:100',
            'skin_color' => 'nullable|string|max:100',
            'eye_color'  => 'nullable|string|max:100',
            'birth_year' => 'nullable|string|max:50',
            'gender'     => 'nullable|string|max:50',
            'homeworld'  => 'nullable|string|max:255',
            'film_ids'   => 'nullable|array',
            'film_ids.*' => 'integer|exists:films,id',
        ]);

        $person = Person::create(Arr::except($validated, ['film_ids']));

        if (! empty($validated['film_ids'])) {
            $person->films()->sync($validated['film_ids']);
        }

        return response()->json(['data' => $person->load('films')], 201);
    }

    /**
     * Update an existing person.
     * PUT /api/people/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $person = Person::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'sometimes|required|string|max:255',
            'height'     => 'nullable|string|max:50',
            'mass'       => 'nullable|string|max:50',
            'hair_color' => 'nullable|string|max:100',
            'skin_color' => 'nullable|string|max:100',
            'eye_color'  => 'nullable|string|max:100',
            'birth_year' => 'nullable|string|max:50',
            'gender'     => 'nullable|string|max:50',
            'homeworld'  => 'nullable|string|max:255',
            'film_ids'   => 'nullable|array',
            'film_ids.*' => 'integer|exists:films,id',
        ]);

        $person->update(Arr::except($validated, ['film_ids']));

        if (array_key_exists('film_ids', $validated)) {
            $person->films()->sync($validated['film_ids'] ?? []);
        }

        return response()->json(['data' => $person->load('films')]);
    }

    /**
     * Delete a person. Cascade handles the pivot table.
     * DELETE /api/people/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return response()->json(['message' => 'Person deleted successfully']);
    }
}
