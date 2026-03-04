import { useRouter } from 'expo-router';
import React, { useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  Pressable,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  View,
} from 'react-native';

import { useCreateFilm } from '@/src/api/films';

function Field({
  label,
  value,
  onChangeText,
  placeholder,
  multiline,
  keyboardType,
}: {
  label: string;
  value: string;
  onChangeText: (t: string) => void;
  placeholder?: string;
  multiline?: boolean;
  keyboardType?: 'default' | 'numeric';
}) {
  return (
    <View style={styles.field}>
      <Text style={styles.label}>{label}</Text>
      <TextInput
        style={[styles.input, multiline && styles.inputMultiline]}
        value={value}
        onChangeText={onChangeText}
        placeholder={placeholder ?? label}
        placeholderTextColor="#bbb"
        multiline={multiline}
        keyboardType={keyboardType}
      />
    </View>
  );
}

export default function CreateFilmScreen() {
  const router = useRouter();
  const mutation = useCreateFilm();

  const [title, setTitle] = useState('');
  const [episodeId, setEpisodeId] = useState('');
  const [director, setDirector] = useState('');
  const [producer, setProducer] = useState('');
  const [releaseDate, setReleaseDate] = useState('');
  const [openingCrawl, setOpeningCrawl] = useState('');

  const handleSave = () => {
    if (!title.trim()) {
      Alert.alert('Validation', 'Title is required.');
      return;
    }
    if (!episodeId || isNaN(Number(episodeId))) {
      Alert.alert('Validation', 'Episode ID must be a number.');
      return;
    }

    mutation.mutate(
      {
        title: title.trim(),
        episode_id: Number(episodeId),
        director: director || undefined,
        producer: producer || undefined,
        release_date: releaseDate || undefined,
        opening_crawl: openingCrawl || undefined,
      },
      {
        onSuccess: () => router.back(),
        onError: () => Alert.alert('Error', 'Failed to create film. Episode ID may already exist.'),
      }
    );
  };

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Field label="Title *" value={title} onChangeText={setTitle} placeholder="e.g. A New Hope" />
      <Field label="Episode ID *" value={episodeId} onChangeText={setEpisodeId} keyboardType="numeric" placeholder="e.g. 4" />
      <Field label="Director" value={director} onChangeText={setDirector} />
      <Field label="Producer" value={producer} onChangeText={setProducer} />
      <Field label="Release Date" value={releaseDate} onChangeText={setReleaseDate} placeholder="YYYY-MM-DD" />
      <Field
        label="Opening Crawl"
        value={openingCrawl}
        onChangeText={setOpeningCrawl}
        multiline
        placeholder="A long time ago in a galaxy far, far away..."
      />

      <Pressable style={styles.saveBtn} onPress={handleSave} disabled={mutation.isPending}>
        {mutation.isPending ? (
          <ActivityIndicator color="#fff" />
        ) : (
          <Text style={styles.saveBtnText}>Create Film</Text>
        )}
      </Pressable>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f9f9f9' },
  content: { padding: 16, paddingBottom: 40 },
  field: { marginBottom: 14 },
  label: { fontSize: 13, fontWeight: '600', color: '#555', marginBottom: 6, textTransform: 'uppercase', letterSpacing: 0.4 },
  input: {
    backgroundColor: '#fff',
    borderRadius: 10,
    paddingHorizontal: 14,
    paddingVertical: 12,
    fontSize: 16,
    color: '#111',
    borderWidth: StyleSheet.hairlineWidth,
    borderColor: '#ddd',
  },
  inputMultiline: { minHeight: 120, textAlignVertical: 'top' },
  saveBtn: {
    backgroundColor: '#0AB463',
    borderRadius: 10,
    paddingVertical: 14,
    alignItems: 'center',
    marginTop: 8,
  },
  saveBtnText: { color: '#fff', fontWeight: '600', fontSize: 16 },
});
