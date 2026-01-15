<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

/**
 * SWAPI Service Test
 * 
 * Example test to guide your implementation.
 * Write tests for your SWAPI service here.
 * 
 * Consider testing:
 * - Successful API calls
 * - Error handling
 * - Data transformation
 * - Rate limiting
 */
class SwapiServiceTest extends TestCase
{
    /**
     * Test that we can fetch people from SWAPI.
     */
    public function test_can_fetch_people(): void
    {
        // TODO: Implement this test
        // Example using HTTP fakes:
        // Http::fake([
        //     'swapi.dev/api/people*' => Http::response([
        //         'results' => [
        //             ['name' => 'Luke Skywalker', ...],
        //         ]
        //     ], 200)
        // ]);
        //
        // $service = new SwapiService();
        // $people = $service->fetchPeople();
        //
        // $this->assertIsArray($people);
        // $this->assertNotEmpty($people);

        $this->markTestIncomplete('Implement SwapiServiceTest@test_can_fetch_people');
    }

    /**
     * Test that we can fetch films from SWAPI.
     */
    public function test_can_fetch_films(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement SwapiServiceTest@test_can_fetch_films');
    }

    /**
     * Test that we handle SWAPI errors gracefully.
     */
    public function test_handles_api_errors(): void
    {
        // TODO: Implement this test
        // Consider testing:
        // - 404 errors
        // - 500 errors
        // - Timeout errors
        // - Network errors

        $this->markTestIncomplete('Implement SwapiServiceTest@test_handles_api_errors');
    }
}

