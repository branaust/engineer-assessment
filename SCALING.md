# Scaling Considerations

## 1. How would you handle 100k searches/day?

**Current state:**
Each search request hits MySQL with a `LIKE` query on an indexed `name`/`title` column, creates a `SearchLog` row synchronously, and returns a paginated result. At 100k searches/day (~1.2 req/s average, with spikes potentially 5–10×), the synchronous `SearchLog::create()` call inside the request cycle becomes a bottleneck — it adds a DB write to every read path.

**Scaling approach:**
First, offload search logging to a queued job (`dispatch(new LogSearchJob(...))`) so the HTTP response is not blocked by the write. Second, add a read replica for MySQL: all `SELECT` queries (people, films, statistics) route to the replica while writes (CRUD, log inserts) hit the primary. Third, add Redis query caching for popular search terms with a short TTL (e.g., 30 seconds), since a query for "Luke" will return the same 15 results for thousands of users. At very high volume, consider full-text search (MySQL `FULLTEXT` index or Elasticsearch) to replace the `LIKE "%term%"` pattern, which cannot use a standard B-tree index.

---

## 2. What happens when the dataset grows 50x?

**Impact analysis:**
50× growth takes the dataset from ~82 people and 6 films (SWAPI-bounded) to potentially 4,000+ characters across hundreds of films — realistic if the app is extended to cover the Expanded Universe or user-created content. The `LIKE '%query%'` search degrades from fast (index scan on a small table) to slow (full table scan) because a leading wildcard prevents index use. The `film_person` pivot table grows proportionally. The statistics queries (`GROUP BY HOUR(searched_at)`) on `search_logs` become expensive as that table accumulates millions of rows.

**Solution:**
Replace `LIKE` with MySQL `FULLTEXT` search (`MATCH ... AGAINST`) or migrate to Elasticsearch for sub-millisecond fuzzy search at scale. Partition the `search_logs` table by month so analytics queries only scan recent data. Archive older log partitions to cold storage (S3 + Athena for ad-hoc queries). Add composite indexes on `film_person(person_id, film_id)` to support both directions of the join efficiently. Consider paginating the `search_logs` aggregations themselves if the window grows beyond a month.

---

## 3. What would you monitor in production?

**Key metrics:**
- **API latency** (p50, p95, p99 per endpoint) — alerts if p95 > 500ms
- **MySQL query time** — slow query log threshold at 100ms; alert on queries >1s
- **Queue depth and job lag** — alert if `UpdateStatisticsJob` hasn't run in >10 minutes
- **Redis memory usage and hit ratio** — low hit ratio on `search_statistics` key indicates the job is failing
- **Error rate by endpoint** — 5xx rate >1% triggers immediate alert
- **Search log write rate** — sudden drop may indicate a bug in the logging path; sudden spike may indicate abuse or a crawler

**Alerting:**
Use Datadog or Prometheus + Grafana. Critical alerts (PagerDuty): 5xx spike, queue worker down, DB connection pool exhausted. Warning alerts (Slack): p95 latency creep, Redis memory >80%, stats cache TTL repeatedly expiring without renewal (job failure indicator).

---

## 4. What would you document for the next engineer?

**Critical documentation:**
- **Seeding process**: The database must be seeded from SWAPI before the app is usable. Document that the seeder uses `updateOrCreate` keyed on `swapi_url`, making it safe to re-run. Document that `Http::pool()` is used for parallel page fetching and that swapi.tech returns films under `result` (singular) rather than `results`.
- **Statistics pipeline**: Explain the full flow — search request → `SearchLog::create()` → `UpdateStatisticsJob` every 5 min via scheduler → Redis cache → `StatisticsController` reads from cache. A new engineer must know that statistics will be stale (up to 5 min) by design, and that manually dispatching the job via Tinker (`UpdateStatisticsJob::dispatch()`) is the way to force a refresh in development.
- **Docker architecture**: The frontend (Expo) is intentionally not in Docker. The four backend containers (backend, queue, scheduler, mysql, redis) must all be running for the full system to work. The `queue` container must be running for jobs to process — without it, `UpdateStatisticsJob` will queue but never execute.

**Common pitfalls:**
- **Android emulator networking**: `localhost` in the Expo app resolves to the emulator itself, not the host machine. Use `10.0.2.2:8080` for the API URL when testing on Android. This is configured automatically in `src/api/client.ts` but can be overridden via `EXPO_PUBLIC_API_URL` in `frontend/.env`.
- **SWAPI rate limits during seeding**: swapi.tech may throttle requests if the seeder is run repeatedly in quick succession. The `Http::pool()` implementation sends 8 concurrent requests for people pages — if this causes 429 errors, add `usleep(500000)` between pool batches.
- **Pivot table has no timestamps**: `film_person` uses a composite primary key with no `id` or timestamps column. Do not run `php artisan tinker` and call `$film->people()->attach($id)` with `withTimestamps()` — it will fail. Use `sync()` or `attach()` without timestamp options.
