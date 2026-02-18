---
description: Panduan deploy perubahan terbaru ke server production (Laravel + Vite + Nginx)
---

# Deploy ke Server Production

## Prasyarat
- SSH akses ke server
- Nginx + PHP-FPM sudah berjalan
- Composer & Node.js sudah terinstall di server
- Database (MySQL/PostgreSQL) sudah dikonfigurasi

## Langkah-langkah Deploy

### 1. SSH ke Server
```bash
ssh user@ip-server
cd /path/ke/web-ppa
```

### 2. Aktifkan Maintenance Mode (opsional, agar user tidak lihat error saat deploy)
```bash
php artisan down --retry=60 --refresh=15
```

### 3. Pull Kode Terbaru
// turbo
```bash
git pull origin main
```

### 4. Install Dependensi PHP (jika ada package baru)
```bash
composer install --no-dev --optimize-autoloader
```

### 5. Install Dependensi Node & Build Aset
```bash
npm install
npm run build
```
> **Catatan:** `npm run build` akan compile Vite + TailwindCSS + DaisyUI ke folder `public/build`.
> Di server TIDAK perlu jalankan `npm run dev`, cukup `npm run build` sekali saja.

### 6. Jalankan Migration Database (PENTING untuk perubahan baru ini)
```bash
php artisan migrate --force
```
> Flag `--force` diperlukan karena di environment production.
> Migration baru yang akan dijalankan:
> - `update_status_enum_in_complaints_table` — update enum status
> - `rename_tahap_1_to_selesai_tahap_1_in_complaints` — rename status
> - `add_location_columns_to_complaints_and_consultations_tables` — tambah kolom latitude & longitude
> - `add_phone_to_consultations_table` — tambah kolom no_hp di konsultasi
> - `add_verification_to_testimonials_table` — tambah kolom verifikasi & relasi di testimoni
> - `add_testimonial_token_to_complaints_and_consultations` — tambah kolom token testimoni
>
> **Data yang ada TIDAK akan terhapus**, migration hanya menambah kolom baru.

### 7. (Opsional) Jalankan Seeder untuk Data Dummy
```bash
php artisan db:seed --class=ComplaintSeeder --force
php artisan db:seed --class=ConsultationSeeder --force
```
> **PERINGATAN:** Seeder akan MENGHAPUS data aduan/konsultasi yang ada lalu mengisi ulang dengan data dummy!
> Jangan jalankan ini jika sudah ada data production yang nyata.
> Seeder hanya untuk testing/demo.

### 8. Bersihkan & Rebuild Cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 9. Nonaktifkan Maintenance Mode
```bash
php artisan up
```

### 10. Restart PHP-FPM (jika perlu)
```bash
sudo systemctl restart php8.2-fpm
```
> Sesuaikan versi PHP dengan yang terinstall di server (php8.1-fpm, php8.2-fpm, php8.3-fpm).

---

## Rangkuman Perintah (Copy-Paste Sekaligus)

```bash
cd /path/ke/web-ppa
php artisan down --retry=60 --refresh=15
git pull origin main
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan up
sudo systemctl restart php8.2-fpm
```

---

## Troubleshooting

### Error "Permission denied" saat git pull
```bash
sudo chown -R www-data:www-data /path/ke/web-ppa
sudo chmod -R 775 storage bootstrap/cache
```

### Halaman kosong / error 500 setelah deploy
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
tail -f storage/logs/laravel.log
```

### Migration gagal
```bash
php artisan migrate:status    # cek migration mana yang belum jalan
php artisan migrate --force   # coba lagi
```
