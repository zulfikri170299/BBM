# Panduan Migrasi & Deploy BBM ke Production

Dokumen ini merangkum langkah-langkah kritis dan pelajaran yang didapat saat men-deploy aplikasi BBM ke server production, khususnya saat menggunakan Nginx dan Cloudflare/Tunnel.

## 1. Persiapan Server (Environment)

### Folder Permissions
Masalah umum: **Error 500** saat login atau akses halaman.
**Solusi:** Pastikan web server (`www-data`) memiliki akses *write* ke folder storage dan database.

```bash
# Ubah kepemilikan folder aplikasi ke www-data
chown -R www-data:www-data /path/to/bbm

# Set permission khusus untuk storage dan database
chmod -R 775 storage bootstrap/cache
chmod -R 775 database
touch database/database.sqlite
chown www-data:www-data database/database.sqlite
```

### Dependency Installation
Jangan lupa install dependensi PHP dan Node.js:
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

## 2. Konfigurasi Nginx (PENTING!)

Masalah umum: **Error 419 Page Expired** (CSRF Token Mismatch) saat akses via HTTPS/Cloudflare.
**Penyebab:** Nginx di server (port http) tidak memberitahu Laravel bahwa request aslinya adalah HTTPS.

**Solusi:** Tambahkan `fastcgi_param HTTPS on;` di blok PHP Nginx.

**File:** `/etc/nginx/sites-available/bbm`
```nginx
server {
    listen 8092; # Sesuaikan port
    server_name _;
    root /path/to/bbm/public;

    # ... konfigurasi lain ...

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        
        # WAJIB UNTUK CLOUDFLARE / HTTPS PROXY
        fastcgi_param HTTPS on; 
        
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## 3. Konfigurasi Laravel (.env)

Sesuaikan `.env` untuk production:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bbm.ikydev.site  # Wajib HTTPS jika pakai domain SSL

# Session Configuration (Tips: Pakai 'file' biar aman dan mudah didebug)
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null  # Biarkan null agar auto-detect domain
SESSION_SECURE_COOKIE=true
```

## 4. Source Code Update (AppServiceProvider)

Untuk memaksa HTTPS di level aplikasi (terutama untuk generate link asset/route), tambahkan kode ini di `app/Providers/AppServiceProvider.php`:

```php
public function boot(): void
{
    if($this->app->environment('production')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
```

## 5. Deployment Commands (Cheat Sheet)

Setiap kali ada update code (git pull), jalankan perintah ini di server:

```bash
# 1. Pindah ke folder
cd /var/www/bbm # sesuaikan path

# 2. Pull changes
git pull origin main

# 3. Optimize dependencies
composer install --no-dev
npm run build

# 4. Migrate database (jika ada perubahan DB)
php artisan migrate --force

# 5. Clear Cache (Wajib!)
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart Service (Opsional tapi disarankan jika ubah config php/nginx)
# systemctl restart php8.2-fpm
# systemctl restart nginx
```

## 6. Deployment Automation (New!)

Untuk mempermudah proses di atas, saya sudah buatkan script otomatis bernama `deploy.sh` di root folder project.

### Cara Pakai:
1.  **Upload** file `deploy.sh` ke folder project di server (misal: `/root/bbm/`).
2.  **Beri Izin Eksekusi** (sekali saja):
    ```bash
    chmod +x deploy.sh
    ```
3.  **Jalankan Script** setiap kali mau update:
    ```bash
    ./deploy.sh
    ```

Script ini akan otomatis melakukan:
*   Git Pull
*   Composer Install & NPM Build
*   Database Migration
*   Clear Cache
*   Fix Permissions (Folder & SQLite)

### Tips Tambahan: One-Liner Deploy dari Laptop
Anda bahkan bisa menjalankan script ini langsung dari laptop Windows Anda tanpa masuk ke server dulu:
```bash
ssh root@100.100.20.1 "cd /root/bbm && ./deploy.sh"
```
Simpan perintah ini di Notepad atau buat shortcut agar deploy semudah satu kali klik! 🚀
