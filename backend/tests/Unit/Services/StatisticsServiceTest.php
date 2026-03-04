<?php

namespace Tests\Unit\Services;

use App\Models\SearchLog;
use App\Services\StatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_average_duration(): void
    {
        SearchLog::factory()->create(['duration_ms' => 100]);
        SearchLog::factory()->create(['duration_ms' => 200]);

        $service = new StatisticsService();

        $this->assertEquals(150.0, $service->getAverageDuration());
    }

    public function test_finds_most_popular_hour(): void
    {
        // Two searches at 14:00, one at 08:00
        SearchLog::factory()->create(['searched_at' => now()->setHour(14)->setMinute(0)->setSecond(0)]);
        SearchLog::factory()->create(['searched_at' => now()->setHour(14)->setMinute(30)->setSecond(0)]);
        SearchLog::factory()->create(['searched_at' => now()->setHour(8)->setMinute(0)->setSecond(0)]);

        $service = new StatisticsService();

        $this->assertEquals(14, $service->getMostPopularHour());
    }

    public function test_gets_top_searches_with_percentages(): void
    {
        SearchLog::factory()->count(3)->create(['query' => 'Luke']);
        SearchLog::factory()->count(1)->create(['query' => 'Vader']);

        $service = new StatisticsService();
        $top = $service->getTopSearches();

        $this->assertCount(2, $top);
        $this->assertEquals('Luke', $top[0]['query']);
        $this->assertEquals(3, $top[0]['count']);
        $this->assertEquals(75.0, $top[0]['percentage']);
        $this->assertEquals('Vader', $top[1]['query']);
        $this->assertEquals(25.0, $top[1]['percentage']);
    }

    public function test_handles_no_search_logs(): void
    {
        $service = new StatisticsService();

        $this->assertEquals(0.0, $service->getAverageDuration());
        $this->assertNull($service->getMostPopularHour());
        $this->assertEmpty($service->getTopSearches());
    }
}
