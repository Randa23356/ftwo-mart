# FTWO Mart

Multi-vendor e-commerce marketplace untuk produk batik & fashion, dibangun dengan Laravel 12.

🌐 **Domain**: [https://mart.ftwodev.id](https://mart.ftwodev.id)

---

## Fitur

### 🛍️ Marketplace
- Katalog produk dengan kategori & filter harga
- Pencarian produk real-time (AJAX autocomplete)
- Detail produk dengan multiple gambar
- Sistem varian produk (ukuran, warna, dll) dengan harga & stok per kombinasi
- Keranjang belanja dengan seleksi item
- Checkout dengan pilihan pengiriman
- Beli langsung (Buy Now)
- COD (Cash on Delivery) dengan konfirmasi signed URL
- Rating & ulasan produk dengan balasan admin/operator

### 💳 Pembayaran
- **Midtrans Payment Gateway**: E-wallet (GoPay, OVO, DANA, LinkAja, ShopeePay), QRIS, Bank Transfer (BCA, BNI, BRI, Mandiri), Credit Card
- **COD**: Konfirmasi pengiriman via halaman signed URL
- Auto-cancel pesanan yang tidak dibayar (kadaluarsa)
- Extend masa tenggat pembayaran

### 🚚 Pengiriman
- Integrasi RajaOngkir (JNE, POS, TIKI, SiCepat, J&T, AnterAja, Ninja, BinderByte)
- Kalkulator ongkir real-time
- Multiplier biaya per provinsi
- Base cost + biaya per kg
- Tracking nomor resi
- **Courier QR Scan**: Kode QR per pesanan untuk konfirmasi pengiriman kurir

### 💬 Chat / Customer Service
- Multi-role chat (Admin, Operator, Seller, User, Guest)
- Visibilitas terkontrol per role
- Status read/unread per role
- User presence (online/offline)
- Close/reopen percakapan
- Tandai penting (important)
- Restore & force delete
- Balasan email dari admin ke guest

### ⭐ Rating & Review
- Rating per produk per pesanan
- Balasan dari admin/operator
- kelola rating di admin panel

### 💰 Sistem Seller & Komisi
- Registrasi seller dengan upload dokumen (KTP, NIB, NPWP, Rekening)
- Approval workflow oleh admin
- Dashboard seller (produk, pesanan, earnings, withdraw)
- Komisi platform per item (dihitung otomatis saat delivered)
- Pencairan dana (withdrawal) dengan approval admin
- Tracking saldo, earnings, dan penarikan

### 🔁 Sistem Refund
- Request refund oleh user
- Approval/reject oleh admin
- Workflow: pending → approved → return shipped → completed / rejected
- Upload bukti pengembalian

### 📦 Manajemen Pesanan
- Status pesanan: pending → processing → ready → shipped → delivered → cancelled
- Audit trail status (order status history)
- Print invoice (PDF via DomPDF)
- Trash / restore / force delete
- Auto-complete pesanan shipped setelah 3 hari

### 👥 Multi-Role System (4 Role)
| Role | Akses |
|------|-------|
| **Admin** | Full control — produk, kategori, pesanan, user, seller, withdraw, refund, pengaturan website, laporan |
| **Operator** | Dashboard, kelola pesanan, kelola produk (CRUD), print invoice |
| **Seller** | Dashboard seller, produk (CRUD), pesanan, earnings, withdraw |
| **User** | Belanja, keranjang, checkout, riwayat pesanan, chat, rating, refund |

### 🔐 Autentikasi & Otorisasi
- Login / Register
- Google OAuth (Laravel Socialite)
- Email verification
- Spatie Laravel Permission (22 permissions)
- Profile publik dengan slug

### 🎨 Website CMS
- Logo, hero image, about page
- Informasi kontak
- Halaman login & register yang bisa dikustomisasi
- Pengaturan biaya pengiriman & warehouse
- Inline settings update

### 📊 Dashboard & Laporan
- Dashboard admin dengan statistik
- Dashboard seller
- Dashboard operator
- Laporan penjualan, produk terlaris, metode pembayaran

### 🔧 Fitur Teknis Lainnya
- Soft delete (produk, kategori, pesanan, percakapan)
- Trash → Restore → Force delete
- Shared hosting storage fallback (tanpa symlink)
- Scheduled tasks: auto-cancel expired orders, auto-complete delivered orders
- Staff product blocking (produk staff tidak bisa dibeli oleh staff)
- User presence tracking (online/offline)
- Responsive UI (mobile-first)

---

## Teknologi

### Backend
| Package | Fungsi |
|---------|--------|
| Laravel 12 | Framework |
| Laravel Sanctum | API token auth |
| Laravel Breeze | Auth scaffolding |
| Laravel Socialite | Google OAuth |
| Spatie Permission | Roles & permissions |
| Midtrans PHP | Payment gateway |
| DomPDF | PDF generation (invoice) |
| Intervention Image | Image processing |
| Bacon QR Code | QR code (courier scan) |

### Frontend
| Package | Fungsi |
|---------|--------|
| Tailwind CSS 3 | CSS framework |
| Alpine.js 3 | Reactive JavaScript |
| Vite 5 | Build tool |
| Font Awesome | Icons |

### Database
- MySQL 8.0+ / PostgreSQL 13+
- 30+ tabel (users, products, orders, sellers, conversations, messages, ratings, refund_requests, dll)

---

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

Edit `.env`:
```env
APP_NAME="FTWO Mart"
APP_URL=https://mart.ftwodev.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ftwo_mart
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

### 4. Generate Key & Setup DB
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 5. Build & Run
```bash
npm run build
php artisan serve
npm run dev
```

---

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@ftwomart.com | password |
| Operator | operator@ftwomart.com | password |
| User | user@ftwomart.com | password |

---

## Struktur Project

```
ftwo-mart/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers (7)
│   │   │   ├── Operator/       # Operator controllers (2)
│   │   │   ├── Seller/         # Seller controllers (1)
│   │   │   └── *.php           # Public/Auth controllers (11)
│   │   └── Middleware/          # Custom middleware (4)
│   ├── Models/                 # Eloquent models (24)
│   └── Providers/
├── database/
│   ├── migrations/             # 70+ migrations
│   └── seeders/                # 11 seeders
├── resources/
│   └── views/
│       ├── admin/              # Admin panel views
│       ├── operator/           # Operator panel views
│       ├── seller/             # Seller panel views
│       ├── chat/               # Chat views
│       ├── orders/             # Order views
│       ├── components/         # Reusable Blade components
│       └── layouts/            # Layout templates
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes (payment webhook)
└── public/
```

---

## Scheduled Tasks

| Task | Frequency | Fungsi |
|------|-----------|--------|
| `orders:cancel-expired` | Setiap 30 menit | Auto-cancel pesanan yang belum dibayar |
| `orders:auto-complete` | Setiap hari | Auto-complete pesanan shipped > 3 hari |

---

## License

Distributed under the MIT License.

---

**FTWO Mart** — Multi-vendor e-commerce marketplace untuk produk batik 🇮🇩
