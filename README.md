<div align="center">
  <br>
  <h1>🚗 eCompare</h1>
  <p>
    <strong>Economic Vehicle Comparing Tools</strong><br>
    <em>Platform cerdas untuk mencari, membandingkan, dan menilai nilai ekonomis kendaraan Anda.</em>
  </p>
  
  <p>
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine JS" />
    <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  </p>
</div>

---

## 📖 Tentang eCompare

**eCompare** adalah aplikasi web modern berbasis *Laravel* yang dirancang untuk membantu calon pembeli kendaraan dalam mengambil keputusan cerdas. Aplikasi ini tidak hanya membandingkan spesifikasi teknis dan harga beli, tetapi juga mengkalkulasikan **biaya operasional jangka panjang** (pajak tahunan, estimasi servis, depresiasi harga, dan efisiensi bahan bakar) untuk menghasilkan *Economic Score* yang objektif.

## ✨ Fitur Utama

- 🔍 **Pencarian Cerdas**: Cari kendaraan spesifik berdasarkan merk, tahun, transmisi, maupun bahan bakar.
- ⚖️ **Komparasi Kendaraan**: Bandingkan spesifikasi dan nilai ekonomi hingga 3 kendaraan sekaligus dalam bentuk tabel *side-by-side*.
- 📊 **Economic Score Engine**: Algoritma unik yang memberikan skor ekonomis untuk menentukan kendaraan mana yang paling hemat untuk dompet Anda.
- 💾 **History & Favorites**: Simpan hasil komparasi Anda atau tandai kendaraan favorit untuk dilihat kembali nanti.
- 🛡️ **Role-based Authentication**: Akses khusus untuk *User* biasa dan panel *Admin* untuk mengelola data master.
- 📱 **Desain Responsif**: Antarmuka yang bersih, modern, dan nyaman digunakan baik di PC maupun *smartphone*.

## 🛠️ Tech Stack

- **Backend**: Laravel 10/11 (PHP 8.2+)
- **Frontend**: Tailwind CSS, Alpine.js, Blade Components
- **Database**: MySQL 8.0+
- **Assets Bundler**: Vite

---

## 🚀 Panduan Instalasi (Lokal)

Ikuti langkah-langkah di bawah ini untuk menjalankan project eCompare di komputer (local environment) Anda.

### Prasyarat
Pastikan Anda sudah menginstal:
- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)
- Database MySQL (misal: Laragon, XAMPP, dsb)

### Langkah Instalasi

1. **Clone repository ini**
   ```bash
   git clone https://github.com/USERNAME_KAMU/eCompare.git
   cd eCompare
   ```

2. **Install dependency PHP & Node.js**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file konfigurasi bawaan dan *generate* application key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Buka file `.env` dan sesuaikan nama `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan konfigurasi MySQL Anda.*

4. **Migrasi Database & Seeding**
   Langkah ini akan membuat tabel dan mengisi ratusan dataset kendaraan awal (108 mobil dan 79 motor).
   ```bash
   php artisan migrate --seed
   ```

5. **Symlink Storage (Opsional)**
   Agar gambar dapat ditampilkan dengan baik.
   ```bash
   php artisan storage:link
   ```

6. **Build Frontend Assets & Jalankan Server**
   ```bash
   npm run build
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses melalui `http://localhost:8000`.

## ☁️ Deployment ke Vercel

Repository ini memakai PHP community runtime dan aset frontend hasil build yang ada di `public/build`.

1. Build aset sebelum commit:
   ```bash
   npm ci
   npm run build
   ```
2. Tambahkan environment variables berikut di **Vercel → Project Settings → Environment Variables**:
   - `APP_KEY` — buat dengan `php artisan key:generate --show`
   - `APP_URL` — URL production Vercel
   - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`
3. Gunakan database eksternal yang dapat diakses Vercel, lalu jalankan migration dan seeder terhadap database tersebut:
   ```bash
   php artisan migrate --seed --force
   ```

Filesystem function Vercel bersifat sementara. File upload production sebaiknya disimpan di object storage seperti S3, bukan disk `local`.

---

## 🔐 Akun Demo

Anda dapat langsung mencoba semua fitur dengan menggunakan akun *seeder* berikut:

| Peran (Role) | Email | Password | Hak Akses |
| --- | --- | --- | --- |
| **Admin** | `admin@ecompare.test` | `password` | Mengelola data Master, Pengguna, dan Notifikasi. |
| **User** | `user@ecompare.test` | `password` | Mencari kendaraan, membuat komparasi, dan akses profil. |

---

## 🧮 Logika Economic Score

Data spesifikasi diambil dari dataset internal CSV (`cars.csv` dan `motorcycles.csv`). Jika terdapat nilai pajak, estimasi servis bulanan, atau persentase depresiasi yang kosong dari dataset, *Seeder* akan menghitung nilai *default* tersebut secara deterministik.
Admin dapat menyesuaikan angka tersebut secara manual di panel *Dashboard*.

**Bobot Penilaian Sistem:**
- Harga Baru: `35%`
- Harga Bekas: `25%`
- Efisiensi BBM / *Range* EV: `20%`
- Pajak Tahunan: `10%`
- Persentase Depresiasi: `10%`

*(Efisiensi BBM konvensional dan EV/Listrik dipisah karena memiliki benchmark ukur yang berbeda).*

---

## 🧪 Pengujian (Testing)

Aplikasi ini dilengkapi dengan pengujian otomatis (*Automated Testing*) yang komprehensif menggunakan PHPUnit / Pest.

Untuk menjalankan pengujian, jalankan perintah:
```bash
php artisan test
```
*Cakupan tes meliputi: Autentikasi sistem, Profil, Proteksi hak akses Role, Validasi batasan tiga kendaraan dalam satu komparasi, serta keandalan fungsi simpanan riwayat.*

---
<div align="center">
  Dibuat dengan ❤️ untuk Ujian Akhir Semester Pemrograman Web 2.
</div>
