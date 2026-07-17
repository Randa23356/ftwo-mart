# FTWO Mart

Website e-commerce multi-produk (bakery & batik) dengan fitur lengkap yang dibangun menggunakan Laravel 12.

🌐 **Domain**: [https://mart.ftwodev.id](https://mart.ftwodev.id)

## Fitur Utama

### 🛍️ E-commerce
- Katalog produk dengan kategori
- Keranjang belanja
- Sistem pemesanan online
- Status pesanan real-time
- Riwayat pesanan

### 💳 Sistem Pembayaran
- E-wallet (GoPay, OVO, DANA, dll)
- QRIS
- Transfer Bank
- Midtrans Payment Gateway

### 👥 Multi-role System
- **User**: Melihat produk, memesan, tracking pesanan
- **Operator**: Mengelola status pesanan, print invoice
- **Admin**: Full control (produk, kategori, user, laporan, pengaturan website)

### 🎨 Website Management
- Pengaturan konten website dinamis
- Upload dan edit gambar
- Pengaturan informasi kontak
- Pengaturan biaya pengiriman

### 📊 Dashboard & Laporan
- Dashboard admin dengan statistik
- Laporan penjualan
- Laporan produk terlaris
- Laporan metode pembayaran

## Teknologi yang Digunakan

- **Backend**: Laravel 12
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze
- **Authorization**: Spatie Laravel Permission
- **Payment Gateway**: Midtrans
- **Image Processing**: Intervention Image
- **PDF Generation**: DomPDF
- **Frontend**: Blade Templates + Tailwind CSS

## Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd ftwo-mart
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi:
```env
APP_NAME="FTWO Mart"
APP_URL=https://mart.ftwodev.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=piciabakery
DB_USERNAME=root
DB_PASSWORD=

# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_MERCHANT_ID=your_midtrans_merchant_id
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### 6. Setup Storage
```bash
php artisan storage:link
```

### 7. Install Laravel Breeze
```bash
php artisan breeze:install
npm install
npm run build
```

### 8. Run Application
```bash
php artisan serve
npm run dev
```

## Akun Default

Setelah menjalankan seeder, tersedia akun default:

### Admin
- Email: admin@piciabakery.com
- Password: password

### Operator
- Email: operator@piciabakery.com
- Password: password

### User
- Email: user@piciabakery.com
- Password: password

## Struktur Database

### Tabel Utama
- `users` - User dengan role (user, operator, admin)
- `categories` - Kategori produk
- `products` - Semua produk
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

## API Endpoints

### Public Routes
- `GET /` - Homepage
- `GET /products` - Katalog produk
- `GET /products/{slug}` - Detail produk
- `GET /about` - Halaman about
- `GET /contact` - Halaman kontak

### Protected Routes (Auth Required)
- `GET /cart` - Keranjang belanja
- `POST /cart/add` - Tambah ke keranjang
- `GET /checkout` - Halaman checkout
- `POST /orders` - Buat pesanan
- `GET /orders` - Riwayat pesanan

### Admin Routes
- `GET /admin/dashboard` - Dashboard admin
- `GET /admin/products` - Kelola produk
- `GET /admin/categories` - Kelola kategori
- `GET /admin/orders` - Kelola pesanan
- `GET /admin/users` - Kelola user
- `GET /admin/settings` - Pengaturan website
- `GET /admin/reports` - Laporan

### Operator Routes
- `GET /operator/dashboard` - Dashboard operator
- `GET /operator/orders` - Kelola pesanan
- `GET /operator/orders/pending` - Pesanan pending
- `GET /operator/orders/processing` - Pesanan diproses
- `GET /operator/orders/ready` - Pesanan siap
- `GET /operator/orders/delivered` - Pesanan terkirim

## Fitur Pembayaran

### Midtrans Integration
Website ini terintegrasi dengan Midtrans untuk berbagai metode pembayaran:

1. **E-wallet**: GoPay, OVO, DANA, LinkAja, ShopeePay
2. **QRIS**: Pembayaran dengan scan QR
3. **Bank Transfer**: BCA, BNI, BRI, Mandiri
4. **Credit Card**: Visa, Mastercard, JCB

### Setup Midtrans
1. Daftar akun di [Midtrans](https://midtrans.com)
2. Dapatkan Server Key dan Client Key
3. Update file `.env` dengan key yang didapat
4. Test dengan sandbox mode terlebih dahulu

## Deployment

### Production Checklist
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
- Node.js 18+
- NPM 9+

## Contributing

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Support

Untuk dukungan teknis, silakan buat issue di repository ini atau hubungi tim development.

---

**FTWO Mart** - Website E-commerce Multi-produk dengan Fitur Lengkap 🚀
