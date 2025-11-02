# Stage 1: Frontend Asset Builder
FROM node:18-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Backend Dependency Builder
FROM composer:2.5 AS backend
WORKDIR /app
COPY --from=frontend /app .
RUN composer install --no-dev --optimize-autoloader

# Stage 3: Final Production Image
FROM php:8.2-apache
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd dom fileinfo zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-enable opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure Apache
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copy application code and compiled assets
COPY --from=backend /app .

# Set correct permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage link and run optimizations
RUN php artisan storage:link \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache

# Expose port 80 and start Apache
EXPOSE 80
CMD ["apache2-foreground"]