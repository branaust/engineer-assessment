# Star Wars Engineer Assessment

A full-stack application starter for managing Star Wars characters and films data using Laravel (backend) and React Router (frontend).

## Quick Start with Docker

The easiest way to get started is using Docker. This will spin up all services with a single command.

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) (v20.10+)
- [Docker Compose](https://docs.docker.com/compose/install/) (v2.0+)

### Getting Started

1. **Clone the repository and navigate to the project:**
   ```bash
   git clone <repository-url>
   cd engineer-assessment
   ```

2. **Copy the environment file:**
   ```bash
   # On Linux/Mac
   cp .env.example .env

   # On Windows (PowerShell)
   copy .env.example .env
   ```

3. **Start all services:**
   ```bash
   docker compose up --build
   ```

4. **Wait for services to be healthy** (first run takes a few minutes to build).

5. **In a new terminal, run database migrations and generate app key:**
   ```bash
   docker compose exec backend php artisan migrate
   docker compose exec backend php artisan key:generate
   ```

6. **Access the application:**
   - **Frontend:** http://localhost:5173
   - **Backend API:** http://localhost:8080/api
   - **GraphiQL:** http://localhost:8080/graphiql

### Available Services

| Service    | URL                              | Description                     |
|------------|----------------------------------|---------------------------------|
| Frontend   | http://localhost:5173            | React Router application        |
| Backend    | http://localhost:8080            | Laravel API                     |
| MySQL      | localhost:3307                   | Database (user: starwars)       |
| Redis      | localhost:6379                   | Cache & Queue                   |

### Useful Commands

```bash
# Start all services
docker compose up

# Start in background
docker compose up -d

# Stop all services
docker compose down

# View logs
docker compose logs -f

# Rebuild after changes
docker compose up --build

# Clean up (removes volumes/data)
docker compose down -v
```

**Backend commands:**
```bash
docker compose exec backend php artisan migrate
docker compose exec backend php artisan db:seed
docker compose exec backend php artisan migrate:fresh --seed
docker compose exec backend php artisan test
docker compose exec backend ./vendor/bin/pint
```

**Frontend commands:**
```bash
docker compose exec frontend yarn typecheck
docker compose exec frontend yarn build
```

**Database access:**
```bash
docker compose exec mysql mysql -u starwars -psecret starwars
docker compose exec redis redis-cli
```

### IDE Setup (Optional)

For better IDE support (autocompletion, linting, type hints), you can install dependencies locally while still using Docker for execution:

```bash
# Install backend dependencies locally for IDE
cd backend
composer install

# Install frontend dependencies locally for IDE  
cd frontend
corepack enable
yarn install
```

The Docker containers use their own isolated dependencies, so local installs won't affect runtime. When adding new packages, install in Docker first then sync locally:

```bash
# Add package in Docker
docker compose exec backend composer require some/package

# Sync to local for IDE support
cd backend && composer install
```

### Troubleshooting

**Port conflicts:**
The default ports (8080, 5173, 3307) are chosen to avoid common conflicts. If you still have issues, edit `.env`:
```env
BACKEND_PORT=8081
FRONTEND_PORT=5174
DB_EXTERNAL_PORT=3308
```

**Database connection issues:**
Wait for MySQL to be healthy before running migrations:
```bash
docker compose exec backend php artisan migrate --force
```

**Fresh start:**
```bash
docker compose down -v
docker compose up --build
```

---

## Project Structure

```
engineer-assessment/
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/Api/   # API Controllers
│   │   ├── Models/                 # Eloquent Models
│   │   ├── Services/               # Business Logic
│   │   └── Jobs/                   # Background Jobs
│   ├── database/
│   │   ├── migrations/             # Database Schema
│   │   ├── seeders/                # Data Seeders
│   │   └── factories/              # Model Factories
│   ├── routes/api.php              # API Routes
│   └── tests/                      # PHPUnit Tests
├── frontend/                # React Router Application
│   ├── app/
│   │   ├── routes/                 # Page Components
│   │   └── root.tsx                # Root Layout
│   └── vite.config.ts              # Vite Configuration
├── docker-compose.yml       # Docker Orchestration
├── DECISIONS.md.example     # Template for architectural decisions
├── SCALING.md.example       # Template for scaling questions
└── .env.example             # Environment Template
```

---

## Documentation Templates

When you complete the assessment, create these files from the provided templates:

| Template | Create As | Purpose |
|----------|-----------|---------|
| `DECISIONS.md.example` | `DECISIONS.md` | Document your architectural decisions |
| `SCALING.md.example` | `SCALING.md` | Answer scaling questions |

---

## Local Development (Without Docker)

If you prefer running services locally without Docker:

### Frontend

```bash
cd frontend
corepack enable
yarn install
yarn dev
```

### Backend

Requires PHP 8.4+, Composer, MySQL, and Redis.

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## License

This project is for assessment purposes only.
