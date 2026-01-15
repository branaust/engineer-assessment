# syntax=docker/dockerfile:1

FROM php:8.4-fpm AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# -------------------------------------------
# Development stage
# -------------------------------------------
FROM base AS development

# Install Node.js for asset building (if needed)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application
COPY . .

# Generate autoloader and run scripts
RUN composer dump-autoload --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port for PHP built-in server (development)
EXPOSE 8000

# Default command for development
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

# -------------------------------------------
# Production stage
# -------------------------------------------
FROM base AS production

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev)
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Copy the rest of the application
COPY . .

# Generate autoloader
RUN composer dump-autoload --optimize --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Cache config and routes for production
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
