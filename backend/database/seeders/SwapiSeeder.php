<?php

namespace Database\Seeders;

use App\Services\SwapiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * SWAPI Seeder
 * 
 * Seeds the database with data from the Star Wars API.
 * 
 * This is an EXAMPLE seeder to guide your implementation.
 * You'll need to:
 * 1. Fetch data from SWAPI using the SwapiService
 * 2. Transform the data to match your database schema
 * 3. Store it in your database
 * 4. Handle relationships (e.g., films and people)
 * 
 * Key considerations:
 * - The application must work even if SWAPI is offline
 * - Consider using database transactions
 * - Handle errors gracefully
 * - Log progress for debugging
 * - Consider rate limiting when calling SWAPI
 * - Consider using a repository instead of using models directly
 * 
 * Usage:
 *   php artisan db:seed --class=SwapiSeeder
 *   
 * Or run all seeders:
 *   php artisan db:seed
 */
class SwapiSeeder extends Seeder
{
    private SwapiService $swapiService;

    public function __construct(SwapiService $swapiService)
    {
        $this->swapiService = $swapiService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Log::info('Starting SWAPI data seeding...');

        try {
            // TODO: Implement seeding logic
            
            // Example approach:
            // 1. Fetch and seed films
            // $this->seedFilms();
            
            // 2. Fetch and seed people
            // $this->seedPeople();
            
            // 3. Create relationships (film_person pivot table)
            // $this->seedRelationships();
            
            Log::info('SWAPI data seeding completed successfully!');
        } catch (\Exception $e) {
            Log::error('Error seeding SWAPI data', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Seed films from SWAPI.
     */
    private function seedFilms(): void
    {
        // TODO: Implement this method
        // 1. Fetch films from SWAPI
        // 2. Transform data
        // 3. Insert into database
        
        Log::info('Seeding films...');
        
        // Example:
        // $filmsData = $this->swapiService->fetchFilms();
        // foreach ($filmsData['results'] ?? [] as $filmData) {
        //     Film::updateOrCreate(
        //         ['swapi_url' => $filmData['url']],
        //         [
        //             'title' => $filmData['title'],
        //             'episode_id' => $filmData['episode_id'],
        //             // ... map other fields
        //         ]
        //     );
        // }
    }

    /**
     * Seed people from SWAPI.
     */
    private function seedPeople(): void
    {
        // TODO: Implement this method
        Log::info('Seeding people...');
    }

    /**
     * Seed relationships between films and people.
     */
    private function seedRelationships(): void
    {
        // TODO: Implement this method
        // Link people to films based on SWAPI data
        Log::info('Seeding relationships...');
    }
}

