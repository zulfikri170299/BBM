# Sistem Manajemen BBM (SIMAK BBM)

## Deskripsi

SIMAK BBM adalah aplikasi berbasis web yang dibangun dengan Laravel 10 untuk mengelola distribusi bahan bakar kendaraan dinas operasional. Sistem ini memfasilitasi monitoring saldo BBM, transaksi dengan barcode/QR Code, dan pelaporan penggunaan bahan bakar secara _real-time_.

## Fitur Utama

1. **Multi-Role User**:
    - **Super Admin**: Mengelola Satker, User, Topup Saldo Global, dan Laporan.
    - **Admin Satker**: Mengelola Kendaraan, Personel, dan Cetak Kartu BBM.
    - **Petugas BBM**: Melakukan transaksi (Scan Barcode & Input PIN) dan cetak struk.
    - **Personel/Driver**: Memantau saldo pribadi dan riwayat transaksi.

2. **Manajemen Kendaraan**:
    - Registrasi kendaraan dengan Barcode unik.
    - PIN 6 digit untuk keamanan transaksi.
    - Kartu BBM PDF siap cetak.

3. **Transaksi Real-time**:
    - Input transaksi via barcode.
    - Kalkulasi otomatis berdasarkan harga BBM (Pertalite, Pertamax, Solar, Dexlite).
    - Verifikasi saldo sebelum transaksi.

4. **Laporan & Ekspor**:
    - Laporan transaksi harian/bulanan.
    - Ekspor data ke PDF dan Excel.
    - Cetak struk termal (Thermal Printer 58mm/80mm).

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB (Database)

## Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal Anda:

1. **Clone Repository (Jika ada logikanya)**

    ```bash
    git clone https://github.com/username/simak-bbm.git
    cd simak-bbm
    ```

2. **Install Dependensi PHP**

    ```bash
    composer install
    ```

3. **Install Dependensi Frontend**

    ```bash
    npm install
    ```

4. **Konfigurasi Environment**
    - Salin file `.env.example` menjadi `.env`:
        ```bash
        cp .env.example .env
        ```
    - Buka file `.env` dan sesuaikan konfigurasi database:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=db_spbp
        DB_USERNAME=root
        DB_PASSWORD=
        ```

5. **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

6. **Migrasi dan Seeding Database**
   Jalankan perintah ini untuk membuat tabel dan mengisi data awal (pengguna, satker, master data):
    ```bash
    php artisan migrate:fresh --seed
    ```

## Menjalankan Aplikasi

Anda perlu menjalankan dua terminal secara bersamaan.

**Terminal 1 (Server PHP):**

```bash
php artisan serve
```

**Terminal 2 (Build Assets / Vite):**

```bash
npm run dev
```

Akan muncul pesan Server running on `http://127.0.0.1:8000`. Buka alamat tersebut di browser.

> **Catatan:** Jika Anda tidak memiliki Node.js, tampilan mungkin berantakan. Pastikan `npm run build` dijalankan setidaknya sekali jika deploy ke server non-dev.

## Menjalankan dengan Docker (Produksi)

Jika akan mendeploy ke server produksi seperti VPS, BBM sudah tersetup otomatis dengan container (PHP FPM, Nginx, SQLite).

1. Clone repositori ke dalam server produksi Anda.
2. Masuk ke direktori `simak-bbm`.
3. Buat file `.env` dengan menyalin versi example-nya:
```bash
cp .env.example .env
```
*(Opsional: Anda dapat mengedit isi `.env` menggunakan `nano .env` jika ingin mengganti port bawaan via variabel `WEB_PORT` atau preferensi lainnya).*
4. Jalankan baris perintah docker berikut untuk proses instalasi dependensi, aset build, dan start backend server:
```bash
docker compose -f compose.prod.yaml up --build -d
```
5. Setelah container menyala sukses, Anda dapat membuat akun Super Administrator dengan cara menjalankan script seeder custom berikut yang masuk ke dalam container:
```bash
docker compose -f compose.prod.yaml exec app php artisan db:seed --class=AdminSeeder
```
6. Akses aplikasi Anda melalui browser di alamat `http://<IP-SERVER>:8088` (atau port lain jika Anda menggantinya).

## Akun Demo (Default)

Gunakan akun berikut untuk login pertama kali (Password semua akun: `password`):

| Role             | Email                     | Deskripsi                           |
| ---------------- | ------------------------- | ----------------------------------- |
| **Super Admin**  | `superadmin@example.com`  | Akses penuh sistem.                 |
| **Admin Satker** | `adminsatker@example.com` | Kelola kendaraan & personel satker. |
| **Petugas BBM**  | `petugas@example.com`     | Scan barcode & proses isi bensin.   |
| **Personel**     | `personel@example.com`    | Driver/User pemilik saldo.          |

## Alur Penggunaan Singkat

1. Login sebagai **Super Admin** -> Menu "Topup Global" untuk mengisi saldo ke semua kendaraan.
2. Login sebagai **Admin Satker** -> Menu "Kendaraan" -> "Print" untuk mencetak kartu barcode kendaraan.
3. Login sebagai **Petugas BBM** -> Menu "Transaksi" -> Scan barcode pada kartu -> Masukkan PIN (Default: 123456) & Jumlah Liter -> Proses.
4. Saldo kendaraan akan berkurang otomatis.

## Lisensi

Software ini berlisensi open-source di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).
