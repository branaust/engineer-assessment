# Star Wars Engineer Assessment

A full-stack application for managing Star Wars characters and films data using Laravel (backend) and React Router (frontend).

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

5. **In a new terminal, run database migrations and seed data:**
   ```bash
   # Run migrations
   docker compose exec backend php artisan migrate

   # Generate app key (if not already set)
   docker compose exec backend php artisan key:generate

   # Seed the database with SWAPI data
   docker compose exec backend php artisan db:seed
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

# Start in background (detached mode)
docker compose up -d

# Stop all services
docker compose down

# View logs
docker compose logs -f

# View specific service logs
docker compose logs -f backend
docker compose logs -f frontend

# Run artisan commands
docker compose exec backend php artisan <command>

# Run tests
docker compose exec backend php artisan test

# Access MySQL CLI
docker compose exec mysql mysql -u starwars -psecret starwars

# Access Redis CLI
docker compose exec redis redis-cli

# Rebuild after Dockerfile changes
docker compose up --build

# Clean up volumes (WARNING: deletes database data)
docker compose down -v
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

## Documentation

- **Implementation Guide:** See [backend/GETTING_STARTED.md](./backend/GETTING_STARTED.md) for what to implement
- **Requirements Checklist:** See [backend/ASSESSMENT_REQUIREMENTS.md](./backend/ASSESSMENT_REQUIREMENTS.md)
- **Frontend:** See [frontend/README.md](./frontend/README.md)

---

## Local Development (Without Docker)

If you prefer running services locally without Docker:

### Frontend Quick Setup

```bash
cd frontend

# Enable Corepack for Yarn 4
corepack enable

# Install dependencies
yarn

# Start development server
yarn dev
```

### Backend Quick Setup

Requires PHP 8.2+, Composer, MySQL, and Redis.

```bash
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start server
php artisan serve
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
└── .env.example             # Environment Template
```

---

## API Endpoints

| Method | Endpoint              | Description                    |
|--------|----------------------|--------------------------------|
| GET    | /api/health          | Health check                   |
| GET    | /api/people          | List people (with search)      |
| GET    | /api/people/{id}     | Get person with films          |
| POST   | /api/people          | Create person                  |
| PUT    | /api/people/{id}     | Update person                  |
| DELETE | /api/people/{id}     | Delete person                  |
| GET    | /api/films           | List films (with search)       |
| GET    | /api/films/{id}      | Get film with people           |
| POST   | /api/films           | Create film                    |
| PUT    | /api/films/{id}      | Update film                    |
| DELETE | /api/films/{id}      | Delete film                    |
| GET    | /api/statistics      | Search statistics              |

---

## Assessment Requirements

See [backend/ASSESSMENT_REQUIREMENTS.md](./backend/ASSESSMENT_REQUIREMENTS.md) for the full requirements.

---

## License

This project is for assessment purposes only.
