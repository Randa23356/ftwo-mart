# Summary of Hero Image Fixes

## Masalah yang Diidentifikasi
1. **Gambar hero tidak muncul di production** - Kemungkinan karena masalah symbolic link storage atau konfigurasi URL
2. **Gambar lama tidak terhapus ketika diupload baru** - Menumpuk di folder storage/app/public/website

## Perbaikan yang Telah Dilakukan

### 1. Controller Improvements (WebsiteSettingController.php)
- Menambahkan logika untuk menghapus gambar lama sebelum menyimpan yang baru
- Menggunakan `ImageHelper` untuk konsistensi
- Menambahkan import `Illuminate\Support\Facades\Storage` dan `App\Helpers\ImageHelper`

### 2. Image Helper (app/Helpers/ImageHelper.php)
- `getImageUrl()`: Menghasilkan URL gambar yang konsisten untuk semua environment
- `deleteOldImage()`: Menghapus gambar lama dengan aman
- `storeImage()`: Menyimpan gambar baru dan mengembalikan path

### 3. Blade Directive (AppServiceProvider.php)
- Menambahkan directive `@imageUrl()` untuk memudahkan akses gambar
- Memperbarui view `home.blade.php` untuk menggunakan directive baru

### 4. Cleanup Command (app/Console/Commands/CleanupUnusedImages.php)
- Command `php artisan images:cleanup-website` untuk membersihkan gambar tidak terpakai
- Opsi `--dry-run` untuk melihat file mana yang akan dihapus tanpa benar-benar menghapus

### 5. Test Tools
- `TestImageController.php`: Controller untuk debugging akses gambar
- `check_storage.php`: Script diagnostic untuk memeriksa konfigurasi storage
- `deploy_fix_storage.sh`: Script bash untuk memperbaiki masalah di production

## Cara Menggunakan Perbaikan

### 1. Upload Gambar Baru
- Setelah perbaikan, ketika mengupload hero image baru melalui admin panel (`/admin/settings`), gambar lama akan otomatis dihapus

### 2. Membersihkan Gambar Lama
```bash
# Lihat file yang akan dihapus (dry run)
php artisan images:cleanup-website --dry-run

# Hapus gambar tidak terpakai
php artisan images:cleanup-website
```

### 3. Debugging di Production
```bash
# Jalankan script fix di server production
chmod +x deploy_fix_storage.sh
./deploy_fix_storage.sh

# Atau manual steps:
php artisan storage:link
php artisan cache:clear
chmod -R 775 storage
chmod -R 775 public/storage
```

### 4. Test Akses Gambar
- Buka `/test/images` untuk melihat status semua gambar
- Buka `/test/images/cleanup` untuk membersihkan gambar tidak terpakai

## Untuk Production Deployment

### Pastikan:
1. **Storage link berfungsi**: `php artisan storage:link`
2. **Permissions benar**: Web server memiliki read access ke folder storage
3. **APP_URL benar**: Di `.env`, `APP_URL` harus sesuai domain production
4. **APP_ENV=production**: Di `.env` untuk production

### Common Production Issues:
1. **Symbolic link broken**: Jalankan ulang `php artisan storage:link`
2. **Permission denied**: Set permissions dengan `chmod -R 775 storage public/storage`
3. **APP_URL salah**: Pastikan `APP_URL` di `.env` sesuai domain production
4. **Web server config**: Pastikan web server mengizinkan akses ke symbolic links

## Files yang Diubah:
1. `app/Http/Controllers/Admin/WebsiteSettingController.php`
2. `app/Models/WebsiteSetting.php` (hanya untuk referensi)
3. `app/Helpers/ImageHelper.php` (baru)
4. `app/Providers/AppServiceProvider.php`
5. `resources/views/home.blade.php`
6. `app/Console/Commands/CleanupUnusedImages.php` (baru)
7. `app/Http/Controllers/TestImageController.php` (baru)
8. `routes/web.php` (menambahkan test routes)

## Files Support (bisa dihapus setelah debugging):
1. `check_storage.php`
2. `deploy_fix_storage.sh`
3. `IMAGE_FIX_SUMMARY.md` (file ini)