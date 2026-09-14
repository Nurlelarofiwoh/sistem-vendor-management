#!/bin/sh
set -e

# 1. Periksa apakah DB_HOST berisi placeholder (seperti <host-database-cloud>, <...>, dll)
IS_PLACEHOLDER=0
if [ -n "$DB_HOST" ]; then
    case "$DB_HOST" in
        *\<*|*\>*|*host-database-cloud*|*YOUR_*|*your_*)
            IS_PLACEHOLDER=1
            ;;
    esac
fi

# 2. Jika DB_CONNECTION tidak diatur, atau DB_HOST adalah placeholder, paksa ke sqlite
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ] || [ "$IS_PLACEHOLDER" -eq 1 ]; then
    echo "============================================================"
    echo "Sistem mendeteksi DB_CONNECTION='sqlite' atau DB_HOST merupakan placeholder."
    echo "Mengalihkan DB_CONNECTION ke 'sqlite' secara otomatis..."
    echo "============================================================"
    export DB_CONNECTION=sqlite
    export DB_DATABASE="/app/database/database.sqlite"
fi

# 3. Pengujian koneksi jika menggunakan database external (MySQL/PgSQL/MariaDB)
if [ "$DB_CONNECTION" != "sqlite" ]; then
    echo "Menguji koneksi database $DB_CONNECTION ke host '$DB_HOST'..."
    if ! php -r "
        \$conn = env('DB_CONNECTION', 'mysql');
        \$host = env('DB_HOST', '127.0.0.1');
        \$port = env('DB_PORT', 3306);
        \$db   = env('DB_DATABASE', 'laravel');
        \$user = env('DB_USERNAME', 'root');
        \$pass = env('DB_PASSWORD', '');
        try {
            \$dsn = \"\$conn:host=\$host;port=\$port;dbname=\$db\";
            \$pdo = new PDO(\$dsn, \$user, \$pass, [PDO::ATTR_TIMEOUT => 3]);
            echo \"Berhasil terhubung ke database \$conn.\n\";
            exit(0);
        } catch (\Throwable \$e) {
            echo \"Gagal terhubung ke database \$conn: \" . \$e->getMessage() . \"\n\";
            exit(1);
        }
    " 2>/dev/null; then
        echo "============================================================"
        echo "PERINGATAN: Koneksi ke server database '$DB_HOST' gagal/unreachable."
        echo "Mengalihkan DB_CONNECTION ke 'sqlite' secara otomatis agar web tidak crash..."
        echo "============================================================"
        export DB_CONNECTION=sqlite
        export DB_DATABASE="/app/database/database.sqlite"
    fi
fi

# 4. Pastikan file database SQLite ada dan dapat ditulis jika menggunakan sqlite
if [ "$DB_CONNECTION" = "sqlite" ]; then
    mkdir -p /app/database
    touch /app/database/database.sqlite
    chmod 666 /app/database/database.sqlite || true
fi

# 5. Buat symlink storage jika belum ada
php artisan storage:link --force 2>/dev/null || true

# 6. Jalankan migrasi database
echo "Menjalankan migrasi database ($DB_CONNECTION)..."
php artisan migrate --force || echo "Migrasi database dilewati."

# 7. Jalankan seeder database lengkap (roles, users, vendor, klien, & event)
echo "Memastikan seluruh data pengguna, vendor, klien, & event siap..."
php artisan db:seed --force || echo "Seeder database dilewati."

# 8. Bersihkan cache aplikasi
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 9. Ambil port dari environment variable Render (default 10000)
PORT="${PORT:-10000}"

echo "Menjalankan aplikasi Laravel pada port ${PORT}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
