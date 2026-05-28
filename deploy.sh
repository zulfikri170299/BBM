#!/bin/bash

# ==========================================
# BBM Application Deployment Script
# ==========================================
# Cara pakai:
# 1. Upload script ini ke folder project di server (misal: /root/bbm/deploy.sh)
# 2. Beri izin eksekusi: chmod +x deploy.sh
# 3. Jalankan: ./deploy.sh
# ==========================================

# Berhenti jika ada error
set -e

echo "🚀 Memulai Proses Deployment BBM..."
echo "-----------------------------------"

# 1. Pull Source Code Terbaru
echo "📥 1. Mengambil update dari Git (Pulling latest changes)..."
git pull origin main

# 2. Install Dependensi PHP
echo "🔧 2. Install/Update Dependensi PHP (Composer)..."
# Menggunakan --no-dev untuk production agar lebih ringan
composer install --optimize-autoloader --no-dev

# 3. Build Frontend Assets
echo "🎨 3. Build Frontend Assets (Vite/Tailwind)..."
npm install
npm run build

# 4. Database Migration
echo "🗄️  4. Menjalankan Database Migration..."
# --force diperlukan di production agar tidak minta konfirmasi interaktif
# CATATAN: Ini hanya akan mengupdate struktur tabel (schema) tanpa menghapus data yang ada.
# Jangan pernah jalankan \`php artisan migrate:fresh\` atau \`php artisan db:seed\` di production
# jika Anda ingin mempertahankan data lama dari database yang sudah berjalan.
php artisan migrate --force

# 5. Clear & Cache Konfigurasi
echo "🧹 5. Membersihkan dan Cache Ulang Konfigurasi..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Fix Permissions (Sangat Penting!)
echo "🔒 6. Memperbaiki Izin Folder & Database..."
# Pastikan web server (www-data) bisa akses
chown -R www-data:www-data .
chown -R www-data:www-data storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache database

# Khusus SQLite
if [ -f "database/database.sqlite" ]; then
    chown www-data:www-data database/database.sqlite
    chmod 664 database/database.sqlite
    echo "   - Izin database.sqlite diperbarui."
fi

# 7. Restart Services (Opsional)
# echo "🔄 7. Restart PHP-FPM & Nginx..."
# systemctl restart php8.2-fpm
# systemctl restart nginx

echo "-----------------------------------"
echo "✅ DEPLOYMENT SUKSES! Website sudah update."
