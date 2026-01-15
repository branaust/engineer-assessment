# Makefile for Star Wars Engineer Assessment
# Provides convenient shortcuts for common Docker operations

.PHONY: help up up-d down build logs shell migrate seed test fresh key clean

# Default target
help:
	@echo "Star Wars Engineer Assessment - Available Commands"
	@echo ""
	@echo "  make up        - Start all services"
	@echo "  make down      - Stop all services"
	@echo "  make build     - Rebuild and start services"
	@echo "  make logs      - Follow all service logs"
	@echo "  make shell     - Open shell in backend container"
	@echo "  make migrate   - Run database migrations"
	@echo "  make seed      - Seed the database"
	@echo "  make test      - Run backend tests"
	@echo "  make fresh     - Fresh install (migrate + seed)"
	@echo "  make key       - Generate Laravel APP_KEY"
	@echo "  make clean     - Stop services and remove volumes"
	@echo ""

# Start all services
up:
	docker compose up

# Start in background
up-d:
	docker compose up -d

# Stop all services
down:
	docker compose down

# Rebuild and start
build:
	docker compose up --build

# Follow logs
logs:
	docker compose logs -f

# Backend shell
shell:
	docker compose exec backend sh

# Run migrations
migrate:
	docker compose exec backend php artisan migrate

# Seed database
seed:
	docker compose exec backend php artisan db:seed

# Run tests
test:
	docker compose exec backend php artisan test

# Fresh install
fresh:
	docker compose exec backend php artisan migrate:fresh --seed

# Generate app key
key:
	docker compose exec backend php artisan key:generate

# Clean everything including volumes
clean:
	docker compose down -v --remove-orphans
