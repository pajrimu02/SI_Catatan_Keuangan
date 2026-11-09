# SI Catatan Keuangan & Gaji

Aplikasi berbasis web untuk mengelola dan memantau pencatatan keuangan pribadi/perusahaan, mencakup arus kas masuk & keluar, rekapitulasi gaji, serta ringkasan data visual melalui dashboard intuitif.

---

## 📷 Tangkapan Layar (Screenshots)

### 1. Transaksi Keuangan (Masuk & Keluar)
![Catatan Keuangan Keluar Masuk](docs/screenshots/1.png)

### 2. Catatan Gaji
![Catatan Gaji](docs/screenshots/2.png)

### 3. Dashboard Ringkasan
![Dashboard](docs/screenshots/3.png)

---

## 🚀 Fitur Utama

- **Dashboard Visual**: Menampilkan ringkasan total pemasukan, pengeluaran, dan grafik arus kas secara *real-time*.
- **Catatan Keuangan (Keluar & Masuk)**: Transaksi pencatatan arus kas dengan detail tanggal, kategori, dan jumlah.
- **Catatan Gaji**: Pengelolaan data penggajian dan riwayat pembayaran gaji.
- **Manajemen Profil**: Pengaturan data akun pengguna.

---

## 🛠️ Persyaratan Sistem

- **PHP**: `^8.2` atau `^8.3`
- **Laravel**: `^11.0` atau `^12.0`
- **Composer**
- **Node.js & NPM**
- **MySQL / MariaDB**

---

## ⚙️ Cara Instalasi & Penggunaan

1. **Clone Repositori**
   git clone [https://github.com/username/SI_Catatan_Keuangan.git](https://github.com/username/SI_Catatan_Keuangan.git)
   cd SI_Catatan_Keuangan

2. **Install Dependensi PHP & Node**
   composer install
   npm install

3. **Konfigurasi Lingkungan (.env)**
   cp .env.example .env
   php artisan key:generate

4. **Migrasi Database & Seed Data**
   php artisan migrate --seed

5. **Jalankan Aplikasi**
   Buka dua terminal dan jalankan perintah berikut secara bersamaan:

   Terminal 1 (Vite Asset Bundler):
   npm run dev

   Terminal 2 (Laravel Server):
   php artisan serve

   Akses aplikasi di browser melalui URL: [http://127.0.0.1:8000](http://127.0.0.1:8000)