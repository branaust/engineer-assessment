import { useInfiniteQuery, useMutation, useQuery, useQueryClient } from '@tanstack/react-query';

import { apiClient } from './client';
import type { PaginatedResponse } from '../types/ApiResponse';
import type { Person, PersonFormData } from '../types/Person';

export const usePeople = (search?: string, page = 1) =>
  useQuery<PaginatedResponse<Person>>({
    queryKey: ['people', search, page],
    queryFn: () =>
      apiClient
        .get('/people', { params: { search: search || undefined, page } })
        .then((r) => r.data),
  });

export const usePeopleInfinite = (search?: string) =>
  useInfiniteQuery<PaginatedResponse<Person>>({
    queryKey: ['people-infinite', search],
    initialPageParam: 1,
    queryFn: ({ pageParam }) =>
      apiClient
        .get('/people', { params: { search: search || undefined, page: pageParam } })
        .then((r) => r.data),
    getNextPageParam: (lastPage) =>
      lastPage.meta.current_page < lastPage.meta.last_page
        ? lastPage.meta.current_page + 1
        : undefined,
  });

export const usePerson = (id: number) =>
  useQuery<{ data: Person }>({
    queryKey: ['people', id],
    queryFn: () => apiClient.get(`/people/${id}`).then((r) => r.data),
    enabled: !!id,
  });

export const useCreatePerson = () => {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: PersonFormData) =>
      apiClient.post('/people', data).then((r) => r.data),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['people-infinite'] }),
  });
};

export const useUpdatePerson = (id: number) => {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: Partial<PersonFormData>) =>
      apiClient.put(`/people/${id}`, data).then((r) => r.data),
    onSuccess: () => {
      qc.invalidateQueries({ queryKey: ['people-infinite'] });
      qc.invalidateQueries({ queryKey: ['people', id] });
    },
  });
};

export const useDeletePerson = () => {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (id: number) =>
      apiClient.delete(`/people/${id}`).then((r) => r.data),
    onSuccess: () => qc.invalidateQueries({ queryKey: ['people-infinite'] }),
  });
};
