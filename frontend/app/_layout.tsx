import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { DarkTheme, DefaultTheme, ThemeProvider } from '@react-navigation/native';
import { Stack } from 'expo-router';
import { StatusBar } from 'expo-status-bar';
import 'react-native-reanimated';

import { useColorScheme } from '@/hooks/use-color-scheme';

const queryClient = new QueryClient({
  defaultOptions: {
    queries: { staleTime: 30_000, retry: 1 },
  },
});

export const unstable_settings = {
  anchor: '(tabs)',
};

export default function RootLayout() {
  const colorScheme = useColorScheme();

  return (
    <QueryClientProvider client={queryClient}>
      <ThemeProvider value={colorScheme === 'dark' ? DarkTheme : DefaultTheme}>
        <Stack>
          <Stack.Screen name="(tabs)" options={{ headerShown: false }} />
          <Stack.Screen name="people/[id]" options={{ title: 'Character' }} />
          <Stack.Screen name="people/create" options={{ title: 'Add Character', presentation: 'modal' }} />
          <Stack.Screen name="people/[id]/edit" options={{ title: 'Edit Character', presentation: 'modal' }} />
          <Stack.Screen name="films/[id]" options={{ title: 'Film' }} />
          <Stack.Screen name="films/create" options={{ title: 'Add Film', presentation: 'modal' }} />
          <Stack.Screen name="films/[id]/edit" options={{ title: 'Edit Film', presentation: 'modal' }} />
        </Stack>
        <StatusBar style="auto" />
      </ThemeProvider>
    </QueryClientProvider>
  );
}
