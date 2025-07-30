FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim unzip git curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy seluruh project
COPY . .

# ✅ Pastikan folder bootstrap/cache ada dan writable
RUN mkdir -p bootstrap/cache \
    && chown -R www-data:www-data bootstrap/cache \
    && chmod -R 775 bootstrap/cache

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# Set permission storage juga
RUN chown -R www-data:www-data storage \
    && chmod -R 775 storage

# Expose port dan jalankan server
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
