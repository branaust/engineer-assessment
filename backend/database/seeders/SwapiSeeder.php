<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Models\Person;
use App\Services\SwapiService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Seeds the database with Star Wars data from SWAPI.
 * Films are seeded first since people reference film URLs.
 * Uses updateOrCreate so re-running is safe (idempotent).
 */
class SwapiSeeder extends Seeder
{
    private SwapiService $swapiService;

    public function __construct(SwapiService $swapiService)
    {
        $this->swapiService = $swapiService;
    }

    public function run(): void
    {
        Log::info('Starting SWAPI data seeding...');
        $this->command->info('Fetching films from SWAPI...');

        $filmMap = $this->seedFilms();
        $this->command->info('Seeded '.count($filmMap).' films.');

        $this->command->info('Fetching people from SWAPI (this may take a moment)...');
        $peopleData = $this->seedPeople();
        $this->command->info('Seeded '.count($peopleData).' people.');

        $this->command->info('Linking people to films...');
        $this->seedRelationships($peopleData, $filmMap);

        Log::info('SWAPI data seeding completed successfully!');
    }

    /**
     * Fetch and seed all films.
     *
     * @return array<string, Film> Map of swapi_url => Film model
     */
    private function seedFilms(): array
    {
        $filmsRaw = $this->swapiService->fetchFilms();
        $filmMap = [];

        DB::transaction(function () use ($filmsRaw, &$filmMap) {
            foreach ($filmsRaw as $data) {
                $film = Film::updateOrCreate(
                    ['swapi_url' => $data['swapi_url']],
                    Arr::except($data, ['swapi_url', 'character_urls'])
                );
                $filmMap[$data['swapi_url']] = $film;
            }
        });

        return $filmMap;
    }

    /**
     * Fetch and seed all people.
     *
     * @return array<int, array{model: Person, film_urls: array<string>}>
     */
    private function seedPeople(): array
    {
        $peopleRaw = $this->swapiService->fetchPeople();
        $peopleData = [];

        DB::transaction(function () use ($peopleRaw, &$peopleData) {
            foreach ($peopleRaw as $data) {
                $person = Person::updateOrCreate(
                    ['swapi_url' => $data['swapi_url']],
                    Arr::except($data, ['swapi_url', 'film_urls'])
                );
                $peopleData[] = [
                    'model'     => $person,
                    'film_urls' => $data['film_urls'] ?? [],
                ];
            }
        });

        return $peopleData;
    }

    /**
     * Attach each person to their films via the pivot table.
     *
     * @param  array<int, array{model: Person, film_urls: array<string>}>  $peopleData
     * @param  array<string, Film>  $filmMap
     */
    private function seedRelationships(array $peopleData, array $filmMap): void
    {
        foreach ($peopleData as $entry) {
            $filmIds = collect($entry['film_urls'])
                ->map(fn ($url) => $filmMap[$url]->id ?? null)
                ->filter()
                ->values()
                ->toArray();

            $entry['model']->films()->sync($filmIds);
        }
    }
}
