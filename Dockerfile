# Example Dockerfile for Laravel
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Copy composer files
COPY composer.json composer.lock ./

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN composer install --no-dev --optimize-autoloader

# Copy application
COPY . .

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Run migrations
RUN php artisan migrate --force

# Expose port
EXPOSE 9000

CMD ["php-fpm"]
