<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Person API Test
 *
 * Example test to guide your implementation.
 * Write tests for your CRUD operations here.
 *
 * Run tests: docker compose exec backend php artisan test
 */
class PersonApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the people endpoint returns a successful response.
     */
    public function test_can_list_people(): void
    {
        // TODO: Implement this test
        // Example:
        // Person::factory()->count(3)->create();
        //
        // $response = $this->getJson('/api/people');
        //
        // $response->assertStatus(200)
        //          ->assertJsonStructure(['data']);

        $this->markTestIncomplete('Implement PersonApiTest@test_can_list_people');
    }

    /**
     * Test that we can search for people.
     */
    public function test_can_search_people(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement PersonApiTest@test_can_search_people');
    }

    /**
     * Test that we can get a specific person.
     */
    public function test_can_show_person(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement PersonApiTest@test_can_show_person');
    }

    /**
     * Test that we can create a person.
     */
    public function test_can_create_person(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement PersonApiTest@test_can_create_person');
    }

    /**
     * Test that we can update a person.
     */
    public function test_can_update_person(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement PersonApiTest@test_can_update_person');
    }

    /**
     * Test that we can delete a person.
     */
    public function test_can_delete_person(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement PersonApiTest@test_can_delete_person');
    }

    /**
     * Test that person includes related films.
     */
    public function test_person_includes_films(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement PersonApiTest@test_person_includes_films');
    }
}
