import type { Person } from './Person';

export interface Film {
  id: number;
  title: string;
  episode_id: number;
  opening_crawl: string | null;
  director: string | null;
  producer: string | null;
  release_date: string | null;
  swapi_url: string | null;
  people?: Person[];
  created_at: string;
  updated_at: string;
}

export interface FilmFormData {
  title: string;
  episode_id: number;
  opening_crawl?: string;
  director?: string;
  producer?: string;
  release_date?: string;
  person_ids?: number[];
}
