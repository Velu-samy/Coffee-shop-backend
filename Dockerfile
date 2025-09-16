# Use PHP 8.2 FPM
FROM php:8.2-fpm

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libonig-dev libxml2-dev curl \
    libzip-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath gd xml zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the entire Laravel app first
COPY . .

# Ensure artisan has execute permissions
RUN chmod +x artisan

# Install PHP dependencies as www-data user to avoid root issues
RUN su www-data -s /bin/sh -c "composer install --no-dev --optimize-autoloader"

# Set storage and cache permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Run migrations safely (if DB ready)
RUN php artisan migrate --force || true
RUN php artisan config:cache || true
RUN php artisan route:cache || true

# Expose port
EXPOSE 10000

# Start Laravel PHP server
CMD php -S 0.0.0.0:10000 -t public
