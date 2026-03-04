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

import { useCreatePerson } from '@/src/api/people';

function Field({
  label,
  value,
  onChangeText,
  placeholder,
}: {
  label: string;
  value: string;
  onChangeText: (t: string) => void;
  placeholder?: string;
}) {
  return (
    <View style={styles.field}>
      <Text style={styles.label}>{label}</Text>
      <TextInput
        style={styles.input}
        value={value}
        onChangeText={onChangeText}
        placeholder={placeholder ?? label}
        placeholderTextColor="#bbb"
      />
    </View>
  );
}

export default function CreatePersonScreen() {
  const router = useRouter();
  const mutation = useCreatePerson();

  const [name, setName] = useState('');
  const [height, setHeight] = useState('');
  const [mass, setMass] = useState('');
  const [hairColor, setHairColor] = useState('');
  const [skinColor, setSkinColor] = useState('');
  const [eyeColor, setEyeColor] = useState('');
  const [birthYear, setBirthYear] = useState('');
  const [gender, setGender] = useState('');
  const [homeworld, setHomeworld] = useState('');

  const handleSave = () => {
    if (!name.trim()) {
      Alert.alert('Validation', 'Name is required.');
      return;
    }

    mutation.mutate(
      {
        name: name.trim(),
        height: height || undefined,
        mass: mass || undefined,
        hair_color: hairColor || undefined,
        skin_color: skinColor || undefined,
        eye_color: eyeColor || undefined,
        birth_year: birthYear || undefined,
        gender: gender || undefined,
        homeworld: homeworld || undefined,
      },
      {
        onSuccess: () => router.back(),
        onError: () => Alert.alert('Error', 'Failed to create character.'),
      }
    );
  };

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Field label="Name *" value={name} onChangeText={setName} placeholder="e.g. Han Solo" />
      <Field label="Gender" value={gender} onChangeText={setGender} placeholder="male / female / n/a" />
      <Field label="Birth Year" value={birthYear} onChangeText={setBirthYear} placeholder="e.g. 29BBY" />
      <Field label="Height (cm)" value={height} onChangeText={setHeight} placeholder="e.g. 180" />
      <Field label="Mass (kg)" value={mass} onChangeText={setMass} placeholder="e.g. 80" />
      <Field label="Hair Color" value={hairColor} onChangeText={setHairColor} />
      <Field label="Skin Color" value={skinColor} onChangeText={setSkinColor} />
      <Field label="Eye Color" value={eyeColor} onChangeText={setEyeColor} />
      <Field label="Homeworld" value={homeworld} onChangeText={setHomeworld} />

      <Pressable style={styles.saveBtn} onPress={handleSave} disabled={mutation.isPending}>
        {mutation.isPending ? (
          <ActivityIndicator color="#fff" />
        ) : (
          <Text style={styles.saveBtnText}>Create Character</Text>
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
  saveBtn: {
    backgroundColor: '#0AB463',
    borderRadius: 10,
    paddingVertical: 14,
    alignItems: 'center',
    marginTop: 8,
  },
  saveBtnText: { color: '#fff', fontWeight: '600', fontSize: 16 },
});
