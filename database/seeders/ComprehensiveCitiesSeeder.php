<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\City;

class ComprehensiveCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏙️ Mengimpor data kota/kabupaten lengkap Indonesia...');

        // Clear existing cities
        City::truncate();

        // Import comprehensive city data
        $this->importAllCities();

        $totalCities = City::count();
        $this->command->info("✅ $totalCities kota/kabupaten berhasil diimpor!");
    }

    private function importAllCities()
    {
        $cities = [
            // DKI JAKARTA
            ['id' => '3101', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kabupaten', 'name' => 'Kepulauan Seribu'],
            ['id' => '3171', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Selatan'],
            ['id' => '3172', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Timur'],
            ['id' => '3173', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Pusat'],
            ['id' => '3174', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Barat'],
            ['id' => '3175', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Utara'],

            // JAWA BARAT
            ['id' => '3201', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Bogor'],
            ['id' => '3202', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Sukabumi'],
            ['id' => '3203', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Cianjur'],
            ['id' => '3204', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Bandung'],
            ['id' => '3205', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Garut'],
            ['id' => '3206', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Tasikmalaya'],
            ['id' => '3207', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Ciamis'],
            ['id' => '3208', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Kuningan'],
            ['id' => '3209', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Cirebon'],
            ['id' => '3210', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Majalengka'],
            ['id' => '3211', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Sumedang'],
            ['id' => '3212', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Indramayu'],
            ['id' => '3213', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Subang'],
            ['id' => '3214', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Purwakarta'],
            ['id' => '3215', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Karawang'],
            ['id' => '3216', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Bekasi'],
            ['id' => '3217', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Bandung Barat'],
            ['id' => '3218', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Pangandaran'],
            ['id' => '3271', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Bogor'],
            ['id' => '3272', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Sukabumi'],
            ['id' => '3273', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Bandung'],
            ['id' => '3274', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Cirebon'],
            ['id' => '3275', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Bekasi'],
            ['id' => '3276', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Depok'],
            ['id' => '3277', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Cimahi'],
            ['id' => '3278', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Tasikmalaya'],
            ['id' => '3279', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Banjar'],

            // JAWA TENGAH
            ['id' => '3301', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Cilacap'],
            ['id' => '3302', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Banyumas'],
            ['id' => '3303', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Purbalingga'],
            ['id' => '3304', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Banjarnegara'],
            ['id' => '3305', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Kebumen'],
            ['id' => '3306', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Purworejo'],
            ['id' => '3307', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Wonosobo'],
            ['id' => '3308', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Magelang'],
            ['id' => '3309', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Boyolali'],
            ['id' => '3310', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Klaten'],
            ['id' => '3311', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Sukoharjo'],
            ['id' => '3312', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Wonogiri'],
            ['id' => '3313', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Karanganyar'],
            ['id' => '3314', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Sragen'],
            ['id' => '3315', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Grobogan'],
            ['id' => '3316', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Blora'],
            ['id' => '3317', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Rembang'],
            ['id' => '3318', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Pati'],
            ['id' => '3319', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Kudus'],
            ['id' => '3320', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Jepara'],
            ['id' => '3321', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Demak'],
            ['id' => '3322', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Semarang'],
            ['id' => '3323', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Temanggung'],
            ['id' => '3324', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Kendal'],
            ['id' => '3325', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Batang'],
            ['id' => '3326', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Pekalongan'],
            ['id' => '3327', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Pemalang'],
            ['id' => '3328', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Tegal'],
            ['id' => '3329', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Brebes'],
            ['id' => '3371', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Magelang'],
            ['id' => '3372', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Surakarta'],
            ['id' => '3373', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Salatiga'],
            ['id' => '3374', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Semarang'],
            ['id' => '3375', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Pekalongan'],
            ['id' => '3376', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Tegal'],

            // JAWA TIMUR
            ['id' => '3501', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Pacitan'],
            ['id' => '3502', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Ponorogo'],
            ['id' => '3503', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Trenggalek'],
            ['id' => '3504', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Tulungagung'],
            ['id' => '3505', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Blitar'],
            ['id' => '3506', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Kediri'],
            ['id' => '3507', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Malang'],
            ['id' => '3508', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Lumajang'],
            ['id' => '3509', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Jember'],
            ['id' => '3510', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Banyuwangi'],
            ['id' => '3511', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Bondowoso'],
            ['id' => '3512', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Situbondo'],
            ['id' => '3513', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Probolinggo'],
            ['id' => '3514', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Pasuruan'],
            ['id' => '3515', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Sidoarjo'],
            ['id' => '3516', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Mojokerto'],
            ['id' => '3517', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Jombang'],
            ['id' => '3518', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Nganjuk'],
            ['id' => '3519', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Madiun'],
            ['id' => '3520', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Magetan'],
            ['id' => '3521', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Ngawi'],
            ['id' => '3522', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Bojonegoro'],
            ['id' => '3523', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Tuban'],
            ['id' => '3524', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Lamongan'],
            ['id' => '3525', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Gresik'],
            ['id' => '3526', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Bangkalan'],
            ['id' => '3527', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Sampang'],
            ['id' => '3528', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Pamekasan'],
            ['id' => '3529', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Sumenep'],
            ['id' => '3571', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Kediri'],
            ['id' => '3572', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Blitar'],
            ['id' => '3573', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Malang'],
            ['id' => '3574', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Probolinggo'],
            ['id' => '3575', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Pasuruan'],
            ['id' => '3576', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Mojokerto'],
            ['id' => '3577', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Madiun'],
            ['id' => '3578', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Surabaya'],
            ['id' => '3579', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Batu'],

            // BANTEN
            ['id' => '3601', 'province_id' => '36', 'province' => 'BANTEN', 'type' => 'Kabupaten', 'name' => 'Pandeglang'],
            ['id' => '3602', 'province_id' => '36', 'province' => 'BANTEN', 'type' => 'Kabupaten', 'name' => 'Lebak'],
            ['id' => '3603', 'province_id' => '36', 'province' => 'BANTEN', 'type' => 'Kabupaten', 'name' => 'Tangerang'],
            ['id' => '3604', 'province_id' => '36', 'province' => 'BANTEN', 'type' => 'Kabupaten', 'name' => 'Serang'],
            ['id' => '3671', 'province_id' => '36', 'province' => 'BANTEN', 'type' => 'Kota', 'name' => 'Tangerang'],
            ['id' => '3672', 'province_id' => '36', 'province' => 'BANTEN', 'type' => 'Kota', 'name' => 'Cilegon'],
            ['id' => '3673', 'province_id' => '36', 'province' => 'BANTEN', 'type' => 'Kota', 'name' => 'Serang'],
            ['id' => '3674', 'province_id' => '36', 'province' => 'BANTEN', 'type' => 'Kota', 'name' => 'Tangerang Selatan'],

            // BALI
            ['id' => '5101', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Jembrana'],
            ['id' => '5102', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Tabanan'],
            ['id' => '5103', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Badung'],
            ['id' => '5104', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Gianyar'],
            ['id' => '5105', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Klungkung'],
            ['id' => '5106', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Bangli'],
            ['id' => '5107', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Karangasem'],
            ['id' => '5108', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Buleleng'],
            ['id' => '5171', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kota', 'name' => 'Denpasar'],

            // NUSA TENGGARA BARAT
            ['id' => '5201', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Lombok Barat'],
            ['id' => '5202', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Lombok Tengah'],
            ['id' => '5203', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Lombok Timur'],
            ['id' => '5204', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Sumbawa'],
            ['id' => '5205', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Dompu'],
            ['id' => '5206', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Bima'],
            ['id' => '5207', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Sumbawa Barat'],
            ['id' => '5208', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Lombok Utara'],
            ['id' => '5271', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kota', 'name' => 'Mataram'],
            ['id' => '5272', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kota', 'name' => 'Bima'],

            // SUMATERA UTARA
            ['id' => '1201', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Nias'],
            ['id' => '1202', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Mandailing Natal'],
            ['id' => '1203', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Tapanuli Selatan'],
            ['id' => '1204', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Tapanuli Tengah'],
            ['id' => '1205', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Tapanuli Utara'],
            ['id' => '1206', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Toba Samosir'],
            ['id' => '1207', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Labuhan Batu'],
            ['id' => '1208', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Asahan'],
            ['id' => '1209', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Simalungun'],
            ['id' => '1210', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Dairi'],
            ['id' => '1211', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Karo'],
            ['id' => '1212', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Deli Serdang'],
            ['id' => '1213', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kabupaten', 'name' => 'Langkat'],
            ['id' => '1271', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kota', 'name' => 'Sibolga'],
            ['id' => '1272', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kota', 'name' => 'Tanjung Balai'],
            ['id' => '1273', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kota', 'name' => 'Pematang Siantar'],
            ['id' => '1274', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kota', 'name' => 'Tebing Tinggi'],
            ['id' => '1275', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kota', 'name' => 'Medan'],
            ['id' => '1276', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kota', 'name' => 'Binjai'],
            ['id' => '1277', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kota', 'name' => 'Padangsidimpuan'],
            ['id' => '1278', 'province_id' => '12', 'province' => 'SUMATERA UTARA', 'type' => 'Kota', 'name' => 'Gunungsitoli'],

            // SUMATERA BARAT
            ['id' => '1301', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Kepulauan Mentawai'],
            ['id' => '1302', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Pesisir Selatan'],
            ['id' => '1303', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Solok'],
            ['id' => '1304', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Sijunjung'],
            ['id' => '1305', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Tanah Datar'],
            ['id' => '1306', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Padang Pariaman'],
            ['id' => '1307', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Agam'],
            ['id' => '1308', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Lima Puluh Kota'],
            ['id' => '1309', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Pasaman'],
            ['id' => '1310', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Solok Selatan'],
            ['id' => '1311', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Dharmasraya'],
            ['id' => '1312', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kabupaten', 'name' => 'Pasaman Barat'],
            ['id' => '1371', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kota', 'name' => 'Padang'],
            ['id' => '1372', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kota', 'name' => 'Solok'],
            ['id' => '1373', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kota', 'name' => 'Sawahlunto'],
            ['id' => '1374', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kota', 'name' => 'Padang Panjang'],
            ['id' => '1375', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kota', 'name' => 'Bukittinggi'],
            ['id' => '1376', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kota', 'name' => 'Payakumbuh'],
            ['id' => '1377', 'province_id' => '13', 'province' => 'SUMATERA BARAT', 'type' => 'Kota', 'name' => 'Pariaman'],

            // RIAU
            ['id' => '1401', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Kuantan Singingi'],
            ['id' => '1402', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Indragiri Hulu'],
            ['id' => '1403', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Indragiri Hilir'],
            ['id' => '1404', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Pelalawan'],
            ['id' => '1405', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Siak'],
            ['id' => '1406', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Kampar'],
            ['id' => '1407', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Rokan Hulu'],
            ['id' => '1408', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Bengkalis'],
            ['id' => '1409', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Rokan Hilir'],
            ['id' => '1410', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kabupaten', 'name' => 'Kepulauan Meranti'],
            ['id' => '1471', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kota', 'name' => 'Pekanbaru'],
            ['id' => '1473', 'province_id' => '14', 'province' => 'RIAU', 'type' => 'Kota', 'name' => 'Dumai'],

            // KALIMANTAN BARAT
            ['id' => '6101', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Sambas'],
            ['id' => '6102', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Bengkayang'],
            ['id' => '6103', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Landak'],
            ['id' => '6104', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Mempawah'],
            ['id' => '6105', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Sanggau'],
            ['id' => '6106', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Ketapang'],
            ['id' => '6107', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Sintang'],
            ['id' => '6108', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Kapuas Hulu'],
            ['id' => '6109', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Sekadau'],
            ['id' => '6110', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Melawi'],
            ['id' => '6111', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Kayong Utara'],
            ['id' => '6112', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kabupaten', 'name' => 'Kubu Raya'],
            ['id' => '6171', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kota', 'name' => 'Pontianak'],
            ['id' => '6172', 'province_id' => '61', 'province' => 'KALIMANTAN BARAT', 'type' => 'Kota', 'name' => 'Singkawang'],

            // KALIMANTAN SELATAN
            ['id' => '6301', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Tanah Laut'],
            ['id' => '6302', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Kota Baru'],
            ['id' => '6303', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Banjar'],
            ['id' => '6304', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Barito Kuala'],
            ['id' => '6305', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Tapin'],
            ['id' => '6306', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Hulu Sungai Selatan'],
            ['id' => '6307', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Hulu Sungai Tengah'],
            ['id' => '6308', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Hulu Sungai Utara'],
            ['id' => '6309', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Tabalong'],
            ['id' => '6310', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Tanah Bumbu'],
            ['id' => '6311', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kabupaten', 'name' => 'Balangan'],
            ['id' => '6371', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kota', 'name' => 'Banjarmasin'],
            ['id' => '6372', 'province_id' => '63', 'province' => 'KALIMANTAN SELATAN', 'type' => 'Kota', 'name' => 'Banjar Baru'],

            // KALIMANTAN TIMUR
            ['id' => '6401', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kabupaten', 'name' => 'Paser'],
            ['id' => '6402', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kabupaten', 'name' => 'Kutai Barat'],
            ['id' => '6403', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kabupaten', 'name' => 'Kutai Kartanegara'],
            ['id' => '6404', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kabupaten', 'name' => 'Kutai Timur'],
            ['id' => '6405', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kabupaten', 'name' => 'Berau'],
            ['id' => '6409', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kabupaten', 'name' => 'Penajam Paser Utara'],
            ['id' => '6411', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kabupaten', 'name' => 'Mahakam Ulu'],
            ['id' => '6471', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kota', 'name' => 'Balikpapan'],
            ['id' => '6472', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kota', 'name' => 'Samarinda'],
            ['id' => '6474', 'province_id' => '64', 'province' => 'KALIMANTAN TIMUR', 'type' => 'Kota', 'name' => 'Bontang'],

            // SULAWESI SELATAN
            ['id' => '7301', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Kepulauan Selayar'],
            ['id' => '7302', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Bulukumba'],
            ['id' => '7303', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Bantaeng'],
            ['id' => '7304', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Jeneponto'],
            ['id' => '7305', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Takalar'],
            ['id' => '7306', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Gowa'],
            ['id' => '7307', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Sinjai'],
            ['id' => '7308', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Maros'],
            ['id' => '7309', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Pangkajene dan Kepulauan'],
            ['id' => '7310', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Barru'],
            ['id' => '7311', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Bone'],
            ['id' => '7312', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Soppeng'],
            ['id' => '7313', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Wajo'],
            ['id' => '7314', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Sidenreng Rappang'],
            ['id' => '7315', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Pinrang'],
            ['id' => '7316', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Enrekang'],
            ['id' => '7317', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Luwu'],
            ['id' => '7318', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Tana Toraja'],
            ['id' => '7322', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Luwu Utara'],
            ['id' => '7325', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Luwu Timur'],
            ['id' => '7326', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kabupaten', 'name' => 'Toraja Utara'],
            ['id' => '7371', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kota', 'name' => 'Makassar'],
            ['id' => '7372', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kota', 'name' => 'Parepare'],
            ['id' => '7373', 'province_id' => '73', 'province' => 'SULAWESI SELATAN', 'type' => 'Kota', 'name' => 'Palopo'],

            // PAPUA
            ['id' => '9401', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Merauke'],
            ['id' => '9402', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Jayawijaya'],
            ['id' => '9403', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Jayapura'],
            ['id' => '9404', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Nabire'],
            ['id' => '9408', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Kepulauan Yapen'],
            ['id' => '9409', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Biak Numfor'],
            ['id' => '9410', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Paniai'],
            ['id' => '9411', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Puncak Jaya'],
            ['id' => '9412', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Mimika'],
            ['id' => '9413', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Boven Digoel'],
            ['id' => '9414', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Mappi'],
            ['id' => '9415', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Asmat'],
            ['id' => '9416', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Yahukimo'],
            ['id' => '9417', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Pegunungan Bintang'],
            ['id' => '9418', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Tolikara'],
            ['id' => '9419', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Sarmi'],
            ['id' => '9420', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Keerom'],
            ['id' => '9426', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Waropen'],
            ['id' => '9427', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Supiori'],
            ['id' => '9428', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Mamberamo Raya'],
            ['id' => '9429', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Nduga'],
            ['id' => '9430', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Lanny Jaya'],
            ['id' => '9431', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Mamberamo Tengah'],
            ['id' => '9432', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Yalimo'],
            ['id' => '9433', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Puncak'],
            ['id' => '9434', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Dogiyai'],
            ['id' => '9435', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Intan Jaya'],
            ['id' => '9436', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kabupaten', 'name' => 'Deiyai'],
            ['id' => '9471', 'province_id' => '94', 'province' => 'PAPUA', 'type' => 'Kota', 'name' => 'Jayapura'],
        ];

        // Insert in batches
        $batch = [];
        $count = 0;

        foreach ($cities as $city) {
            $batch[] = [
                'city_id' => $city['id'],
                'province_id' => $city['province_id'],
                'province' => $city['province'],
                'type' => $city['type'],
                'city_name' => $city['name'],
                'postal_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $count++;

            // Insert in batches of 50
            if (count($batch) >= 50) {
                City::insert($batch);
                $batch = [];
                $this->command->info("📍 Diimpor: $count kota");
            }
        }

        // Insert remaining
        if (!empty($batch)) {
            City::insert($batch);
        }

        $this->command->info("✅ $count kota/kabupaten berhasil diimpor");
    }
}