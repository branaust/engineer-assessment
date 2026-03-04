<?php

namespace Tests\Feature\Api;

use App\Models\Film;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_people(): void
    {
        Person::factory()->count(3)->create();

        $this->getJson('/api/people')
            ->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_can_search_people(): void
    {
        Person::factory()->create(['name' => 'Luke Skywalker']);
        Person::factory()->create(['name' => 'Darth Vader']);

        $this->getJson('/api/people?search=Luke')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Luke Skywalker');
    }

    public function test_can_show_person(): void
    {
        $person = Person::factory()->create();

        $this->getJson("/api/people/{$person->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $person->id)
            ->assertJsonStructure(['data' => ['id', 'name', 'films']]);
    }

    public function test_can_create_person(): void
    {
        $this->postJson('/api/people', ['name' => 'Obi-Wan Kenobi', 'gender' => 'male'])
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Obi-Wan Kenobi');

        $this->assertDatabaseHas('people', ['name' => 'Obi-Wan Kenobi']);
    }

    public function test_can_update_person(): void
    {
        $person = Person::factory()->create(['name' => 'Old Name']);

        $this->putJson("/api/people/{$person->id}", ['name' => 'New Name'])
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'New Name');

        $this->assertDatabaseHas('people', ['id' => $person->id, 'name' => 'New Name']);
    }

    public function test_can_delete_person(): void
    {
        $person = Person::factory()->create();

        $this->deleteJson("/api/people/{$person->id}")
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing('people', ['id' => $person->id]);
    }

    public function test_person_includes_films(): void
    {
        $film = Film::factory()->create();
        $person = Person::factory()->create();
        $person->films()->attach($film->id);

        $this->getJson("/api/people/{$person->id}")
            ->assertStatus(200)
            ->assertJsonCount(1, 'data.films')
            ->assertJsonPath('data.films.0.id', $film->id);
    }
}
