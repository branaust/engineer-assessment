import { Stack, useRouter } from "expo-router";
import { SymbolView } from "expo-symbols";
import { useState } from "react";
import {
  ActivityIndicator,
  FlatList,
  Pressable,
  StyleSheet,
  Text,
  View,
} from "react-native";
import { useFocusEffect } from "@react-navigation/native";
import { useCallback } from "react";

import { useFilms } from "@/src/api/films";
import { FilmCard } from "@/src/components/FilmCard";
import { useSearchMode } from "@/src/context/SearchModeContext";

export default function FilmsScreen() {
  const router = useRouter();
  const [page, setPage] = useState(1);
  const { setMode } = useSearchMode();

  const { data, isLoading, isError } = useFilms(undefined, page);

  useFocusEffect(
    useCallback(() => {
      setMode("films");
    }, [setMode])
  );

  return (
    <>
      <Stack.Header
        largeStyle={{ backgroundColor: "transparent" }}
        style={{ backgroundColor: "transparent" }}
      />
      <Stack.Screen
        options={{
          title: "Films",
          headerLargeTitleEnabled: true,
          headerRight: () => (
            <Pressable
              onPress={() => router.push("/films/create")}
              hitSlop={8}
            >
              <SymbolView name="plus" size={22} tintColor="#0AB463" />
            </Pressable>
          ),
        }}
      />

      {isLoading && <ActivityIndicator style={styles.loader} size="large" />}
      {isError && <Text style={styles.error}>Failed to load films.</Text>}

      <FlatList
        data={data?.data}
        keyExtractor={(item) => String(item.id)}
        renderItem={({ item }) => <FilmCard film={item} />}
        contentInsetAdjustmentBehavior="automatic"
        ListEmptyComponent={
          !isLoading ? (
            <Text style={styles.empty}>No films found.</Text>
          ) : null
        }
        ListFooterComponent={
          data && data.meta.last_page > 1 ? (
            <View style={styles.pagination}>
              <Pressable
                disabled={page === 1}
                onPress={() => setPage((p) => p - 1)}
                style={[styles.pageBtn, page === 1 && styles.pageBtnDisabled]}
              >
                <Text style={styles.pageBtnText}>← Prev</Text>
              </Pressable>
              <Text style={styles.pageInfo}>
                {data.meta.current_page} / {data.meta.last_page}
              </Text>
              <Pressable
                disabled={page === data.meta.last_page}
                onPress={() => setPage((p) => p + 1)}
                style={[
                  styles.pageBtn,
                  page === data.meta.last_page && styles.pageBtnDisabled,
                ]}
              >
                <Text style={styles.pageBtnText}>Next →</Text>
              </Pressable>
            </View>
          ) : null
        }
      />
    </>
  );
}

const styles = StyleSheet.create({
  loader: { marginTop: 40 },
  error: { textAlign: "center", marginTop: 40, color: "#e74c3c" },
  empty: { textAlign: "center", marginTop: 60, color: "#aaa", fontSize: 16 },
  pagination: {
    flexDirection: "row",
    justifyContent: "center",
    alignItems: "center",
    paddingVertical: 16,
    gap: 20,
  },
  pageBtn: {
    paddingHorizontal: 14,
    paddingVertical: 8,
    backgroundColor: "#0AB463",
    borderRadius: 8,
  },
  pageBtnDisabled: { backgroundColor: "#ccc" },
  pageBtnText: { color: "#fff", fontWeight: "600" },
  pageInfo: { color: "#555", fontSize: 14 },
});
