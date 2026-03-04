import { useRouter } from 'expo-router';
import React from 'react';
import { Pressable, StyleSheet, Text, View } from 'react-native';

import type { Film } from '../types/Film';

interface Props {
  film: Film;
  /** When true, navigate to film detail on press. Defaults to true. */
  navigable?: boolean;
}

export function FilmCard({ film, navigable = true }: Props) {
  const router = useRouter();

  return (
    <Pressable
      style={({ pressed }) => [styles.card, pressed && styles.pressed]}
      onPress={() => navigable && router.push(`/films/${film.id}`)}
    >
      <View style={styles.row}>
        <Text style={styles.episode}>Episode {film.episode_id}</Text>
        {navigable && <Text style={styles.chevron}>›</Text>}
      </View>
      <Text style={styles.title}>{film.title}</Text>
      <Text style={styles.meta}>
        {[film.director, film.release_date?.slice(0, 4)].filter(Boolean).join(' · ')}
      </Text>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  card: {
    backgroundColor: '#fff',
    paddingHorizontal: 16,
    paddingVertical: 14,
    borderBottomWidth: StyleSheet.hairlineWidth,
    borderBottomColor: '#e0e0e0',
  },
  pressed: {
    backgroundColor: '#f5f5f5',
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  episode: {
    fontSize: 12,
    fontWeight: '600',
    color: '#FFE81F',
    backgroundColor: '#1a1a2e',
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 4,
    overflow: 'hidden',
  },
  chevron: {
    fontSize: 20,
    color: '#bbb',
  },
  title: {
    fontSize: 17,
    fontWeight: '500',
    color: '#111',
    marginTop: 6,
  },
  meta: {
    fontSize: 13,
    color: '#888',
    marginTop: 3,
  },
});
