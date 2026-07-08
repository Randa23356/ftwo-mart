<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\City;

class IndonesiaWilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌏 Mengunduh data wilayah Indonesia...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        City::truncate();
        Province::truncate();

        try {
            // Seed provinces first
            $this->seedProvinces();
            
            // Then seed cities
            $this->seedCities();

            $this->command->info('✅ Data wilayah Indonesia berhasil diimpor!');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Error: ' . $e->getMessage());
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function seedProvinces()
    {
        $this->command->info('📍 Mengimpor data provinsi...');

        // Data provinsi Indonesia (34 provinsi)
        $provinces = [
            ['id' => '11', 'name' => 'ACEH'],
            ['id' => '12', 'name' => 'SUMATERA UTARA'],
            ['id' => '13', 'name' => 'SUMATERA BARAT'],
            ['id' => '14', 'name' => 'RIAU'],
            ['id' => '15', 'name' => 'JAMBI'],
            ['id' => '16', 'name' => 'SUMATERA SELATAN'],
            ['id' => '17', 'name' => 'BENGKULU'],
            ['id' => '18', 'name' => 'LAMPUNG'],
            ['id' => '19', 'name' => 'KEPULAUAN BANGKA BELITUNG'],
            ['id' => '21', 'name' => 'KEPULAUAN RIAU'],
            ['id' => '31', 'name' => 'DKI JAKARTA'],
            ['id' => '32', 'name' => 'JAWA BARAT'],
            ['id' => '33', 'name' => 'JAWA TENGAH'],
            ['id' => '34', 'name' => 'DI YOGYAKARTA'],
            ['id' => '35', 'name' => 'JAWA TIMUR'],
            ['id' => '36', 'name' => 'BANTEN'],
            ['id' => '51', 'name' => 'BALI'],
            ['id' => '52', 'name' => 'NUSA TENGGARA BARAT'],
            ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
            ['id' => '61', 'name' => 'KALIMANTAN BARAT'],
            ['id' => '62', 'name' => 'KALIMANTAN TENGAH'],
            ['id' => '63', 'name' => 'KALIMANTAN SELATAN'],
            ['id' => '64', 'name' => 'KALIMANTAN TIMUR'],
            ['id' => '65', 'name' => 'KALIMANTAN UTARA'],
            ['id' => '71', 'name' => 'SULAWESI UTARA'],
            ['id' => '72', 'name' => 'SULAWESI TENGAH'],
            ['id' => '73', 'name' => 'SULAWESI SELATAN'],
            ['id' => '74', 'name' => 'SULAWESI TENGGARA'],
            ['id' => '75', 'name' => 'GORONTALO'],
            ['id' => '76', 'name' => 'SULAWESI BARAT'],
            ['id' => '81', 'name' => 'MALUKU'],
            ['id' => '82', 'name' => 'MALUKU UTARA'],
            ['id' => '91', 'name' => 'PAPUA BARAT'],
            ['id' => '94', 'name' => 'PAPUA'],
        ];

        foreach ($provinces as $province) {
            Province::create([
                'province_id' => $province['id'],
                'province' => $province['name']
            ]);
        }

        $this->command->info("✅ " . count($provinces) . " provinsi berhasil diimpor");
    }

    private function seedCities()
    {
        $this->command->info('🏙️ Mengimpor data kota/kabupaten...');

        // Try to get data from BinderByte API first
        try {
            $response = Http::timeout(30)->get('https://api.binderbyte.com/wilayah/kabupaten', [
                'api_key' => config('services.binderbyte.api_key')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['value']) && is_array($data['value'])) {
                    $this->processCitiesFromBinderByte($data['value']);
                    return;
                }
            }
        } catch (\Exception $e) {
            $this->command->warn('⚠️ BinderByte API gagal, menggunakan data manual...');
        }

        // Fallback to manual data
        $this->seedCitiesManual();
    }

    private function processCitiesFromBinderByte($cities)
    {
        $count = 0;
        $batch = [];

        foreach ($cities as $city) {
            $batch[] = [
                'city_id' => $city['id'] ?? $count + 1,
                'province_id' => substr($city['id'] ?? '00', 0, 2),
                'province' => $this->getProvinceName(substr($city['id'] ?? '00', 0, 2)),
                'type' => $this->getCityType($city['name'] ?? ''),
                'city_name' => $this->cleanCityName($city['name'] ?? ''),
                'postal_code' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $count++;

            // Insert in batches of 100
            if (count($batch) >= 100) {
                City::insert($batch);
                $batch = [];
                $this->command->info("📍 Diimpor: $count kota");
            }
        }

        // Insert remaining
        if (!empty($batch)) {
            City::insert($batch);
        }

        $this->command->info("✅ $count kota/kabupaten berhasil diimpor dari BinderByte");
    }

    private function seedCitiesManual()
    {
        $this->command->info('📝 Menggunakan data kota manual...');

        // Data kota-kota besar Indonesia (sample)
        $cities = [
            // DKI Jakarta
            ['id' => '3101', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Pusat'],
            ['id' => '3102', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Utara'],
            ['id' => '3103', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Barat'],
            ['id' => '3104', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Selatan'],
            ['id' => '3105', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kota', 'name' => 'Jakarta Timur'],
            ['id' => '3106', 'province_id' => '31', 'province' => 'DKI JAKARTA', 'type' => 'Kabupaten', 'name' => 'Kepulauan Seribu'],

            // Jawa Barat (sample)
            ['id' => '3201', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Bogor'],
            ['id' => '3202', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Sukabumi'],
            ['id' => '3203', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Cianjur'],
            ['id' => '3204', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kabupaten', 'name' => 'Bandung'],
            ['id' => '3271', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Bogor'],
            ['id' => '3272', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Sukabumi'],
            ['id' => '3273', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Bandung'],
            ['id' => '3274', 'province_id' => '32', 'province' => 'JAWA BARAT', 'type' => 'Kota', 'name' => 'Cirebon'],

            // Jawa Tengah (sample)
            ['id' => '3301', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Cilacap'],
            ['id' => '3302', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kabupaten', 'name' => 'Banyumas'],
            ['id' => '3371', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Magelang'],
            ['id' => '3372', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Surakarta'],
            ['id' => '3373', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Salatiga'],
            ['id' => '3374', 'province_id' => '33', 'province' => 'JAWA TENGAH', 'type' => 'Kota', 'name' => 'Semarang'],

            // Jawa Timur (sample)
            ['id' => '3501', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Pacitan'],
            ['id' => '3502', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kabupaten', 'name' => 'Ponorogo'],
            ['id' => '3571', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Kediri'],
            ['id' => '3572', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Blitar'],
            ['id' => '3573', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Malang'],
            ['id' => '3578', 'province_id' => '35', 'province' => 'JAWA TIMUR', 'type' => 'Kota', 'name' => 'Surabaya'],

            // Bali
            ['id' => '5101', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Jembrana'],
            ['id' => '5102', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Tabanan'],
            ['id' => '5103', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Badung'],
            ['id' => '5104', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kabupaten', 'name' => 'Gianyar'],
            ['id' => '5171', 'province_id' => '51', 'province' => 'BALI', 'type' => 'Kota', 'name' => 'Denpasar'],

            // NTB
            ['id' => '5201', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Lombok Barat'],
            ['id' => '5202', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Lombok Tengah'],
            ['id' => '5203', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Lombok Timur'],
            ['id' => '5204', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kabupaten', 'name' => 'Sumbawa'],
            ['id' => '5271', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kota', 'name' => 'Mataram'],
            ['id' => '5272', 'province_id' => '52', 'province' => 'NUSA TENGGARA BARAT', 'type' => 'Kota', 'name' => 'Bima'],
        ];

        $batch = [];
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
        }

        City::insert($batch);
        $this->command->info("✅ " . count($cities) . " kota/kabupaten berhasil diimpor (data manual)");
    }

    private function getProvinceName($provinceId)
    {
        $provinces = [
            '11' => 'ACEH', '12' => 'SUMATERA UTARA', '13' => 'SUMATERA BARAT',
            '14' => 'RIAU', '15' => 'JAMBI', '16' => 'SUMATERA SELATAN',
            '17' => 'BENGKULU', '18' => 'LAMPUNG', '19' => 'KEPULAUAN BANGKA BELITUNG',
            '21' => 'KEPULAUAN RIAU', '31' => 'DKI JAKARTA', '32' => 'JAWA BARAT',
            '33' => 'JAWA TENGAH', '34' => 'DI YOGYAKARTA', '35' => 'JAWA TIMUR',
            '36' => 'BANTEN', '51' => 'BALI', '52' => 'NUSA TENGGARA BARAT',
            '53' => 'NUSA TENGGARA TIMUR', '61' => 'KALIMANTAN BARAT',
            '62' => 'KALIMANTAN TENGAH', '63' => 'KALIMANTAN SELATAN',
            '64' => 'KALIMANTAN TIMUR', '65' => 'KALIMANTAN UTARA',
            '71' => 'SULAWESI UTARA', '72' => 'SULAWESI TENGAH',
            '73' => 'SULAWESI SELATAN', '74' => 'SULAWESI TENGGARA',
            '75' => 'GORONTALO', '76' => 'SULAWESI BARAT',
            '81' => 'MALUKU', '82' => 'MALUKU UTARA',
            '91' => 'PAPUA BARAT', '94' => 'PAPUA',
        ];

        return $provinces[$provinceId] ?? 'UNKNOWN';
    }

    private function getCityType($name)
    {
        if (stripos($name, 'KOTA') !== false) {
            return 'Kota';
        } elseif (stripos($name, 'KAB') !== false || stripos($name, 'KABUPATEN') !== false) {
            return 'Kabupaten';
        }
        return 'Kabupaten'; // Default
    }

    private function cleanCityName($name)
    {
        // Remove prefixes
        $name = preg_replace('/^(KOTA|KAB\.?|KABUPATEN)\s+/i', '', $name);
        return trim($name);
    }
}