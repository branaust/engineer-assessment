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

import { useDeleteFilm, useFilm } from '@/src/api/films';
import { PersonCard } from '@/src/components/PersonCard';

function DetailRow({ label, value }: { label: string; value: string | null | undefined }) {
  if (!value) return null;
  return (
    <View style={styles.detailRow}>
      <Text style={styles.detailLabel}>{label}</Text>
      <Text style={styles.detailValue}>{value}</Text>
    </View>
  );
}

export default function FilmDetailScreen() {
  const { id } = useLocalSearchParams<{ id: string }>();
  const router = useRouter();
  const { data, isLoading, isError } = useFilm(Number(id));
  const deleteMutation = useDeleteFilm();

  const film = data?.data;

  const handleDelete = () => {
    Alert.alert('Delete Film', `Are you sure you want to delete "${film?.title}"?`, [
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
    ]);
  };

  if (isLoading) return <ActivityIndicator style={styles.loader} size="large" />;
  if (isError || !film) return <Text style={styles.error}>Film not found.</Text>;

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <View style={styles.episodeBadge}>
        <Text style={styles.episodeText}>Episode {film.episode_id}</Text>
      </View>
      <Text style={styles.title}>{film.title}</Text>

      <View style={styles.card}>
        <DetailRow label="Director" value={film.director} />
        <DetailRow label="Producer" value={film.producer} />
        <DetailRow label="Release Date" value={film.release_date ?? undefined} />
      </View>

      {film.opening_crawl && (
        <>
          <Text style={styles.sectionTitle}>Opening Crawl</Text>
          <View style={styles.crawlCard}>
            <Text style={styles.crawlText}>{film.opening_crawl.trim()}</Text>
          </View>
        </>
      )}

      {film.people && film.people.length > 0 && (
        <>
          <Text style={styles.sectionTitle}>Characters</Text>
          <FlatList
            data={film.people}
            keyExtractor={(p) => String(p.id)}
            renderItem={({ item }) => <PersonCard person={item} />}
            scrollEnabled={false}
          />
        </>
      )}

      <View style={styles.actions}>
        <Pressable
          style={styles.editBtn}
          onPress={() => router.push(`/films/${id}/edit`)}
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
  episodeBadge: {
    alignSelf: 'flex-start',
    backgroundColor: '#1a1a2e',
    borderRadius: 6,
    paddingHorizontal: 10,
    paddingVertical: 4,
    marginBottom: 8,
  },
  episodeText: { color: '#FFE81F', fontSize: 13, fontWeight: '700' },
  title: { fontSize: 26, fontWeight: '700', color: '#111', marginBottom: 16 },
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
  crawlCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.06,
    shadowRadius: 4,
    elevation: 2,
  },
  crawlText: { fontSize: 15, color: '#444', lineHeight: 22 },
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
