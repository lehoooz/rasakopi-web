# ☕ Rasakopi

Aplikasi manajemen kedai kopi berbasis web menggunakan **Laravel 13** dan **Tailwind CSS**. Mendukung 3 role pengguna: Admin, Kasir, dan Customer/Member.

---

## Persyaratan

Pastikan sudah terinstall:

- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL (atau bisa pakai SQLite)

---

## Instalasi

### 1. Clone repository

```bash
git clone https://github.com/username/rasakopi.git
cd rasakopi
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Salin file environment

```bash
cp .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Konfigurasi database

Buka file `.env`, lalu sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rasakopi
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan migrasi dan seeder

```bash
php artisan migrate
php artisan session:table
php artisan migrate
php artisan db:seed
```

### 7. Build assets

```bash
npm run build
```

### 8. Jalankan server

```bash
php artisan serve
```

Buka browser dan akses: **http://127.0.0.1:8000**

---

## Akun Default

Setelah menjalankan seeder, akun berikut tersedia:

| Role     | Email               | Password |
| -------- | ------------------- | -------- |
| Admin    | admin@rasakopi.com  | password |
| Kasir    | kasir@rasakopi.com  | password |
| Customer | member@rasakopi.com | password |

---

## Teknologi

- [Laravel 13](https://laravel.com)
- [Tailwind CSS 4](https://tailwindcss.com)
- [Maatwebsite Excel](https://laravel-excel.com)

jimmy
