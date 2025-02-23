# Fleet Management System

## 📌 Langkah-langkah Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek ini di lingkungan lokal Anda.

### 1️⃣ Clone Repository
Clone repository dari GitHub menggunakan perintah berikut:
```
git clone <URL_REPOSITORY>
cd <NAMA_FOLDER_PROYEK>
```

### 2️⃣ Instalasi Dependensi
Jalankan perintah berikut untuk menginstal dependensi yang diperlukan:
```
npm install
composer install
```

### 3️⃣ Konfigurasi Environment
Salin file `.env.example` menjadi `.env` dan atur konfigurasi sesuai kebutuhan.
Setelah itu, jalankan perintah berikut untuk membersihkan cache konfigurasi:
```
php artisan config:clear
php artisan optimize:clear
```

### 4️⃣ Generate Application Key
Jalankan perintah berikut untuk menghasilkan application key:
```
php artisan key:generate
```

### 5️⃣ Migrasi dan Seeding Database
Jalankan migrasi dan seeding database menggunakan perintah berikut:
```
php artisan migrate
php artisan db:seed --class=DatabaseSeeder
```

### 6️⃣ Menjalankan Aplikasi
Untuk menjalankan aplikasi, jalankan perintah berikut:
```
npm run dev
php artisan serve
```

Aplikasi Anda sekarang siap digunakan! 🚀
