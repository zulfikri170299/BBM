#!/bin/sh
set -e

echo "==> [Entrypoint] Memulai setup aplikasi..."



# -------------------------------------------------------
# 1. Setup .env jika belum ada
# -------------------------------------------------------
if [ ! -f /var/www/html/.env ]; then
    echo "==> [Entrypoint] Membuat .env dari .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# -------------------------------------------------------
# 2. Generate APP_KEY jika belum ada atau kosong
#    (skip jika .env read-only / mounted dari host)
# -------------------------------------------------------
APP_KEY_VALUE=$(grep -E '^APP_KEY=' /var/www/html/.env | cut -d '=' -f2)
if [ -z "$APP_KEY_VALUE" ]; then
    if [ -w /var/www/html/.env ]; then
        echo "==> [Entrypoint] Generating APP_KEY..."
        php artisan key:generate --force
    else
        echo "==> [WARN] APP_KEY kosong tapi .env read-only. Isi APP_KEY di host terlebih dahulu!"
    fi
fi

# -------------------------------------------------------
# 3. Populate shared public volume (jika masih kosong)
# -------------------------------------------------------
if [ ! -f /var/www/html/public/index.php ]; then
    echo "==> [Entrypoint] Menyalin file public ke volume bersama..."
    cp -r /public-init/. /var/www/html/public/
    chown -R www-data:www-data /var/www/html/public
fi

# -------------------------------------------------------
# 4. Buat file SQLite jika belum ada
# -------------------------------------------------------
DB_PATH="${DB_DATABASE:-/var/www/html/storage/database.sqlite}"
if [ ! -f "$DB_PATH" ]; then
    echo "==> [Entrypoint] Membuat database SQLite: $DB_PATH"
    touch "$DB_PATH"
    chown www-data:www-data "$DB_PATH"
fi

# -------------------------------------------------------
# 5. Jalankan migrasi database
# -------------------------------------------------------
echo "==> [Entrypoint] Menjalankan migrasi database..."
php artisan migrate --force

# -------------------------------------------------------
# 5a. Jalankan ProductionSeeder jika belum pernah (cek tabel roles via sqlite)
# -------------------------------------------------------
ROLE_COUNT=$(php -r "
  try {
    \$db = new PDO('sqlite:$DB_PATH');
    \$row = \$db->query(\"SELECT COUNT(*) FROM roles\")->fetchColumn();
    echo \$row;
  } catch (\Exception \$e) {
    echo 0;
  }
" 2>/dev/null || echo "0")
if [ "$ROLE_COUNT" = "0" ]; then
    echo "==> [Entrypoint] Menjalankan ProductionSeeder (first-time setup)..."
    php artisan db:seed --class=ProductionSeeder --force || echo "==> [WARN] ProductionSeeder gagal, skip."
else
    echo "==> [Entrypoint] Data production sudah ada ($ROLE_COUNT roles), skip seeder."
fi

# -------------------------------------------------------
# 6. Buat symlink storage jika belum ada
# -------------------------------------------------------
if [ ! -L /var/www/html/public/storage ]; then
    echo "==> [Entrypoint] Membuat storage symlink..."
    php artisan storage:link
fi

# -------------------------------------------------------
# 7. Optimasi Laravel & Fix Permission Akhir
# -------------------------------------------------------
echo "==> [Entrypoint] Optimasi Laravel..."
php artisan optimize

echo "==> [Entrypoint] Memastikan hak akses storage milik www-data..."
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

echo "==> [Entrypoint] Setup selesai! Menjalankan: $@"
exec "$@"
