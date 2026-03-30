FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql pdo_sqlite bcmath gd zip intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install dependencies (only if vendor doesn't exist or for clean build)
# RUN composer install --no-interaction --no-plugins --no-scripts

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
