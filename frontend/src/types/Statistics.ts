export interface TopSearch {
  query: string;
  count: number;
  percentage: number;
}

export interface Statistics {
  average_duration_ms: number;
  most_popular_hour: number | null;
  top_searches: TopSearch[];
}
