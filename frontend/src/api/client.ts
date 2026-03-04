import axios from 'axios';
import { Platform } from 'react-native';

/**
 * Base URL for the Laravel API.
 * - iOS simulator: localhost resolves to the host machine
 * - Android emulator: 10.0.2.2 maps to the host machine's localhost
 * - Physical device: set EXPO_PUBLIC_API_URL in .env to your LAN IP
 *   e.g. http://192.168.1.x:8080/api
 */
const getBaseUrl = (): string => {
  if (process.env.EXPO_PUBLIC_API_URL) {
    return process.env.EXPO_PUBLIC_API_URL;
  }
  // Android emulator cannot use localhost — it refers to the emulator itself
  // Port 8080 matches BACKEND_PORT in .env (mapped from container port 8000)
  const host = Platform.OS === 'android' ? '10.0.2.2' : 'localhost';
  return `http://${host}:8080/api`;
};

export const apiClient = axios.create({
  baseURL: getBaseUrl(),
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  timeout: 10_000,
});
