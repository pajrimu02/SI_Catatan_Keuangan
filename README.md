# 💰 SI Catatan Keuangan & Gaji

**Sistem Informasi Catatan Keuangan & Gaji** merupakan aplikasi berbasis web yang dirancang untuk membantu pengguna dalam mencatat, mengelola, dan memantau aktivitas keuangan secara lebih terstruktur.

Aplikasi ini menyediakan pengelolaan transaksi pemasukan dan pengeluaran, pencatatan gaji, serta dashboard yang menyajikan ringkasan kondisi keuangan dalam bentuk informasi visual. Dengan adanya sistem ini, pengguna dapat mengetahui perkembangan keuangan dan melihat riwayat transaksi dengan lebih mudah.

---

## 📸 Preview Aplikasi

Berikut merupakan beberapa tampilan utama dari aplikasi SI Catatan Keuangan & Gaji.

### 1. Transaksi Keuangan (Pemasukan & Pengeluaran)

Digunakan untuk mencatat seluruh aktivitas keuangan, baik uang yang masuk maupun uang yang keluar. Setiap transaksi dilengkapi dengan informasi tanggal, kategori, jumlah, dan keterangan sehingga pencatatan lebih terorganisir.

![Catatan Keuangan Keluar Masuk](docs/screenshots/1.png)

### 2. Catatan Gaji

Menyediakan fitur pencatatan dan pengelolaan data gaji untuk memantau nominal serta riwayat pembayaran gaji.

![Catatan Gaji](docs/screenshots/2.png)

### 3. Dashboard Ringkasan

Menampilkan gambaran umum kondisi keuangan melalui informasi total pemasukan, total pengeluaran, dan visualisasi grafik sehingga pengguna dapat memahami kondisi keuangan dengan lebih cepat.

![Dashboard](docs/screenshots/3.png)

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 📊 **Dashboard** | Menampilkan ringkasan pemasukan, pengeluaran, dan visualisasi data keuangan. |
| 💵 **Pencatatan Pemasukan** | Mencatat transaksi uang masuk berdasarkan tanggal, kategori, nominal, dan keterangan. |
| 💸 **Pencatatan Pengeluaran** | Mengelola transaksi uang keluar agar setiap pengeluaran dapat dipantau. |
| 🧾 **Catatan Gaji** | Mencatat dan mengelola informasi gaji beserta riwayat pembayarannya. |
| 👤 **Manajemen Profil** | Memungkinkan pengguna mengelola informasi profil akun. |

---

## 🛠️ Teknologi yang Digunakan

Aplikasi ini dikembangkan menggunakan beberapa teknologi berikut:

- **PHP** – Bahasa pemrograman sisi server.
- **Laravel 11/12** – Framework PHP untuk membangun aplikasi web.
- **MySQL / MariaDB** – Sistem manajemen basis data.
- **HTML & CSS** – Membangun struktur dan tampilan halaman.
- **JavaScript** – Mendukung interaksi pada antarmuka aplikasi.
- **Node.js & NPM** – Mengelola dependensi frontend.
- **Vite** – Mengelola proses build dan menjalankan aset frontend selama pengembangan.
- **Composer** – Mengelola dependensi PHP.

---

## 📋 Persyaratan Sistem

Pastikan perangkat telah memiliki beberapa perangkat lunak berikut sebelum menjalankan aplikasi:

| Kebutuhan | Versi |
|---|---|
| PHP | 8.2 atau 8.3 |
| Laravel | 11.x atau 12.x |
| Composer | Versi terbaru |
| Node.js | Versi LTS |
| NPM | Mengikuti instalasi Node.js |
| MySQL / MariaDB | Versi yang kompatibel |

---

## ⚙️ Instalasi dan Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal.

### 1. Clone Repository

Unduh project dari GitHub menggunakan perintah:

```bash
git clone https://github.com/username/SI_Catatan_Keuangan.git
```

Masuk ke direktori project:

```bash
cd SI_Catatan_Keuangan
```

### 2. Install Dependensi

Install seluruh dependensi backend menggunakan Composer:

```bash
composer install
```

Kemudian install dependensi frontend:

```bash
npm install
```

### 3. Konfigurasi Environment

Buat file `.env` berdasarkan konfigurasi bawaan:

```bash
cp .env.example .env
```

Buat application key Laravel:

```bash
php artisan key:generate
```

### 4. Konfigurasi Database

Buka file `.env`, kemudian sesuaikan konfigurasi database dengan MySQL yang digunakan.

Contoh konfigurasi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=catatan_keuangan
DB_USERNAME=root
DB_PASSWORD=
```

Buat database dengan nama `catatan_keuangan` melalui phpMyAdmin atau MySQL.

### 5. Migrasi dan Pengisian Data

Jalankan perintah berikut untuk membuat tabel database sekaligus mengisi data awal apabila seeder telah tersedia:

```bash
php artisan migrate --seed
```

### 6. Menjalankan Aplikasi

Untuk menjalankan aplikasi, buka **dua terminal** di direktori project.

**Terminal 1 – Menjalankan Vite**

```bash
npm run dev
```

Perintah ini digunakan untuk menjalankan server pengembangan frontend agar perubahan CSS dan JavaScript dapat dimuat selama proses pengembangan.

**Terminal 2 – Menjalankan Laravel**

```bash
php artisan serve
```

Perintah ini digunakan untuk menjalankan server aplikasi Laravel.

### 7. Akses Aplikasi

Setelah kedua server berjalan, buka browser dan akses alamat berikut:

**http://127.0.0.1:8000**

Aplikasi siap digunakan.

---

## 📖 Gambaran Alur Penggunaan

Secara umum, penggunaan aplikasi dapat dilakukan melalui tahapan berikut:

1. **Masuk ke aplikasi** menggunakan akun pengguna.
2. **Kelola transaksi keuangan** dengan menambahkan data pemasukan dan pengeluaran.
3. **Catat data gaji** untuk menyimpan informasi pembayaran gaji.
4. **Pantau dashboard** untuk melihat ringkasan pemasukan, pengeluaran, dan kondisi keuangan.
5. **Kelola profil** untuk memperbarui informasi akun.

---

## 🎯 Tujuan Pengembangan

Aplikasi SI Catatan Keuangan & Gaji dikembangkan untuk:

- Mempermudah proses pencatatan transaksi keuangan.
- Membantu pengguna memantau pemasukan dan pengeluaran.
- Menyediakan pencatatan gaji yang lebih terstruktur.
- Mengurangi risiko kehilangan atau kesulitan mencari riwayat transaksi.
- Menyajikan informasi keuangan dalam bentuk ringkasan yang mudah dipahami.

---

## 👨‍💻 Pengembangan

Project ini merupakan pengembangan aplikasi Sistem Informasi berbasis web dengan menggunakan framework Laravel.

**SI Catatan Keuangan & Gaji**  
*Manage Your Finance, Track Your Growth.*

