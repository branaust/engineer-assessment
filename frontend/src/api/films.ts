import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';

import { apiClient } from './client';
import type { PaginatedResponse } from '../types/ApiResponse';
import type { Film, FilmFormData } from '../types/Film';

export const useFilms = (search?: string, page = 1) =>
  useQuery<PaginatedResponse<Film>>({
    queryKey: ['films', search, page],
    queryFn: () =>
      apiClient
        .get('/films', { params: { search: search || undefined, page } })
        .then((r) => r.data),
  });

export const useFilm = (id: number) =>
  useQuery<{ data: Film }>({
    queryKey: ['films', id],
    queryFn: () => apiClient.get(`/films/${id}`).then((r) => r.data),
    enabled: !!id,
  });

export const useCreateFilm = () => {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: FilmFormData) =>
      apiClient.post('/films', data).then((r) => r.data),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['films'] }),
  });
};

export const useUpdateFilm = (id: number) => {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: Partial<FilmFormData>) =>
      apiClient.put(`/films/${id}`, data).then((r) => r.data),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['films'] });
      qc.invalidateQueries({ queryKey: ['films', id] });
    },
  });
};

export const useDeleteFilm = () => {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (id: number) =>
      apiClient.delete(`/films/${id}`).then((r) => r.data),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['films'] }),
  });
};
