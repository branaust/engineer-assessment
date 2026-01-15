<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SWAPI Service
 * 
 * Service for interacting with the Star Wars API (SWAPI).
 * 
 * This is an EXAMPLE service to guide your implementation.
 * You'll need to implement methods to fetch data from SWAPI during seeding.
 * 
 * Key considerations:
 * - Rate limiting: SWAPI may have rate limits
 * - Error handling: API might be down or return errors
 * - Data transformation: Convert SWAPI format to your database format
 * - Pagination: SWAPI returns paginated results
 * - Parallel requests: Consider using Http::pool() for better performance
 * - Related resources: Each person/film has related URLs that need fetching
 * 
 * SWAPI Documentation: https://swapi.tech/documentation
 */
class SwapiService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        // TODO: Add SWAPI_BASE_URL to your .env file
        $this->baseUrl = config('services.swapi.base_url', 'https://swapi.tech/api');
        $this->timeout = config('services.swapi.timeout', 10);
    }

    /**
     * Fetch all people from SWAPI.
     * 
     * Example implementation to get you started.
     * You may want to handle pagination, errors, and data transformation.
     *
     * @return array
     */
    public function fetchPeople(): array
    {
        // TODO: Implement this method
        // Consider:
        // - Fetching all pages of results (SWAPI is paginated)
        // - Handling errors gracefully
        // - Transforming data to match your database schema
        // - Rate limiting / throttling requests
        // - Each person has related films URLs - you'll need to fetch those too
        // - For better performance, consider using Http::pool() for parallel requests
        
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . '/people/');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch people from SWAPI', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Exception while fetching people from SWAPI', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Fetch all films from SWAPI.
     *
     * @return array
     */
    public function fetchFilms(): array
    {
        // TODO: Implement this method
        // Similar considerations as fetchPeople()
        // Each film has related people URLs that need to be fetched
        
        return [];
    }

    /**
     * Fetch a single resource by URL.
     * 
     * Useful for fetching related resources (like film URLs from a person).
     * 
     * Tip: If you need to fetch many related URLs, consider using Http::pool()
     * for parallel requests instead of sequential ones.
     *
     * @param string $url
     * @return array|null
     */
    public function fetchByUrl(string $url): ?array
    {
        // TODO: Implement this method
        
        return null;
    }
}

