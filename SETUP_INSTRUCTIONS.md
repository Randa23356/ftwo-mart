# Instruksi Setup Website Picia Bakery

## 🚀 Langkah Setup Lengkap

### 1. Clone dan Setup Awal
```bash
git clone <repository-url>
cd piciabakery
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi:
```env
APP_NAME="Picia Bakery"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=piciabakery
DB_USERNAME=root
DB_PASSWORD=your_mysql_password

# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_MERCHANT_ID=your_midtrans_merchant_id
```

### 4. Setup MySQL Database

#### Opsi A: Menggunakan MySQL Command Line
```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE piciabakery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Keluar dari MySQL
EXIT;
```

#### Opsi B: Menggunakan phpMyAdmin
1. Buka phpMyAdmin di browser
2. Klik "New" untuk membuat database baru
3. Masukkan nama database: `piciabakery`
4. Pilih collation: `utf8mb4_unicode_ci`
5. Klik "Create"

#### Opsi C: Menggunakan File SQL
1. Buka file `setup_mysql.sql` yang sudah dibuat
2. Copy isi file tersebut
3. Paste di MySQL Workbench atau phpMyAdmin
4. Jalankan query

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Setup Database
```bash
php artisan migrate:fresh
php artisan db:seed
```

### 7. Setup Storage
```bash
php artisan storage:link
```

### 8. Install Laravel Breeze (Authentication)
```bash
php artisan breeze:install
```

### 9. Install Frontend Dependencies (Opsional)
```bash
npm install
npm run build
```

### 10. Jalankan Aplikasi
```bash
php artisan serve
```

## 🔑 Akun Default

Setelah menjalankan seeder, tersedia akun default:

### Admin
- **Email**: admin@piciabakery.com
- **Password**: password
- **Role**: Full access (produk, kategori, user, laporan, pengaturan)

### Operator
- **Email**: operator@piciabakery.com
- **Password**: password
- **Role**: Kelola pesanan, update status

### User
- **Email**: user@piciabakery.com
- **Password**: password
- **Role**: Melihat produk, memesan, tracking pesanan

## 🌐 Akses Website

### Public Routes
- **Homepage**: http://localhost:8000/
- **Produk**: http://localhost:8000/products
- **About**: http://localhost:8000/about
- **Contact**: http://localhost:8000/contact

### Admin Panel
- **Dashboard**: http://localhost:8000/admin/dashboard
- **Produk**: http://localhost:8000/admin/products
- **Kategori**: http://localhost:8000/admin/categories
- **Pesanan**: http://localhost:8000/admin/orders
- **User**: http://localhost:8000/admin/users
- **Pengaturan**: http://localhost:8000/admin/settings
- **Laporan**: http://localhost:8000/admin/reports

### Operator Panel
- **Dashboard**: http://localhost:8000/operator/dashboard
- **Pesanan**: http://localhost:8000/operator/orders
- **Pesanan Pending**: http://localhost:8000/operator/orders/pending
- **Pesanan Diproses**: http://localhost:8000/operator/orders/processing
- **Pesanan Siap**: http://localhost:8000/operator/orders/ready
- **Pesanan Terkirim**: http://localhost:8000/operator/orders/delivered

## 💳 Setup Midtrans Payment Gateway

### 1. Daftar Akun Midtrans
- Kunjungi [Midtrans](https://midtrans.com)
- Buat akun merchant
- Dapatkan Server Key dan Client Key

### 2. Update Environment
```env
MIDTRANS_SERVER_KEY=your_actual_server_key
MIDTRANS_CLIENT_KEY=your_actual_client_key
MIDTRANS_IS_PRODUCTION=false  # Set true untuk production
MIDTRANS_MERCHANT_ID=your_merchant_id
```

### 3. Test Payment
- Buat pesanan dengan metode pembayaran Midtrans
- Gunakan kartu test yang disediakan Midtrans

## 🗄️ Struktur Database

### Tabel Utama
- `users` - User dengan role (user, operator, admin)
- `categories` - Kategori produk
- `products` - Produk bakery
- `orders` - Pesanan customer
- `order_items` - Item dalam pesanan
- `cart` - Keranjang belanja
- `payment_transactions` - Transaksi pembayaran
- `website_settings` - Pengaturan website

### Tabel Permission
- `permissions` - Permission yang tersedia
- `roles` - Role user
- `model_has_permissions` - Relasi permission dengan user
- `model_has_roles` - Relasi role dengan user
- `role_has_permissions` - Relasi permission dengan role

## 🔧 Fitur Utama

### 🛍️ E-commerce
- ✅ Katalog produk dengan kategori
- ✅ Keranjang belanja
- ✅ Sistem pemesanan online
- ✅ Status pesanan real-time
- ✅ Riwayat pesanan

### 💳 Sistem Pembayaran
- ✅ E-wallet (GoPay, OVO, DANA, dll)
- ✅ QRIS
- ✅ Transfer Bank
- ✅ Midtrans Payment Gateway

### 👥 Multi-role System
- ✅ **User**: Melihat produk, memesan, tracking pesanan
- ✅ **Operator**: Mengelola status pesanan, print invoice
- ✅ **Admin**: Full control (produk, kategori, user, laporan, pengaturan website)

### 🎨 Website Management
- ✅ Pengaturan konten website dinamis
- ✅ Upload dan edit gambar
- ✅ Pengaturan informasi kontak
- ✅ Pengaturan biaya pengiriman

### 📊 Dashboard & Laporan
- ✅ Dashboard admin dengan statistik
- ✅ Laporan penjualan
- ✅ Laporan produk terlaris
- ✅ Laporan metode pembayaran

## 🚨 Troubleshooting

### Error: "No such table: role_has_permissions"
```bash
php artisan migrate:fresh
php artisan db:seed
```

### Error: "Class 'Spatie\Permission\PermissionRegistrar' not found"
```bash
composer install
php artisan config:clear
```

### Error: "Failed to open stream: No such file or directory: routes/auth.php"
```bash
php artisan breeze:install
```

### Error: "npm: command not found"
- Install Node.js dari [nodejs.org](https://nodejs.org)
- Atau skip frontend build (aplikasi tetap bisa berjalan)

### Error: "SQLSTATE[HY000] [2002] Connection refused"
- Pastikan MySQL server sudah running
- Cek konfigurasi DB_HOST dan DB_PORT di .env
- Cek username dan password MySQL

### Error: "SQLSTATE[HY000] [1049] Unknown database"
- Buat database `piciabakery` terlebih dahulu
- Gunakan file `setup_mysql.sql` yang sudah disediakan

## 📱 Testing Aplikasi

### 1. Test User Flow
1. Buka homepage
2. Lihat katalog produk
3. Register/Login sebagai user
4. Tambah produk ke keranjang
5. Checkout dan buat pesanan
6. Pilih metode pembayaran
7. Lihat status pesanan

### 2. Test Admin Flow
1. Login sebagai admin
2. Kelola produk (tambah, edit, hapus)
3. Kelola kategori
4. Lihat dashboard dan laporan
5. Update pengaturan website

### 3. Test Operator Flow
1. Login sebagai operator
2. Lihat daftar pesanan
3. Update status pesanan
4. Print invoice pesanan

## 🚀 Deployment ke Production

### Checklist Production
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `MIDTRANS_IS_PRODUCTION=true`
- [ ] Setup SSL certificate
- [ ] Setup database backup
- [ ] Setup queue worker
- [ ] Setup cron jobs

### Server Requirements
- PHP 8.2+
- MySQL 8.0+ / PostgreSQL 13+
- Composer 2.0+
- Node.js 18+ (opsional)
- NPM 9+ (opsional)

---

**🎉 Website Picia Bakery siap digunakan dengan MySQL!**

Untuk dukungan teknis, silakan buat issue di repository ini atau hubungi tim development.
