# Tikona — Coffee Ordering Platform

Tikona adalah platform coffee shop sederhana yang dibuat dengan Laravel untuk mendukung proses pemesanan kopi secara **dine-in** maupun **takeaway**.

Project ini dibuat sebagai project portfolio sekaligus simulasi aplikasi coffee ordering yang memiliki katalog produk, keranjang sementara, transaksi, pembayaran Midtrans, autentikasi pengguna, dan review produk.

## ✨ Fitur Utama

- Landing / home page
- Product browsing
- Product detail
- Pemesanan **Dine In** dan **Takeaway**
- Cart menggunakan Laravel Session
- Order confirmation
- Transaction dan transaction details
- Integrasi pembayaran Midtrans
- Login / register
- Google Authentication
- Product review
- REST API untuk resource aplikasi
- Admin API untuk pengelolaan produk, kategori, transaksi, dan pembayaran

> **Catatan:** Project ini sengaja dibuat sederhana dan **tidak menggunakan nomor meja/table number**. Database Tikona juga tidak memiliki kolom tersebut.

## 🛠️ Tech Stack

- PHP
- Laravel
- MySQL
- Blade
- Tailwind CSS
- Laravel Session
- Laravel Sanctum/API
- Google OAuth
- Midtrans

## 📁 Struktur Database

Tikona menggunakan 7 tabel utama:

```text
users
categories
products
transactions
transaction_details
payments
reviews
```

Relasi utamanya:

```text
User
 ├── 1:N Transactions
 └── 1:N Reviews

Category
 └── 1:N Products

Product
 ├── N:1 Category
 ├── 1:N TransactionDetails
 └── 1:N Reviews

Transaction
 ├── N:1 User
 ├── 1:N TransactionDetails
 └── 1:1 Payment

TransactionDetail
 ├── N:1 Transaction
 └── N:1 Product

Payment
 └── 1:1 Transaction

Review
 ├── N:1 User
 └── N:1 Product
```

Struktur database ini mendukung katalog produk, order dine-in/takeaway, detail item pada order, pembayaran Midtrans, dan review produk.

## 🚀 Installation

Ikuti langkah berikut untuk menjalankan project Tikona di local environment.

### 1. Clone Repository

Clone repository GitHub:

```bash
git clone <URL_REPOSITORY_TIKONA>
cd <NAMA_FOLDER_PROJECT>
```

Ganti `<URL_REPOSITORY_TIKONA>` dengan URL repository GitHub Tikona.

### 2. Install PHP Dependencies

Pastikan Composer sudah terinstall, kemudian jalankan:

```bash
composer install
```

### 3. Install Frontend Dependencies

Pastikan Node.js dan npm sudah terinstall:

```bash
npm install
```

### 4. Buat File `.env`

Laravel membutuhkan file `.env` untuk konfigurasi environment.

Salin `.env.example`:

**Windows PowerShell / CMD:**

```bash
copy .env.example .env
```

**Linux / macOS:**

```bash
cp .env.example .env
```

Kemudian generate application key:

```bash
php artisan key:generate
```

> Jangan meng-upload file `.env` ke GitHub. File tersebut dapat berisi credential database, Google OAuth, dan Midtrans.

---

# ⚙️ Konfigurasi `.env`

Konfigurasi utama Tikona yang perlu diisi adalah:

1. Database
2. Google Authentication
3. Midtrans

Selain itu, pastikan konfigurasi Laravel dasar seperti `APP_URL` dan `APP_KEY` tersedia.

## 1. Database

Buat database MySQL terlebih dahulu, misalnya:

```sql
CREATE DATABASE tikona;
```

Kemudian isi bagian database pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tikona
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan:

- `DB_DATABASE` dengan nama database yang dibuat
- `DB_USERNAME` dengan username MySQL
- `DB_PASSWORD` dengan password MySQL

Contoh jika menggunakan XAMPP dengan MySQL default:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tikona
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL menggunakan password, isi `DB_PASSWORD` sesuai konfigurasi MySQL.

---

## 2. Google Authentication

Tikona menggunakan Google Authentication sehingga diperlukan OAuth credentials dari Google Cloud Console.

Secara umum, credentials yang diperlukan adalah:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

### Membuat Google OAuth Credentials

1. Buka Google Cloud Console.
2. Buat atau pilih sebuah Google Cloud Project.
3. Aktifkan konfigurasi OAuth yang diperlukan.
4. Buat OAuth Client ID.
5. Pilih application type yang sesuai dengan konfigurasi project.
6. Masukkan **Authorized Redirect URI** sesuai callback Google yang digunakan oleh project Tikona.
7. Salin Client ID dan Client Secret ke `.env`.

Contoh:

```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/your-google-callback
```

> **Penting:** Nama environment variable dan callback URI harus mengikuti implementasi Google Authentication yang terdapat di repository. Jika `.env.example` project sudah menyediakan nama variable tertentu, gunakan nama tersebut dan jangan membuat nama variable baru tanpa menyesuaikan konfigurasi aplikasi.

Jangan membagikan `GOOGLE_CLIENT_SECRET` ke publik atau memasukkannya ke repository GitHub.

---

## 3. Midtrans

Tikona menggunakan Midtrans untuk payment.

Untuk development, gunakan **Midtrans Sandbox**, bukan credential production.

Tambahkan credential Midtrans pada `.env` sesuai variable yang digunakan oleh project, misalnya:

```env
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false
```

Isi:

```env
MIDTRANS_SERVER_KEY=your-midtrans-server-key
MIDTRANS_CLIENT_KEY=your-midtrans-client-key
MIDTRANS_IS_PRODUCTION=false
```

### Mendapatkan Credential Midtrans

1. Buat akun pada Midtrans.
2. Masuk ke dashboard Midtrans.
3. Gunakan environment **Sandbox** untuk development.
4. Ambil Server Key dan Client Key.
5. Masukkan credential tersebut ke `.env`.
6. Pastikan mode production tidak digunakan ketika menjalankan project secara lokal.

> Nama environment variable harus disesuaikan dengan konfigurasi Midtrans yang digunakan oleh repository. Gunakan `.env.example` sebagai acuan utama.

---

# 🗄️ Database Migration & Seeder

Setelah `.env` selesai dikonfigurasi dan database sudah dibuat, jalankan migration:

```bash
php artisan migrate
```

Kemudian jalankan seeder:

```bash
php artisan db:seed
```

Atau untuk setup awal yang langsung menjalankan migration sekaligus seeder:

```bash
php artisan migrate --seed
```

Seeder project menyediakan data awal untuk development/testing, termasuk data user, kategori, produk, transaksi, review, dan payment sesuai struktur database Tikona.

Jika ingin **menghapus seluruh data database dan membuat ulang dari awal**, gunakan:

```bash
php artisan migrate:fresh --seed
```

> `migrate:fresh --seed` akan menghapus seluruh tabel dan data pada database yang digunakan. Jangan menjalankan command ini pada database production.

## 👤 Data User Seeder

Seeder Tikona dirancang dengan user awal yang terdiri dari:

- Admin / staff
- Customer

Role user pada database menggunakan:

```text
admin
customer
```

Gunakan credential yang tersedia pada seeder untuk login ke akun development.

> Jika credential default diubah pada source code seeder, gunakan credential yang terdapat pada file seeder terbaru di repository.

---

# 🖼️ Storage

Jika project menggunakan file yang disimpan pada Laravel public storage, buat symbolic link:

```bash
php artisan storage:link
```

Hal ini diperlukan agar file pada `storage/app/public` dapat diakses melalui public directory.

---

# 🎨 Build Frontend Assets

Untuk development, jalankan:

```bash
npm run dev
```

Jika ingin melakukan build production:

```bash
npm run build
```

Biarkan Vite berjalan pada terminal terpisah ketika menggunakan:

```bash
npm run dev
```

---

# ▶️ Menjalankan Laravel

Setelah seluruh konfigurasi selesai, jalankan Laravel:

```bash
php artisan serve
```

Secara default aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

atau:

```text
http://localhost:8000
```

Jika menggunakan `npm run dev`, jalankan keduanya pada terminal terpisah:

**Terminal 1**

```bash
php artisan serve
```

**Terminal 2**

```bash
npm run dev
```

---

# 🔄 Urutan Instalasi Singkat

Jika ingin menjalankan project dari awal, urutan command yang direkomendasikan:

```bash
git clone <URL_REPOSITORY_TIKONA>
cd <NAMA_FOLDER_PROJECT>

composer install
npm install

copy .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan storage:link

npm run dev
```

Kemudian pada terminal lain:

```bash
php artisan serve
```

Buka:

```text
http://localhost:8000
```

> Pastikan konfigurasi `.env` sudah selesai sebelum menjalankan migration dan fitur yang membutuhkan database, Google OAuth, atau Midtrans.

---

# 🧪 API

Tikona juga menyediakan REST API.

Beberapa endpoint utama:

```http
POST   /api/register
POST   /api/login
POST   /api/logout
GET    /api/me

GET    /api/products
GET    /api/products/{product}

GET    /api/categories
GET    /api/categories/{category}

GET    /api/transactions
POST   /api/transactions
GET    /api/transactions/{transaction}

POST   /api/payments
GET    /api/payments/{payment}

GET    /api/products/{product}/reviews
POST   /api/products/{product}/reviews

PUT    /api/reviews/{review}
DELETE /api/reviews/{review}
```

Endpoint admin tersedia untuk pengelolaan:

```http
POST   /api/admin/products
PUT    /api/admin/products/{product}
DELETE /api/admin/products/{product}

POST   /api/admin/categories
PUT    /api/admin/categories/{category}
DELETE /api/admin/categories/{category}

GET    /api/admin/transactions
GET    /api/admin/transactions/{transaction}
PUT    /api/admin/transactions/{transaction}/status

GET    /api/admin/payments/{payment}
```

Daftar route dapat diperiksa dengan:

```bash
php artisan route:list
```

---

# 🛒 Order Flow

Tikona memiliki dua cara utama untuk memulai order.

### Flow 1 — Mulai dari Order

```text
/order
   ↓
Choose Dine In / Takeaway
   ↓
/order/products
   ↓
Add / Update / Remove Cart
   ↓
/order/review
   ↓
Confirm Order
   ↓
Transaction
   ↓
Transaction Details
   ↓
Payment
   ↓
Success
```

### Flow 2 — Mulai dari Product

```text
/products
   ↓
/products/{product}
   ↓
Order Now
   ↓
/order
   ↓
Product masuk ke cart
   ↓
Choose Dine In / Takeaway
   ↓
Review
   ↓
Confirm
   ↓
Transaction
```

Cart disimpan sementara menggunakan Laravel Session sehingga project tidak membutuhkan tabel atau model `Cart`.

---

# 🔐 Security Notes

Project menerapkan beberapa prinsip keamanan Laravel, antara lain:

- Validasi input
- Authentication
- Authorization
- CSRF protection pada Web request
- Mass assignment protection
- Menghindari raw SQL dengan input user
- Harga checkout diambil dari database, bukan dipercaya dari browser
- Pengecekan ketersediaan produk sebelum checkout
- User hanya dapat mengakses transaction yang sesuai dengan hak aksesnya
- Database transaction ketika membuat transaction, transaction details, dan payment
- Password tidak disimpan dalam bentuk plain text

Jangan memasukkan credential berikut ke Git:

```text
.env
Google Client Secret
Midtrans Server Key
Database password
APP_KEY
```

Pastikan file `.env` tetap berada di `.gitignore`.

---

# 🧹 Troubleshooting

## Error koneksi database

Pastikan:

1. MySQL sedang berjalan.
2. Database `tikona` sudah dibuat.
3. `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` benar.
4. Jalankan kembali:

```bash
php artisan config:clear
```

Jika diperlukan:

```bash
php artisan cache:clear
```

## Setelah mengubah `.env`, konfigurasi belum berubah

Jalankan:

```bash
php artisan config:clear
```

Kemudian coba kembali menjalankan aplikasi.

## Migration gagal

Pastikan database sudah dibuat dan konfigurasi `.env` benar.

Untuk development, jika ingin membuat database dari awal:

```bash
php artisan migrate:fresh --seed
```

## Storage / gambar tidak muncul

Jalankan:

```bash
php artisan storage:link
```

## Frontend tidak ter-load dengan benar

Pastikan:

```bash
npm install
npm run dev
```

sedang berjalan.

---

# 📌 Development Notes

Tikona memisahkan Web Flow dan API Flow.

Web:

```text
Browser
  ↓
web.php
  ↓
Web Controller
  ↓
Model
  ↓
Database
```

API:

```text
API Client
  ↓
api.php
  ↓
API Controller
  ↓
Model
  ↓
Database
```

Blade tidak perlu memanggil API Laravel sendiri hanya untuk mengambil data dari database jika Web Controller dapat menggunakan Model secara langsung.

Untuk proses checkout, transaction baru dibuat ketika user melakukan confirmation. Data cart disimpan di Session, sedangkan transaction, transaction details, dan payment disimpan di database.

---

# 📚 Project Structure

Struktur utama project:

```text
app/
├── Http/
│   └── Controllers/
│       └── Api/
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Product.php
│   ├── Transaction.php
│   ├── TransactionDetail.php
│   ├── Payment.php
│   └── Review.php

database/
├── migrations/
└── seeders/

resources/
└── views/

routes/
├── web.php
└── api.php
```

## 📄 License

Project ini dibuat untuk kebutuhan pembelajaran dan portfolio.
