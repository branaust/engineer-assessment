<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Film Factory
 *
 * Factory for creating Film model instances in tests and seeders.
 *
 * Usage in tests:
 *   Film::factory()->create();
 *   Film::factory()->count(5)->create();
 *
 * Feel free to add more attributes to the model as needed!
 */
class FilmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Film::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'episode_id' => fake()->unique()->numberBetween(1, 10),
            'opening_crawl' => fake()->paragraph(5),
            'director' => fake()->name(),
            'producer' => fake()->name(),
            'release_date' => fake()->date(),
            'swapi_url' => null,
        ];
    }
}
