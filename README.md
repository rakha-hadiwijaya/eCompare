# E-Compare

Economic Vehicle Comparing Tools — aplikasi Laravel 12 untuk mencari, membandingkan, dan menilai kendaraan berdasarkan harga serta biaya operasional.

## Menjalankan aplikasi

```bash
composer install
npm install
npm run build
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Konfigurasi lokal saat ini memakai database MySQL `ecompare` dan Laragon. Salin `.env.example` menjadi `.env`, lalu sesuaikan kredensial database bila perlu.

## Akun demo

- Admin: `admin@ecompare.test` / `password`
- User: `user@ecompare.test` / `password`

## Dataset dan Economic Score

Seeder mengimpor `database/data/cars.csv` (108 mobil) dan `database/data/motorcycles.csv` (79 motor). Pajak, servis, dan depresiasi yang tidak tersedia pada sumber dihitung deterministik ketika seeding dan dapat diperbarui admin.

Bobot skor: harga baru 35%, harga bekas 25%, efisiensi BBM atau EV range 20%, pajak 10%, dan depresiasi 10%. Efisiensi BBM dan EV range memakai benchmark terpisah karena satuannya berbeda.

## Pengujian

```bash
php artisan test
```

Suite saat ini mencakup autentikasi, profil, akses role, halaman publik, batas maksimal tiga kendaraan, serta penyimpanan perbandingan.
