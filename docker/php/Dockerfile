# Stage 1: Build
FROM php:8.2-fpm AS builder

# Install build dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    build-essential \
    pkg-config \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install intl pdo pdo_mysql zip

# Install Redis
RUN pecl install redis && docker-php-ext-enable redis

# Copy Composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first (for caching)
COPY app/composer.json app/composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy app code
COPY app/ .

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Stage 2: Final image
FROM php:8.2-fpm

# Copy PHP extensions and app from builder
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /var/www/html /var/www/html

WORKDIR /var/www/html

# Set permissions again
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
