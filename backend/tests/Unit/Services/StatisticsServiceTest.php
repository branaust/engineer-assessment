<?php

namespace Tests\Unit\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Statistics Service Test
 * 
 * Example test to guide your implementation.
 * Write tests for your statistics calculations here.
 */
class StatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that we can calculate average duration.
     */
    public function test_calculates_average_duration(): void
    {
        // TODO: Implement this test
        // Example:
        // SearchLog::factory()->create(['duration_ms' => 100]);
        // SearchLog::factory()->create(['duration_ms' => 200]);
        //
        // $service = new StatisticsService();
        // $average = $service->getAverageDuration();
        //
        // $this->assertEquals(150, $average);

        $this->markTestIncomplete('Implement StatisticsServiceTest@test_calculates_average_duration');
    }

    /**
     * Test that we can find the most popular search hour.
     */
    public function test_finds_most_popular_hour(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement StatisticsServiceTest@test_finds_most_popular_hour');
    }

    /**
     * Test that we can get top search queries with percentages.
     */
    public function test_gets_top_searches_with_percentages(): void
    {
        // TODO: Implement this test
        $this->markTestIncomplete('Implement StatisticsServiceTest@test_gets_top_searches_with_percentages');
    }

    /**
     * Test edge case: no search logs.
     */
    public function test_handles_no_search_logs(): void
    {
        // TODO: Implement this test
        // What should happen when there are no search logs?
        $this->markTestIncomplete('Implement StatisticsServiceTest@test_handles_no_search_logs');
    }
}

