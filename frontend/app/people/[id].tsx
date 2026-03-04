import { useLocalSearchParams, useRouter } from 'expo-router';
import React from 'react';
import {
  ActivityIndicator,
  Alert,
  FlatList,
  Pressable,
  ScrollView,
  StyleSheet,
  Text,
  View,
} from 'react-native';

import { useDeletePerson, usePerson } from '@/src/api/people';
import { FilmCard } from '@/src/components/FilmCard';

function DetailRow({ label, value }: { label: string; value: string | null | undefined }) {
  if (!value || value === 'unknown' || value === 'n/a') return null;
  return (
    <View style={styles.detailRow}>
      <Text style={styles.detailLabel}>{label}</Text>
      <Text style={styles.detailValue}>{value}</Text>
    </View>
  );
}

export default function PersonDetailScreen() {
  const { id } = useLocalSearchParams<{ id: string }>();
  const router = useRouter();
  const { data, isLoading, isError } = usePerson(Number(id));
  const deleteMutation = useDeletePerson();

  const person = data?.data;

  const handleDelete = () => {
    Alert.alert(
      'Delete Character',
      `Are you sure you want to delete ${person?.name}?`,
      [
        { text: 'Cancel', style: 'cancel' },
        {
          text: 'Delete',
          style: 'destructive',
          onPress: () => {
            deleteMutation.mutate(Number(id), {
              onSuccess: () => router.back(),
            });
          },
        },
      ]
    );
  };

  if (isLoading) return <ActivityIndicator style={styles.loader} size="large" />;
  if (isError || !person) return <Text style={styles.error}>Character not found.</Text>;

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Text style={styles.name}>{person.name}</Text>

      <View style={styles.card}>
        <DetailRow label="Gender" value={person.gender} />
        <DetailRow label="Birth Year" value={person.birth_year} />
        <DetailRow label="Height" value={person.height ? `${person.height} cm` : null} />
        <DetailRow label="Mass" value={person.mass ? `${person.mass} kg` : null} />
        <DetailRow label="Hair Color" value={person.hair_color} />
        <DetailRow label="Skin Color" value={person.skin_color} />
        <DetailRow label="Eye Color" value={person.eye_color} />
        <DetailRow label="Homeworld" value={person.homeworld} />
      </View>

      {person.films && person.films.length > 0 && (
        <>
          <Text style={styles.sectionTitle}>Appears In</Text>
          <FlatList
            data={person.films}
            keyExtractor={(f) => String(f.id)}
            renderItem={({ item }) => <FilmCard film={item} />}
            scrollEnabled={false}
          />
        </>
      )}

      <View style={styles.actions}>
        <Pressable
          style={styles.editBtn}
          onPress={() => router.push(`/people/${id}/edit`)}
        >
          <Text style={styles.editBtnText}>Edit</Text>
        </Pressable>
        <Pressable style={styles.deleteBtn} onPress={handleDelete}>
          <Text style={styles.deleteBtnText}>Delete</Text>
        </Pressable>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f9f9f9' },
  content: { padding: 16, paddingBottom: 40 },
  loader: { flex: 1, marginTop: 80 },
  error: { textAlign: 'center', marginTop: 80, color: '#e74c3c', fontSize: 16 },
  name: { fontSize: 26, fontWeight: '700', color: '#111', marginBottom: 16 },
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 4,
    marginBottom: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.06,
    shadowRadius: 4,
    elevation: 2,
  },
  detailRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingHorizontal: 14,
    paddingVertical: 11,
    borderBottomWidth: StyleSheet.hairlineWidth,
    borderBottomColor: '#f0f0f0',
  },
  detailLabel: { fontSize: 15, color: '#888' },
  detailValue: { fontSize: 15, color: '#111', fontWeight: '500', maxWidth: '60%', textAlign: 'right' },
  sectionTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: '#555',
    textTransform: 'uppercase',
    letterSpacing: 0.5,
    marginBottom: 8,
  },
  actions: { flexDirection: 'row', gap: 12, marginTop: 24 },
  editBtn: {
    flex: 1,
    backgroundColor: '#0AB463',
    borderRadius: 10,
    paddingVertical: 14,
    alignItems: 'center',
  },
  editBtnText: { color: '#fff', fontWeight: '600', fontSize: 16 },
  deleteBtn: {
    flex: 1,
    backgroundColor: '#fff',
    borderRadius: 10,
    paddingVertical: 14,
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#e74c3c',
  },
  deleteBtnText: { color: '#e74c3c', fontWeight: '600', fontSize: 16 },
});
