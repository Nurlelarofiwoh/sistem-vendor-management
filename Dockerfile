FROM php:8.2-cli

# Install dependency sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Install ekstensi PHP wajib untuk Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Ambil Composer resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Tambahkan --no-scripts agar Composer tidak mengeksekusi skrip artisan saat build Docker
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Atur hak akses folder cache dan storage
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000

# Jalankan server Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000