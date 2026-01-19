<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;

/**
 * Statistics Controller
 *
 * Handles API requests for search statistics.
 *
 * This is an EXAMPLE controller to guide your implementation.
 * Statistics should be updated every 5 minutes via a queue/scheduled job.
 *
 * Required metrics:
 * - Average request duration
 * - Most popular hour of day (0-23)
 * - Top 5 search queries with percentages
 *
 * Endpoint:
 * - GET /api/statistics - Get current statistics
 */
class StatisticsController extends Controller
{
    private StatisticsService $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Display search statistics.
     *
     * Example: GET /api/statistics
     *
     * Expected response format:
     * {
     *   "average_duration_ms": 150.5,
     *   "most_popular_hour": 14,
     *   "top_searches": [
     *     {"query": "Luke", "count": 50, "percentage": 25.0},
     *     {"query": "Vader", "count": 30, "percentage": 15.0},
     *     ...
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        // TODO: Implement this method
        // Consider:
        // - Caching results (updates every 5 min anyway)
        // - Using the StatisticsService

        $statistics = $this->statisticsService->getStatistics();

        return response()->json([
            'data' => $statistics,
            'message' => 'TODO: Implement actual statistics calculation',
        ]);
    }
}
