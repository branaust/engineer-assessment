<?php

namespace Database\Factories;

use App\Models\SearchLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * SearchLog Factory
 *
 * Factory for creating SearchLog model instances in tests.
 * Useful for testing statistics calculations.
 *
 * Usage in tests:
 *   SearchLog::factory()->create();
 *   SearchLog::factory()->count(50)->create();
 *
 * Feel free to add more attributes to the model as needed!
 */
class SearchLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SearchLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'query' => fake()->randomElement(['Luke', 'Vader', 'Leia', 'Han', 'Yoda', null]),
            'resource_type' => fake()->randomElement(['people', 'films']),
            'results_count' => fake()->numberBetween(0, 50),
            'duration_ms' => fake()->numberBetween(10, 500),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'searched_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
