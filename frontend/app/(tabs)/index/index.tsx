import { Stack, useRouter } from "expo-router";
import { SymbolView } from "expo-symbols";
import { useCallback } from "react";
import {
  ActivityIndicator,
  FlatList,
  Pressable,
  StyleSheet,
  Text,
} from "react-native";
import { useFocusEffect } from "@react-navigation/native";

import { usePeopleInfinite } from "@/src/api/people";
import { PersonCard } from "@/src/components/PersonCard";
import { useSearchMode } from "@/src/context/SearchModeContext";

export default function CharactersScreen() {
  const router = useRouter();
  const { setMode } = useSearchMode();

  const { data, isLoading, isError, fetchNextPage, hasNextPage, isFetchingNextPage } =
    usePeopleInfinite();

  useFocusEffect(
    useCallback(() => {
      setMode("people");
    }, [setMode])
  );

  const people = data?.pages.flatMap((p) => p.data) ?? [];

  return (
    <>
      <Stack.Header
        largeStyle={{ backgroundColor: "transparent" }}
        style={{ backgroundColor: "transparent" }}
      />
      <Stack.Screen
        options={{
          title: "Characters",
          headerLargeTitleEnabled: true,
          headerRight: () => (
            <Pressable
              onPress={() => router.push("/people/create")}
              hitSlop={8}
            >
              <SymbolView name="plus" size={22} tintColor="#0AB463" />
            </Pressable>
          ),
        }}
      />

      {isLoading && <ActivityIndicator style={styles.loader} size="large" />}
      {isError && <Text style={styles.error}>Failed to load characters.</Text>}

      <FlatList
        data={people}
        keyExtractor={(item) => String(item.id)}
        renderItem={({ item }) => <PersonCard person={item} />}
        contentInsetAdjustmentBehavior="automatic"
        onEndReachedThreshold={0.3}
        onEndReached={() => {
          if (hasNextPage && !isFetchingNextPage) fetchNextPage();
        }}
        ListEmptyComponent={
          !isLoading ? (
            <Text style={styles.empty}>No characters found.</Text>
          ) : null
        }
        ListFooterComponent={
          isFetchingNextPage ? (
            <ActivityIndicator style={styles.footerLoader} />
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
  footerLoader: { paddingVertical: 16 },
});
