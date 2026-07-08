# Deployment Guide - ftwodev.id/mart

## 🚀 Langkah-langkah Deployment ke Hosting

### 1. Persiapan File untuk Upload

**File yang perlu di-upload ke hosting:**
- Semua file kecuali yang di-ignore oleh .gitignore
- Pastikan upload folder: `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`, `artisan`, `composer.json`, `composer.lock`

**File yang TIDAK perlu di-upload:**
- `.env` (akan dibuat baru di hosting)
- `node_modules/`
- `.git/`
- `.DS_Store`
- `tests/`

### 2. Konfigurasi Environment di Hosting

Buat file `.env` di hosting dengan konfigurasi berikut:

```env
APP_NAME="Picia Bakery"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ftwodev.id/mart

# Database Configuration (sesuaikan dengan hosting)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ftwo_mart
DB_USERNAME=ftwo_mart_user
DB_PASSWORD=your_database_password

# Midtrans Configuration (sesuaikan dengan production)
MIDTRANS_SERVER_KEY=your_production_server_key
MIDTRANS_CLIENT_KEY=your_production_client_key
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_MERCHANT_ID=your_merchant_id

# RajaOngkir Configuration
RAJAONGKIR_API_KEY=your_rajaongkir_api_key
RAJAONGKIR_ACCOUNT_TYPE=starter

# Security
APP_KEY=generate_with_artisan_key_generate
```

### 3. Setup Database di Hosting

1. **Buat database melalui cPanel/phpMyAdmin:**
   - Login ke cPanel
   - Buka MySQL Database Wizard
   - Buat database: `ftwo_mart`
   - Buat user database dengan password yang kuat
   - Grant privileges user ke database

2. **Import database structure:**
   - **Opsi A (via phpMyAdmin):**
     - Buka phpMyAdmin di local
     - Export database `piciabakery` ke file SQL
     - Upload ke phpMyAdmin hosting dan import
   
   - **Opsi B (via SSH jika tersedia):**
     ```bash
     # Export dari local
     mysqldump -u root -p piciabakery > backup.sql
     
     # Import di hosting
     mysql -u ftwo_mart_user -p ftwo_mart < backup.sql
     ```
   
   - **Opsi C (Jalankan migrations di hosting):**
     - Jika tidak ingin import database, jalankan migrations di hosting:
     ```bash
     php artisan migrate --force
     php artisan db:seed --force
     ```

### 4. Setup Permissions

Jalankan perintah berikut di hosting (via SSH atau File Manager):

```bash
# Set storage dan cache directories writable
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Set ownership jika menggunakan SSH
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 5. Install Dependencies & Optimize

Jika hosting mengizinkan SSH access:

```bash
# Install composer dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Clear dan cache config
php artisan config:clear
php artisan config:cache

# Clear dan cache routes
php artisan route:clear
php artisan route:cache

# Clear dan cache views
php artisan view:clear
php artisan view:cache

# Optimize application
php artisan optimize

# Create storage link
php artisan storage:link

# Run migrations
php artisan migrate --force

# Run seeders (opsional)
php artisan db:seed --force
```

### 6. Build Frontend Assets (Jika diperlukan)

```bash
npm install
npm run build
```

### 7. Konfigurasi Web Server

#### Untuk Apache (.htaccess sudah ada di public/)
Pastikan document root mengarah ke folder `public/` bukan root project.

#### Untuk Nginx
Tambahkan konfigurasi berikut:

```nginx
server {
    listen 80;
    server_name ftwodev.id;
    root /path/to/your/project/public;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 8. Setup Cron Jobs (Opsional)

Untuk task scheduling, tambahkan cron job:

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 9. Setup Queue Worker (Opsional)

Jika menggunakan queue untuk background jobs:

```bash
php artisan queue:work --tries=3 --timeout=90
```

### 10. Security Checklist

- [ ] `APP_DEBUG=false` di .env
- [ ] `APP_ENV=production` di .env
- [ ] SSL certificate terinstall
- [ ] File permissions sudah benar
- [ ] Database password kuat
- [ ] Midtrans production keys
- [ ] Backup database terjadwal
- [ ] Firewall aktif

### 11. Testing Setelah Deployment

1. **Test Homepage:** Buka https://ftwodev.id/mart
2. **Test Login:** Coba login dengan akun admin
3. **Test Database:** Coba tambah produk/order
4. **Test Payment:** Test payment gateway
5. **Test Storage:** Test upload gambar

### 12. Troubleshooting

#### Error 500
- Cek file permissions storage/ dan bootstrap/cache/
- Pastikan .env sudah dibuat dengan benar
- Cek log di storage/logs/laravel.log

#### Database Connection Error
- Verifikasi kredensial database di .env
- Pastikan database sudah dibuat di hosting
- Cek database user privileges

#### Storage Link Error
- Jalankan `php artisan storage:link`
- Pastikan folder public/storage ada

#### Asset 404
- Jalankan `npm run build` untuk frontend assets
- Pastikan APP_URL di .env sesuai dengan domain

### 13. Maintenance Mode

Untuk maintenance mode:

```bash
# Aktifkan maintenance mode
php artisan down

# Matikan maintenance mode
php artisan up
```

### 14. Backup Strategy

Setup backup otomatis:
- Database backup harian
- File storage backup mingguan
- Simpan backup di lokasi terpisah

---

**📞 Kontak Support**
Jika mengalami masalah, hubungi tim technical support hosting atau buka issue di repository.
