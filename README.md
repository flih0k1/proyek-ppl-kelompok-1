# Sistem Informasi Perpustakaan (Perpus2)

Aplikasi Sistem Informasi Perpustakaan yang dibangun menggunakan framework **CodeIgniter 4**. Aplikasi ini menggunakan arsitektur HMVC (Hierarchical Model View Controller) dengan dukungan module, yaitu modul `Admin`, `Auth`, `Member`, dan `Pimpinan`.

## 🚀 Teknologi yang Digunakan

- **PHP**: ^8.1
- **Framework**: CodeIgniter 4
- **Database**: MySQL / MariaDB
- **Tools**: Composer, Laragon / XAMPP

## ⚙️ Persyaratan Sistem

- PHP versi 8.1 atau yang lebih baru.
- Ekstensi PHP yang dibutuhkan: `intl`, `mbstring`, `mysqli`.
- [Composer](https://getcomposer.org/) terinstal di sistem Anda.

## 🛠️ Panduan Instalasi (Setup Awal)

1. **Clone atau Ekstrak Repository**
   Tempatkan folder proyek ini di dalam direktori proyek server lokal Anda (misal di folder `htdocs` atau `www`).

2. **Instal Dependensi**
   Buka terminal di dalam folder proyek, jalankan:

   ```bash
   composer install
   ```

3. **Konfigurasi Database**
   - Buat database baru dengan nama `perpus`.
   - Import database dari file `perpus2.sql` yang ada di root direktori proyek.
     ```bash
     mysql -u root -p perpus < perpus2.sql
     ```

4. **Konfigurasi Environment (.env)**
   Salin file `env` menjadi `.env` lalu sesuaikan isinya:

   ```env
   CI_ENVIRONMENT = development

   # Database
   database.default.hostname = localhost
   database.default.database = perpus
   database.default.username = root
   database.default.password =

   # (Opsional) Konfigurasi Email jika di masa depan akan digunakan
   # email.SMTPHost = smtp.gmail.com
   # email.SMTPUser = email-anda@gmail.com
   # email.SMTPPass = password-aplikasi-anda
   # email.SMTPPort = 587
   # email.SMTPCrypto = tls
   ```

5. **Jalankan Aplikasi**
   ```bash
   php spark serve
   ```
   Aplikasi dapat diakses di `http://localhost:8080/`.

---

## 🔐 Panduan Fitur Autentikasi

### 1. Registrasi Akun Baru

1. Buka halaman registrasi melalui URL: `http://localhost:8080/auth/register` (atau klik tombol daftar pada halaman login).
2. Isi form pendaftaran dengan informasi yang diminta: Username, Email, Nomor Whatsapp, Nama Lengkap, dan Password.
3. Submit form. Sistem akan memvalidasi (username & email belum terpakai, password minimal 6 karakter).
4. Jika berhasil, Anda akan diarahkan ke halaman login dan bisa langsung masuk.

### 2. Login

1. Buka halaman login: `http://localhost:8080/auth/login`
2. Masukkan _username_ dan _password_ Anda.
3. Jika kredensial benar, Anda akan diarahkan ke _dashboard_ utama (sesuai dengan role Anda: Admin, Member, atau Pimpinan).

### 3. Fitur Keamanan

- **Validasi Terpusat**: Seluruh form registrasi dan perubahan password dilindungi oleh validasi (minimal panjang karakter, format valid, keunikan username/email).
- **Enkripsi Kata Sandi**: Menggunakan standar enkripsi Bcrypt bawaan pustaka IonAuth.

---

## 💡 Troubleshooting (Penyelesaian Masalah)

- **"Akun Anda Tidak Aktif"**:
  Pengguna telah dinonaktifkan oleh Admin. Silakan hubungi Pimpinan/Admin untuk mengaktifkan akun.
- **"Username atau password salah"**:
  Pastikan kredensial yang dimasukkan benar.

## 📂 Struktur Direktori Utama

- `app/` : Konfigurasi framework dan inti dari CodeIgniter 4.
- `Modules/` : Modul yang dikembangkan dengan pola HMVC, berisi direktori spesifik untuk fungsionalitas `Admin`, `Auth`, `Member`, dan `Pimpinan`.
- `public/` : Tempat diletakkannya _assets_ aplikasi (CSS, JS, Images) serta file _entry point_ aplikasi (`index.php`).
