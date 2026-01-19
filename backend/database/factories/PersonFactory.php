<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Person Factory
 *
 * Factory for creating Person model instances in tests and seeders.
 *
 * Usage in tests:
 *   Person::factory()->create();
 *   Person::factory()->count(10)->create();
 *
 * Feel free to add more attributes to the model as needed!
 */
class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'height' => (string) fake()->numberBetween(150, 220),
            'mass' => (string) fake()->numberBetween(50, 150),
            'hair_color' => fake()->randomElement(['blond', 'brown', 'black', 'gray', 'none']),
            'skin_color' => fake()->randomElement(['fair', 'light', 'dark', 'pale']),
            'eye_color' => fake()->randomElement(['blue', 'brown', 'green', 'hazel', 'yellow']),
            'birth_year' => fake()->numberBetween(1, 100).'BBY',
            'gender' => fake()->randomElement(['male', 'female', 'n/a']),
            'homeworld' => fake()->word(),
            'swapi_url' => null,
        ];
    }
}
