<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for fetching Star Wars data from SWAPI during seeding.
 * All data is stored locally so the app never calls SWAPI at runtime.
 *
 * Uses swapi.dev which returns full properties in the paginated list response:
 *   GET /people/        → { count, next, results: [{ name, height, films: [...] }] }
 *   GET /films/         → { count, next, results: [{ title, characters: [...] }] }
 */
class SwapiService
{
    private string $baseUrl;

    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.swapi.base_url', 'https://swapi.dev/api');
        $this->timeout = config('services.swapi.timeout', 30);
    }

    /**
     * Fetch all people from SWAPI.
     * swapi.dev returns full properties in the paginated list — no individual fetches needed.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchPeople(): array
    {
        try {
            $people = [];
            $url = $this->baseUrl.'/people/';

            while ($url !== null) {
                $response = Http::timeout($this->timeout)->get($url);

                if (! $response->successful()) {
                    Log::error('Failed to fetch people from SWAPI', ['status' => $response->status(), 'url' => $url]);
                    break;
                }

                $data = $response->json();
                foreach ($data['results'] ?? [] as $item) {
                    $people[] = $this->transformPerson($item);
                }

                $url = $data['next'] ?? null;
            }

            return $people;
        } catch (\Exception $e) {
            Log::error('Exception while fetching people from SWAPI', ['message' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * Fetch all films from SWAPI.
     * swapi.dev returns full properties in a single paginated response.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchFilms(): array
    {
        try {
            $films = [];
            $url = $this->baseUrl.'/films/';

            while ($url !== null) {
                $response = Http::timeout($this->timeout)->get($url);

                if (! $response->successful()) {
                    Log::error('Failed to fetch films from SWAPI', ['status' => $response->status()]);
                    break;
                }

                $data = $response->json();
                foreach ($data['results'] ?? [] as $item) {
                    $films[] = $this->transformFilm($item);
                }

                $url = $data['next'] ?? null;
            }

            return $films;
        } catch (\Exception $e) {
            Log::error('Exception while fetching films from SWAPI', ['message' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * Fetch a single resource by URL. Returns null on failure.
     */
    public function fetchByUrl(string $url): ?array
    {
        try {
            $response = Http::timeout($this->timeout)->get($url);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Exception fetching URL from SWAPI', ['url' => $url, 'message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Transform a person result from swapi.dev (properties at top level).
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function transformPerson(array $item): array
    {
        return [
            'name'       => $item['name'] ?? null,
            'height'     => $item['height'] ?? null,
            'mass'       => $item['mass'] ?? null,
            'hair_color' => $item['hair_color'] ?? null,
            'skin_color' => $item['skin_color'] ?? null,
            'eye_color'  => $item['eye_color'] ?? null,
            'birth_year' => $item['birth_year'] ?? null,
            'gender'     => $item['gender'] ?? null,
            'homeworld'  => $item['homeworld'] ?? null,
            'swapi_url'  => $item['url'] ?? null,
            'film_urls'  => $item['films'] ?? [],
        ];
    }

    /**
     * Transform a film result from swapi.dev (properties at top level).
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function transformFilm(array $item): array
    {
        return [
            'title'          => $item['title'] ?? null,
            'episode_id'     => $item['episode_id'] ?? null,
            'opening_crawl'  => $item['opening_crawl'] ?? null,
            'director'       => $item['director'] ?? null,
            'producer'       => $item['producer'] ?? null,
            'release_date'   => $item['release_date'] ?? null,
            'swapi_url'      => $item['url'] ?? null,
            'character_urls' => $item['characters'] ?? [],
        ];
    }
}
