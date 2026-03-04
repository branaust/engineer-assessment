import React from 'react';
import {
  ActivityIndicator,
  FlatList,
  ScrollView,
  StyleSheet,
  Text,
  View,
} from 'react-native';

import { useStatistics } from '@/src/api/statistics';
import { StatCard } from '@/src/components/StatCard';

export default function StatisticsScreen() {
  const { data, isLoading, isError, dataUpdatedAt } = useStatistics();
  const stats = data?.data;

  const formatHour = (hour: number | null) => {
    if (hour === null) return '—';
    const period = hour < 12 ? 'AM' : 'PM';
    const h = hour % 12 || 12;
    return `${h}:00 ${period}`;
  };

  const lastUpdated = dataUpdatedAt
    ? new Date(dataUpdatedAt).toLocaleTimeString()
    : null;

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Text style={styles.heading}>Search Analytics</Text>
      {lastUpdated && (
        <Text style={styles.updated}>Last updated at {lastUpdated} · refreshes every 5 min</Text>
      )}

      {isLoading && <ActivityIndicator style={styles.loader} size="large" />}
      {isError && <Text style={styles.error}>Failed to load statistics.</Text>}

      {stats && (
        <>
          <StatCard
            label="Avg Response Time"
            value={`${stats.average_duration_ms.toFixed(1)} ms`}
            subtitle="Average search request duration"
          />
          <StatCard
            label="Peak Search Hour"
            value={formatHour(stats.most_popular_hour)}
            subtitle={stats.most_popular_hour !== null ? 'Most searches occur at this hour' : 'No data yet'}
          />

          <Text style={styles.sectionTitle}>Top Search Queries</Text>
          {stats.top_searches.length === 0 ? (
            <Text style={styles.empty}>No searches logged yet.</Text>
          ) : (
            <View style={styles.topSearchesCard}>
              {stats.top_searches.map((item, index) => (
                <View
                  key={item.query}
                  style={[styles.searchRow, index > 0 && styles.searchRowBorder]}
                >
                  <View style={styles.searchRank}>
                    <Text style={styles.rankText}>{index + 1}</Text>
                  </View>
                  <Text style={styles.queryText} numberOfLines={1}>
                    {item.query}
                  </Text>
                  <Text style={styles.countText}>{item.count}×</Text>
                  <Text style={styles.pctText}>{item.percentage}%</Text>
                </View>
              ))}
            </View>
          )}
        </>
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f9f9f9' },
  content: { padding: 16, paddingBottom: 40 },
  heading: { fontSize: 24, fontWeight: '700', color: '#111', marginBottom: 4 },
  updated: { fontSize: 12, color: '#aaa', marginBottom: 20 },
  loader: { marginTop: 40 },
  error: { textAlign: 'center', marginTop: 40, color: '#e74c3c' },
  sectionTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: '#555',
    textTransform: 'uppercase',
    letterSpacing: 0.5,
    marginTop: 8,
    marginBottom: 10,
  },
  empty: { color: '#aaa', fontSize: 15, textAlign: 'center', marginTop: 20 },
  topSearchesCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    overflow: 'hidden',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.06,
    shadowRadius: 4,
    elevation: 2,
  },
  searchRow: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 12,
    gap: 12,
  },
  searchRowBorder: {
    borderTopWidth: StyleSheet.hairlineWidth,
    borderTopColor: '#e0e0e0',
  },
  searchRank: {
    width: 24,
    height: 24,
    borderRadius: 12,
    backgroundColor: '#0AB463',
    justifyContent: 'center',
    alignItems: 'center',
  },
  rankText: { color: '#fff', fontSize: 12, fontWeight: '700' },
  queryText: { flex: 1, fontSize: 16, color: '#111' },
  countText: { fontSize: 14, color: '#888', minWidth: 32, textAlign: 'right' },
  pctText: {
    fontSize: 14,
    fontWeight: '600',
    color: '#0AB463',
    minWidth: 44,
    textAlign: 'right',
  },
});
