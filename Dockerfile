# Use PHP 8.2 FPM base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libonig-dev libxml2-dev curl \
    libzip-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath gd xml zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first for caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the app
COPY . .

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Run migrations safely (if database is ready)
# Add a small sleep to give DB time to start
RUN sleep 10 || true
RUN php artisan migrate --force || true
RUN php artisan config:cache || true
RUN php artisan route:cache || true

# Expose port Laravel will run on
EXPOSE 10000

# Start Laravel using PHP built-in server
CMD php -S 0.0.0.0:10000 -t public
