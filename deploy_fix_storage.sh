#!/bin/bash

# Script untuk memperbaiki masalah storage di production
# Jalankan script ini di server production

echo "=== Memperbaiki Masalah Storage di Production ==="
echo ""

# 1. Pastikan kita di direktori project Laravel
if [ ! -f "artisan" ]; then
    echo "Error: File artisan tidak ditemukan. Pastikan Anda berada di direktori project Laravel."
    exit 1
fi

echo "1. Memeriksa environment..."
if [ ! -f ".env" ]; then
    echo "  Warning: File .env tidak ditemukan. Pastikan sudah dikonfigurasi."
else
    echo "  ✓ File .env ditemukan"
fi

echo ""
echo "2. Memeriksa storage link..."
if [ -L "public/storage" ]; then
    LINK_TARGET=$(readlink -f "public/storage")
    echo "  ✓ Storage link ada"
    echo "  Target: $LINK_TARGET"
    
    if [ -d "$LINK_TARGET" ]; then
        echo "  ✓ Target folder ada"
    else
        echo "  ✗ Target folder tidak ada: $LINK_TARGET"
        echo "  Akan membuat ulang storage link..."
    fi
else
    echo "  ✗ Storage link tidak ada"
    echo "  Akan membuat storage link..."
fi

echo ""
echo "3. Membersihkan cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo "  ✓ Cache dibersihkan"

echo ""
echo "4. Membuat/memperbarui storage link..."
# Hapus link lama jika ada
if [ -L "public/storage" ]; then
    rm -f "public/storage"
    echo "  ✓ Link lama dihapus"
fi

# Buat link baru
php artisan storage:link
echo "  ✓ Storage link dibuat"

echo ""
echo "5. Memeriksa permissions..."
STORAGE_PATH="storage"
PUBLIC_STORAGE="public/storage"

if [ -d "$STORAGE_PATH" ]; then
    # Set permissions untuk storage
    chmod -R 775 "$STORAGE_PATH"
    echo "  ✓ Permissions untuk storage diatur"
    
    # Set ownership jika perlu (sesuaikan dengan user web server)
    # chown -R www-data:www-data "$STORAGE_PATH"
    # echo "  ✓ Ownership diatur untuk www-data"
fi

if [ -d "$PUBLIC_STORAGE" ]; then
    chmod -R 775 "$PUBLIC_STORAGE"
    echo "  ✓ Permissions untuk public/storage diatur"
fi

echo ""
echo "6. Memeriksa isi folder website..."
if [ -d "storage/app/public/website" ]; then
    FILE_COUNT=$(ls -la storage/app/public/website/ | grep -E '\.(jpg|jpeg|png|gif)$' | wc -l)
    echo "  ✓ Folder website ada"
    echo "  Jumlah file gambar: $FILE_COUNT"
    
    # Tampilkan beberapa file
    echo "  Beberapa file:"
    ls -la storage/app/public/website/ | head -10
else
    echo "  ✗ Folder website tidak ada"
    mkdir -p storage/app/public/website
    echo "  ✓ Folder website dibuat"
fi

echo ""
echo "7. Memeriksa apakah file dapat diakses via web..."
# Buat file test jika tidak ada
TEST_FILE="storage/app/public/website/test_$(date +%s).txt"
echo "Test file created at $(date)" > "$TEST_FILE"
echo "  ✓ File test dibuat: $TEST_FILE"

if [ -L "public/storage" ]; then
    PUBLIC_TEST_FILE="public/storage/website/$(basename $TEST_FILE)"
    if [ -f "$PUBLIC_TEST_FILE" ]; then
        echo "  ✓ File test dapat diakses via public/storage"
        echo "  Content: $(cat $PUBLIC_TEST_FILE)"
    else
        echo "  ✗ File test TIDAK dapat diakses via public/storage"
        echo "  Periksa symbolic link dan permissions"
    fi
    # Hapus file test
    rm -f "$TEST_FILE"
    rm -f "$PUBLIC_TEST_FILE" 2>/dev/null
    echo "  ✓ File test dihapus"
fi

echo ""
echo "8. Menjalankan migrasi dan seeder jika perlu..."
# php artisan migrate --force
# php artisan db:seed --class=WebsiteSettingSeeder
echo "  (Skip) Migrasi dan seeder - jalankan manual jika perlu"

echo ""
echo "9. Memeriksa konfigurasi .env..."
if [ -f ".env" ]; then
    echo "  APP_URL: $(grep APP_URL .env || echo 'Tidak ditemukan')"
    echo "  APP_ENV: $(grep APP_ENV .env || echo 'Tidak ditemukan')"
    
    # Rekomendasi untuk production
    if grep -q "APP_ENV=local" ".env"; then
        echo "  ⚠️  Warning: APP_ENV=local di production. Ubah ke production"
    fi
    
    if ! grep -q "APP_URL=" ".env" || grep -q "APP_URL=http://localhost" ".env"; then
        echo "  ⚠️  Warning: APP_URL masih localhost. Ubah ke domain production"
    fi
fi

echo ""
echo "=== Ringkasan ==="
echo "1. Storage link telah dibuat/diperbarui"
echo "2. Cache telah dibersihkan"
echo "3. Permissions telah diatur"
echo "4. File test menunjukkan apakah storage berfungsi"
echo ""
echo "=== Langkah Manual untuk Production ==="
echo "1. Pastikan web server (nginx/apache) memiliki read access ke folder storage"
echo "2. Pastikan APP_URL di .env sesuai dengan domain production"
echo "3. Pastikan APP_ENV=production di .env"
echo "4. Restart web server jika diperlukan"
echo "5. Test dengan mengupload gambar baru melalui admin panel"
echo ""
echo "Script selesai."