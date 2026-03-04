<?php

namespace App\Services;

use App\Models\SearchLog;
use Illuminate\Support\Facades\DB;

/**
 * Calculates search analytics from the search_logs table.
 * Results are cached in Redis by UpdateStatisticsJob every 5 minutes.
 */
class StatisticsService
{
    public function getStatistics(): array
    {
        return [
            'average_duration_ms' => $this->getAverageDuration(),
            'most_popular_hour'   => $this->getMostPopularHour(),
            'top_searches'        => $this->getTopSearches(),
        ];
    }

    /**
     * Average search duration across all logged requests.
     */
    public function getAverageDuration(): float
    {
        return (float) (SearchLog::avg('duration_ms') ?? 0.0);
    }

    /**
     * Hour of day (0-23) with the highest number of searches.
     * Returns null when no data exists.
     */
    public function getMostPopularHour(): ?int
    {
        $result = SearchLog::selectRaw('HOUR(searched_at) as hour, COUNT(*) as cnt')
            ->groupBy(DB::raw('HOUR(searched_at)'))
            ->orderByDesc('cnt')
            ->first();

        return $result ? (int) $result->hour : null;
    }

    /**
     * Top 5 non-empty search queries ranked by frequency, with percentage share.
     * Percentages are relative to all non-null query searches (not total requests).
     *
     * @return array<int, array{query: string, count: int, percentage: float}>
     */
    public function getTopSearches(): array
    {
        $total = SearchLog::whereNotNull('query')->count();

        if ($total === 0) {
            return [];
        }

        return SearchLog::selectRaw('query, COUNT(*) as count')
            ->whereNotNull('query')
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'query'      => $row->query,
                'count'      => (int) $row->count,
                'percentage' => round(($row->count / $total) * 100, 1),
            ])
            ->toArray();
    }
}
