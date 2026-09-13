#!/bin/sh
set -e

# Setup file database SQLite jika koneksi database diatur ke sqlite atau default
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p database
    touch database/database.sqlite
    chmod 666 database/database.sqlite || true
fi

# Buat symlink storage jika belum ada
php artisan storage:link --force 2>/dev/null || true

# Jalankan migrasi database
echo "Menjalankan migrasi database..."
php artisan migrate --force || echo "Migrasi database dilewati."

# Jalankan seeder akun penting (cs@vms.test, dll.)
echo "Memastikan akun dan role pengguna siap..."
php artisan db:seed --class=RoleAndUserSeeder --force || echo "Seeder role & user dilewati."

# Pastikan cache bersih saat deploy baru
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ambil port dari environment variable Render (default 10000)
PORT="${PORT:-10000}"

echo "Menjalankan aplikasi Laravel pada port ${PORT}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
