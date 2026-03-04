import { useRouter } from 'expo-router';
import React from 'react';
import { Pressable, StyleSheet, Text, View } from 'react-native';

import type { Person } from '../types/Person';

interface Props {
  person: Person;
}

export function PersonCard({ person }: Props) {
  const router = useRouter();

  return (
    <Pressable
      style={({ pressed }) => [styles.card, pressed && styles.pressed]}
      onPress={() => router.push(`/people/${person.id}`)}
    >
      <View style={styles.row}>
        <Text style={styles.name}>{person.name}</Text>
        <Text style={styles.chevron}>›</Text>
      </View>
      <Text style={styles.meta}>
        {[person.gender, person.birth_year, person.homeworld]
          .filter(Boolean)
          .join(' · ')}
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
  name: {
    fontSize: 17,
    fontWeight: '500',
    color: '#111',
  },
  chevron: {
    fontSize: 20,
    color: '#bbb',
  },
  meta: {
    fontSize: 13,
    color: '#888',
    marginTop: 3,
  },
});
