import type { Film } from './Film';

export interface Person {
  id: number;
  name: string;
  height: string | null;
  mass: string | null;
  hair_color: string | null;
  skin_color: string | null;
  eye_color: string | null;
  birth_year: string | null;
  gender: string | null;
  homeworld: string | null;
  swapi_url: string | null;
  films?: Film[];
  created_at: string;
  updated_at: string;
}

export interface PersonFormData {
  name: string;
  height?: string;
  mass?: string;
  hair_color?: string;
  skin_color?: string;
  eye_color?: string;
  birth_year?: string;
  gender?: string;
  homeworld?: string;
  film_ids?: number[];
}
