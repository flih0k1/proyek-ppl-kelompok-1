# Sistem Informasi Perpustakaan (SIPUS) - Tugas Kelompok

## 📝 Deskripsi Proyek
Proyek ini merupakan aplikasi manajemen perpustakaan berbasis web yang dirancang khusus untuk skala ruang baca Program Studi. Aplikasi ini bertujuan untuk mendigitalisasi proses sirkulasi buku, pengadaan koleksi baru, hingga pelaporan statistik bagi pimpinan.

## 🚀 Spesifikasi Teknis
- **Framework:** CodeIgniter 4 (PHP).
- **Database:** MySQL.
- **UI Template:** AdminLTE / SB Admin (Responsive Admin Template).
- **Architecture:** Modular / HMVC Pattern.

## 👥 Aktor & Peran (Roles)
Sistem ini mendukung 3 jenis pengguna:
1. **Member (Mahasiswa/Dosen):** Melakukan pendaftaran, melihat katalog, dan mengusulkan buku baru.
2. **Pustakawan (Admin):** Mengelola transaksi peminjaman, pengembalian, status denda, serta manajemen katalog buku.
3. **Pimpinan (Kaprodi):** Memantau laporan rekapitulasi bulanan dan statistik perpustakaan.

## ✨ Fitur Utama
Aplikasi ini dilengkapi dengan modul-modul berikut:
* **Manajemen Akun (Permission):** Register & Multi-role login serta pembaruan profil pengguna.
* **Sirkulasi Peminjaman:** Pencatatan peminjaman, pengembalian, dan penghitungan denda otomatis (Rp 2.000/hari).
* **Pengadaan Buku:** Alur pengusulan buku oleh member hingga penginputan ke katalog oleh pustakawan.
* **Maintenance Buku:** Pengaturan status ketersediaan buku (Tersedia, Dalam Perbaikan, atau Dipinjam) secara real-time.
* **Laporan (Pimpinan):** Tabel rekapitulasi bulanan yang mencakup statistik peminjam, buku populer, denda, dan buku rusak.
* **Dashboard & Katalog:** Ringkasan data (Total Buku/Member) dan fitur pencarian buku berdasarkan judul atau penulis.

## 🛠️ Cara Instalasi
Pastikan Anda sudah menginstal XAMPP (dengan PHP versi yang didukung CI4) dan Composer.

1. **Clone Repositori**
   ```bash
   git clone https://github.com/flih0k1/proyek-ppl-kelompok-1.git
   cd nama-repo

2. Instal Dependency
   ```bash
   composer install

3. Konfigurasi Database
- Buat database baru di phpMyAdmin dengan nama db_perpus.
- Import file perpus2.sql yang tersedia di root folder ke database tersebut.
- Salin file env menjadi .env dan sesuaikan pengaturan database:
  ```bash
  database.default.hostname = localhost
  database.default.database = db_perpus
  database.default.username = root
  database.default.password =
  database.default.DBDriver = MySQLi

4. Jalankan Aplikasi
  ```bash
  php spark serve
  ```

Buka http://localhost:8080 di browser Anda.
