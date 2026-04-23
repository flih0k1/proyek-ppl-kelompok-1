# Sistem Informasi Perpustakaan (SIPUS) - Tugas Kelompok

## 📝 Deskripsi Proyek
[cite_start]Proyek ini merupakan aplikasi manajemen perpustakaan berbasis web yang dirancang khusus untuk skala ruang baca Program Studi[cite: 1, 3]. [cite_start]Aplikasi ini bertujuan untuk mendigitalisasi proses sirkulasi buku, pengadaan koleksi baru, hingga pelaporan statistik bagi pimpinan[cite: 3].

## 🚀 Spesifikasi Teknis
- [cite_start]**Framework:** CodeIgniter 4 (PHP)[cite: 6].
- [cite_start]**Database:** MySQL[cite: 7, 8].
- [cite_start]**UI Template:** AdminLTE / SB Admin (Responsive Admin Template)[cite: 9].
- **Architecture:** Modular / HMVC Pattern.

## 👥 Aktor & Peran (Roles)
[cite_start]Sistem ini mendukung 3 jenis pengguna[cite: 10]:
1. [cite_start]**Member (Mahasiswa/Dosen):** Melakukan pendaftaran, melihat katalog, dan mengusulkan buku baru[cite: 11, 17, 30].
2. [cite_start]**Pustakawan (Admin):** Mengelola transaksi peminjaman, pengembalian, status denda, serta manajemen katalog buku[cite: 12, 21, 22, 31].
3. [cite_start]**Pimpinan (Kaprodi):** Memantau laporan rekapitulasi bulanan dan statistik perpustakaan[cite: 13, 37].

## ✨ Fitur Utama
Aplikasi ini dilengkapi dengan modul-modul berikut:
* [cite_start]**Manajemen Akun (Permission):** Register & Multi-role login serta pembaruan profil pengguna[cite: 15, 17, 18].
* [cite_start]**Sirkulasi Peminjaman:** Pencatatan peminjaman, pengembalian, dan penghitungan denda otomatis (Rp 2.000/hari)[cite: 19, 21, 22, 23].
* [cite_start]**Pengadaan Buku:** Alur pengusulan buku oleh member hingga penginputan ke katalog oleh pustakawan[cite: 27, 30, 32].
* [cite_start]**Maintenance Buku:** Pengaturan status ketersediaan buku (Tersedia, Dalam Perbaikan, atau Dipinjam) secara real-time[cite: 33, 35, 46].
* [cite_start]**Laporan (Pimpinan):** Tabel rekapitulasi bulanan yang mencakup statistik peminjam, buku populer, denda, dan buku rusak[cite: 37, 39, 40].
* [cite_start]**Dashboard & Katalog:** Ringkasan data (Total Buku/Member) dan fitur pencarian buku berdasarkan judul atau penulis[cite: 44, 45].

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
