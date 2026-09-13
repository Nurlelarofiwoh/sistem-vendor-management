# ==========================================
# Tahap 1: Build Aset Frontend (Node.js & Vite)
# ==========================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app

COPY package*.json ./
RUN npm ci || npm install

COPY . .
RUN npm run build

# ==========================================
# Tahap 2: Runtime PHP untuk Laravel
# ==========================================
FROM php:8.2-cli

# Install dependency sistem untuk ekstensi PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Konfigurasi & install ekstensi PHP (MySQL, PostgreSQL, SQLite, GD, Intl, Zip, dll.)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl

# Ambil Composer resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy source code proyek
COPY . .

# Copy hasil build frontend Vite dari Tahap 1
COPY --from=frontend-builder /app/public/build ./public/build

# Install dependency Laravel melalui Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --ignore-platform-reqs

# Atur izin akses folder storage dan bootstrap cache
RUN chmod -R 777 storage bootstrap/cache

# Atur hak eksekusi untuk entrypoint script dan bersihkan karakter Windows CRLF
RUN sed -i 's/\r$//' docker-entrypoint.sh && chmod +x docker-entrypoint.sh

EXPOSE 10000

# Jalankan entrypoint script
ENTRYPOINT ["/app/docker-entrypoint.sh"]