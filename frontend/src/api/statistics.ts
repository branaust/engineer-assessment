import { useQuery } from '@tanstack/react-query';

import { apiClient } from './client';
import type { Statistics } from '../types/Statistics';

export const useStatistics = () =>
  useQuery<{ data: Statistics }>({
    queryKey: ['statistics'],
    queryFn: () => apiClient.get('/statistics').then((r) => r.data),
    // Re-fetch every 5 minutes to stay aligned with the backend cache refresh
    refetchInterval: 5 * 60 * 1000,
    staleTime: 4 * 60 * 1000,
  });
