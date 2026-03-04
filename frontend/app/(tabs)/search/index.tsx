import { Stack } from "expo-router";
import { useEffect, useState } from "react";
import { ActivityIndicator, FlatList, StyleSheet, Text, View } from "react-native";

import { usePeople } from "@/src/api/people";
import { useFilms } from "@/src/api/films";
import { PersonCard } from "@/src/components/PersonCard";
import { FilmCard } from "@/src/components/FilmCard";
import { useSearchMode } from "@/src/context/SearchModeContext";

export default function SearchScreen() {
  const { mode } = useSearchMode();
  const [search, setSearch] = useState("");
  const [debouncedSearch, setDebouncedSearch] = useState("");

  useEffect(() => {
    const timer = setTimeout(() => setDebouncedSearch(search), 400);
    return () => clearTimeout(timer);
  }, [search]);

  const isFilms = mode === "films";
  const placeholder = isFilms ? "Search films..." : "Search characters...";

  const peopleQuery = usePeople(!isFilms ? debouncedSearch || undefined : undefined, 1);
  const filmsQuery = useFilms(isFilms ? debouncedSearch || undefined : undefined, 1);

  const { isLoading, isError } = isFilms ? filmsQuery : peopleQuery;

  return (
    <>
      <Stack.Screen
        options={{
          title: "Search",
          headerSearchBarOptions: {
            placeholder,
            onChangeText: (e) => setSearch(e.nativeEvent.text),
            autoFocus: true,
          },
        }}
      />

      <View style={styles.container}>
        {isLoading && <ActivityIndicator style={styles.loader} size="large" />}
        {isError && <Text style={styles.error}>Failed to load results.</Text>}

        {isFilms ? (
          <FlatList
            data={filmsQuery.data?.data}
            keyExtractor={(item) => String(item.id)}
            renderItem={({ item }) => <FilmCard film={item} />}
            contentInsetAdjustmentBehavior="automatic"
            ListEmptyComponent={
              !isLoading ? (
                <Text style={styles.empty}>
                  {debouncedSearch ? "No films found." : "Start typing to search films."}
                </Text>
              ) : null
            }
          />
        ) : (
          <FlatList
            data={peopleQuery.data?.data}
            keyExtractor={(item) => String(item.id)}
            renderItem={({ item }) => <PersonCard person={item} />}
            contentInsetAdjustmentBehavior="automatic"
            ListEmptyComponent={
              !isLoading ? (
                <Text style={styles.empty}>
                  {debouncedSearch ? "No characters found." : "Start typing to search characters."}
                </Text>
              ) : null
            }
          />
        )}
      </View>
    </>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1 },
  loader: { marginTop: 40 },
  error: { textAlign: "center", marginTop: 40, color: "#e74c3c" },
  empty: { textAlign: "center", marginTop: 60, color: "#aaa", fontSize: 16 },
});
