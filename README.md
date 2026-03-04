# Star Wars Full-Stack Assessment

A full-stack Star Wars searchable interface with a Laravel API backend and React Native (Expo) mobile frontend.

**Features:** CRUD for People & Films, full-text search, detail views with related resources, search analytics updated every 5 minutes via background queue.

---

## Architecture

| Layer | Technology | Notes |
|-------|-----------|-------|
| Backend API | Laravel 12 (PHP 8.4) | RESTful, runs in Docker |
| Database | MySQL 8.4 | Seeded from SWAPI |
| Cache & Queue | Redis 7 | Stats caching + job queue |
| Frontend | React Native (Expo Router) | Runs on host via Metro |

The frontend is **not** in Docker — it runs via `npx expo start` on your host machine.

---

## Quick Start

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) (v20.10+) and [Docker Compose](https://docs.docker.com/compose/install/) (v2.0+)
- [Node.js](https://nodejs.org/) 18+ (for the Expo frontend)
- [Expo Go](https://expo.dev/go) app on your phone, OR a local iOS/Android simulator

### Step 1 — Start the backend

```bash
cp .env.example .env
docker compose up --build -d
```

Wait for all containers to be healthy (~30s on first run).

### Step 2 — Run migrations and generate app key

```bash
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate
```

### Step 3 — Seed from SWAPI

This fetches ~82 characters and 6 films from swapi.tech and stores them locally. Internet access required; takes ~30 seconds.

```bash
docker compose exec backend php artisan db:seed
```

After seeding the app works fully offline — SWAPI is never called at runtime.

### Step 4 — Start the Expo app

```bash
cd frontend
npm install       # first time only
npx expo start
```

- **iOS simulator:** press `i`
- **Android emulator:** press `a`
- **Physical device:** scan the QR code with Expo Go

> **Android users:** The API URL automatically switches to `http://10.0.2.2:8080/api` for the Android emulator. On a physical device, set `EXPO_PUBLIC_API_URL=http://<your-LAN-IP>:8080/api` in `frontend/.env`.

---

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/health` | Health check |
| GET | `/api/people?search=&page=` | List / search characters |
| GET | `/api/people/{id}` | Character detail with films |
| POST | `/api/people` | Create character |
| PUT | `/api/people/{id}` | Update character |
| DELETE | `/api/people/{id}` | Delete character |
| GET | `/api/films?search=&page=` | List / search films |
| GET | `/api/films/{id}` | Film detail with characters |
| POST | `/api/films` | Create film |
| PUT | `/api/films/{id}` | Update film |
| DELETE | `/api/films/{id}` | Delete film |
| GET | `/api/statistics` | Search analytics (cached) |

---

## Backend Commands

```bash
# Migrations
docker compose exec backend php artisan migrate
docker compose exec backend php artisan migrate:fresh --seed

# Seeding
docker compose exec backend php artisan db:seed

# Tests
docker compose exec backend php artisan test

# Manually trigger statistics update (for testing)
docker compose exec backend php artisan tinker
# >>> App\Jobs\UpdateStatisticsJob::dispatch()

# Queue worker (already running in 'queue' container)
docker compose exec backend php artisan queue:work --once

# Code style
docker compose exec backend ./vendor/bin/pint

# Database access
docker compose exec mysql mysql -u starwars -psecret starwars
docker compose exec redis redis-cli
```

---

## Project Structure

```
engineer-assessment/
├── backend/                      # Laravel 12 API
│   ├── app/
│   │   ├── Http/Controllers/Api/ # PersonController, FilmController, StatisticsController
│   │   ├── Models/               # Person, Film, SearchLog
│   │   ├── Services/             # SwapiService, StatisticsService
│   │   └── Jobs/                 # UpdateStatisticsJob (runs every 5 min)
│   ├── database/
│   │   ├── migrations/           # people, films, film_person, search_logs
│   │   ├── seeders/              # SwapiSeeder
│   │   └── factories/            # PersonFactory, FilmFactory, SearchLogFactory
│   ├── routes/
│   │   ├── api.php               # REST routes
│   │   └── console.php           # Scheduler registration
│   └── tests/                    # Feature + Unit tests
├── frontend/                     # React Native (Expo Router)
│   ├── app/
│   │   ├── _layout.tsx           # Root layout (QueryClient provider)
│   │   ├── (tabs)/               # Characters | Films | Statistics tabs
│   │   ├── people/               # [id].tsx, create.tsx, [id]/edit.tsx
│   │   └── films/                # [id].tsx, create.tsx, [id]/edit.tsx
│   └── src/
│       ├── api/                  # React Query hooks (people, films, statistics)
│       ├── types/                # TypeScript interfaces
│       └── components/           # SearchBar, PersonCard, FilmCard, StatCard
├── docker-compose.yml            # Backend services only (frontend runs on host)
├── DECISIONS.md                  # Architectural decision records
├── SCALING.md                    # Scaling considerations
└── .env.example                  # Environment template
```

---

## Statistics

The `GET /api/statistics` endpoint returns:

```json
{
  "data": {
    "average_duration_ms": 45.3,
    "most_popular_hour": 14,
    "top_searches": [
      { "query": "Luke", "count": 12, "percentage": 40.0 },
      ...
    ]
  }
}
```

Stats are computed by `UpdateStatisticsJob` every 5 minutes via the scheduler and cached in Redis for 6 minutes. The `search_logs` table records every search request (query, resource type, duration, timestamp).

---

## Assumptions & Limitations

- **Dataset is a SWAPI snapshot.** The seeder fetches from swapi.tech at setup time. Data is not auto-synced with SWAPI after seeding.
- **No authentication.** All API endpoints are public. Adding auth (Sanctum or Passport) would be the next step for a production app.
- **Statistics are eventually consistent** (up to 5 minutes stale by design).
- **Android emulator** requires `10.0.2.2` instead of `localhost` — handled automatically in `src/api/client.ts`.

---

## Key Libraries Added

**Backend:**
- `guzzlehttp/guzzle` — HTTP client for SWAPI fetching (parallel requests via `Http::pool()`)
- `predis/predis` / Laravel Redis — Statistics caching

**Frontend:**
- `@tanstack/react-query` — Server state management and caching
- `axios` — HTTP client with typed interceptors
- `expo-router` — File-based routing (ships with default Expo template)

---

## Troubleshooting

**`docker compose exec backend` fails:** Make sure containers are running — `docker compose ps`

**Seeder times out:** swapi.tech may be slow. Run `docker compose exec backend php artisan db:seed` again — `updateOrCreate` is idempotent.

**Port conflicts:** Change ports in `.env`:
```env
BACKEND_PORT=8081
DB_EXTERNAL_PORT=3308
```

**Frontend can't reach API on physical device:** Set `EXPO_PUBLIC_API_URL=http://192.168.x.x:8080/api` in `frontend/.env` (use your machine's LAN IP).

**Statistics always show zeroes:** Make sure you've performed some searches first, then either wait 5 minutes for the scheduler, or dispatch the job manually via Tinker.

<video src="https://github.com/user-attachments/assets/5fc4101f-7060-4636-abd1-85d59319ee13" width="250" />
