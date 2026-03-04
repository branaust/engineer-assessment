# Architectural Decisions

## Decision 1: SWAPI Data Independence — Seed Once, Never Fetch at Runtime

**Context:**
The assignment required the app to work even if SWAPI is offline. SWAPI is an external third-party API with no uptime guarantee and known rate limits.

**Options Considered:**
1. **Seed + serve from local DB** — Fetch all data once at seeding time, store in MySQL, serve all API requests from the database.
   - Pros: No runtime SWAPI dependency, fast queries, indexable, supports custom CRUD
   - Cons: Data is a snapshot in time (no live sync)
2. **Proxy SWAPI at runtime** — Forward all frontend requests to SWAPI, cache responses in Redis.
   - Pros: Always current data
   - Cons: App breaks when SWAPI is down, rate limit exposure, no offline support, no CRUD possible on immutable external data

**Decision:**
Chose option 1. `SwapiSeeder` fetches all people (paginated, 82 people across 9 pages) and films (6 films, single page) from swapi.dev at setup time, stores them in MySQL via `updateOrCreate` (idempotent), and seeds the `film_person` pivot table. The app never calls SWAPI at runtime. swapi.dev was chosen over swapi.tech because swapi.dev returns full character properties directly in the paginated list response (eliminating the need for 82 individual HTTP fetches), is more reliable, and has better uptime.

**Consequences:**
Dataset is static until re-seeded. This is acceptable for a Star Wars universe that stopped expanding in 1983. The `swapi_url` column provides an audit trail back to the source data.

---

## Decision 2: Statistics Caching via Redis + Queue Job

**Context:**
The statistics endpoint (`GET /api/statistics`) requires three aggregation queries on `search_logs`: average duration, most popular hour, and top 5 queries. Running these on every request would be expensive as the table grows. The spec required stats to update every 5 minutes.

**Options Considered:**
1. **Queue job + Redis cache** — `UpdateStatisticsJob` runs every 5 minutes via the scheduler, writes results to Redis with a 6-minute TTL. Controller reads from cache, falls back to live calculation on miss.
   - Pros: O(1) read on every request, background calculation doesn't block the response, fault-tolerant (fallback exists)
   - Cons: Stats are up to 5 minutes stale
2. **Calculate live on every request** — No caching.
   - Pros: Always fresh
   - Cons: Three GROUP BY queries per GET at potentially high frequency; expensive at scale
3. **Scheduled command without a queue** — Use Laravel scheduler to run a command directly.
   - Pros: Simpler
   - Cons: Ties up the scheduler process, no retry logic, less observable

**Decision:**
Queue job (`UpdateStatisticsJob implements ShouldQueue`) dispatched every 5 minutes by the scheduler. Cache TTL is 6 minutes (slightly longer than the job interval) to prevent a stale window if a job run is delayed by a few seconds.

**Consequences:**
Stats are eventually consistent. The 6-minute TTL means there is always a valid cached value. Redis is already in the stack for sessions and queues, so no new infrastructure is required.

---

## Decision 3: Search Logging at Controller Level

**Context:**
The statistics system needs to log searches with the query string, resource type, result count, and duration in milliseconds. The question was where to intercept requests to create `SearchLog` records.

**Options Considered:**
1. **Controller-level logging** — Inside `PersonController@index` and `FilmController@index`, create a `SearchLog` record only when the `?search=` query parameter is present.
   - Pros: Precise control over what gets logged; access to result count and elapsed time; easy to reason about
   - Cons: Slight code duplication between the two controllers
2. **HTTP Middleware** — Global middleware that intercepts every request matching `/api/people` or `/api/films`.
   - Pros: Centralized
   - Cons: Middleware fires on all methods (GET/POST/PUT/DELETE) and all endpoints, requiring complex filtering; doesn't have access to query results or elapsed time; creates noisy logs

**Decision:**
Controller-level logging using `microtime(true)` at the start of the `index()` method. A `SearchLog` is created only when `$request->has('search')` is true. Empty string searches (browsing all) are logged with `query = null`.

**Consequences:**
Analytics data is meaningful — only intentional searches are tracked, not browse-all requests or mutations. The `null` query rows are excluded from the top-searches calculation in `StatisticsService::getTopSearches()`.

---

## Decision 4: Offset Pagination over Cursor Pagination

**Context:**
Both `GET /api/people` and `GET /api/films` need pagination. Laravel supports both offset-based (`paginate()`) and cursor-based (`cursorPaginate()`) pagination.

**Options Considered:**
1. **Offset pagination** — Standard `paginate(15)`. Returns `data`, `links`, and `meta` including `total`, `current_page`, `last_page`.
   - Pros: Allows jumping to any page, total count is known, simple frontend implementation
   - Cons: Can drift on inserts at scale
2. **Cursor pagination** — `cursorPaginate(15)`. Stable under concurrent writes.
   - Pros: O(1) seek at scale, stable cursor
   - Cons: No total count, no jump-to-page, more complex frontend state

**Decision:**
Offset pagination. The dataset is small (82 people, 6 films) and grows only through manual CRUD. The frontend benefits from knowing the total count and page numbers for rendering pagination controls. Cursor pagination would add complexity with no practical benefit at this scale.

**Consequences:**
If the dataset eventually reached millions of rows, cursor pagination would be preferable. This can be migrated without breaking the API contract by adding a `cursor` parameter alongside `page`.

---

## Decision 5: React Native (Expo Router) for the Frontend

**Context:**
The starter repo provided a React Router v7 web frontend, but I was told in the email to specifically build this app in React Native.

**Options Considered:**
1. **React Native with Expo Router** — File-based routing, native iOS/Android app.
   - Pros: Modern file-based routing (similar to Next.js), works on iOS and Android, familiar React patterns
   - Cons: Not a web app; requires Expo Go or a simulator to run; no Docker integration for the frontend
2. **Keep the web React Router v7 frontend** — Build on the existing scaffold.
   - Pros: Matches the Figma file assumptions, stays in Docker, web URL-based routing
   - Cons: Does not showcase mobile development

**Decision:**
Replaced `frontend/` with an Expo Router app. The backend Docker services (MySQL, Redis, queue, scheduler) are unchanged. The Expo app runs on the host machine via `npx expo start` (Metro bundler). The `frontend` Docker service was removed from `docker-compose.yml`.

**Consequences:**
Running the frontend requires Node + Expo CLI installed on the host, plus either the Expo Go app on a physical device or a local simulator. Android emulator requires using `10.0.2.2` instead of `localhost` to reach the Docker backend. This is documented in the README.
