<?php

namespace App\Services;

/**
 * Statistics Service
 * 
 * Service for calculating and retrieving search statistics.
 * 
 * This is an EXAMPLE service to guide your implementation.
 * You'll need to implement the logic to calculate:
 * - Average request duration
 * - Most popular hour of day (0-23)
 * - Top 5 search queries with percentages
 * 
 * Consider:
 * - Caching results (they update every 5 minutes anyway)
 * - Using database aggregation for performance
 * - Handling edge cases (no data, ties, etc.)
 */
class StatisticsService
{
    /**
     * Get all statistics.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'average_duration_ms' => $this->getAverageDuration(),
            'most_popular_hour' => $this->getMostPopularHour(),
            'top_searches' => $this->getTopSearches(),
        ];
    }

    /**
     * Calculate average request duration in milliseconds.
     *
     * @return float
     */
    public function getAverageDuration(): float
    {
        // TODO: Implement this method
        // Hint: Use SearchLog model and database aggregation
        
        return 0.0;
    }

    /**
     * Get the most popular hour of day for searches (0-23).
     *
     * @return int|null
     */
    public function getMostPopularHour(): ?int
    {
        // TODO: Implement this method
        // Hint: Extract hour from searched_at and count occurrences
        
        return null;
    }

    /**
     * Get top 5 search queries with usage percentages.
     *
     * @return array
     */
    public function getTopSearches(): array
    {
        // TODO: Implement this method
        // Return format example:
        // [
        //     ['query' => 'Luke', 'count' => 50, 'percentage' => 25.0],
        //     ['query' => 'Vader', 'count' => 30, 'percentage' => 15.0],
        //     ...
        // ]
        
        return [];
    }
}

