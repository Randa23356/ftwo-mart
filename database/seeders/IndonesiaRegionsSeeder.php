<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class IndonesiaRegionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        City::truncate();
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert provinces
        $provinces = $this->getProvinces();
        Province::insert($provinces);

        // Insert cities in batches for better performance
        $cities = $this->getCities();
        $chunks = array_chunk($cities, 100);
        
        foreach ($chunks as $chunk) {
            City::insert($chunk);
        }

        $this->command->info('Indonesia regions seeded successfully!');
        $this->command->info('Provinces: ' . count($provinces));
        $this->command->info('Cities: ' . count($cities));
    }

    private function getProvinces()
    {
        return [
            ['province_id' => '1', 'province' => 'Bali', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '2', 'province' => 'Bangka Belitung', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '3', 'province' => 'Banten', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '4', 'province' => 'Bengkulu', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '5', 'province' => 'DI Yogyakarta', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '6', 'province' => 'DKI Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '7', 'province' => 'Gorontalo', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '8', 'province' => 'Jambi', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '9', 'province' => 'Jawa Barat', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '10', 'province' => 'Jawa Tengah', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '11', 'province' => 'Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '12', 'province' => 'Kalimantan Barat', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '13', 'province' => 'Kalimantan Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '14', 'province' => 'Kalimantan Tengah', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '15', 'province' => 'Kalimantan Timur', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '16', 'province' => 'Kalimantan Utara', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '17', 'province' => 'Kepulauan Riau', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '18', 'province' => 'Lampung', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '19', 'province' => 'Maluku', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '20', 'province' => 'Maluku Utara', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '21', 'province' => 'Nanggroe Aceh Darussalam (NAD)', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '23', 'province' => 'Nusa Tenggara Timur (NTT)', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '24', 'province' => 'Papua', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '25', 'province' => 'Papua Barat', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '26', 'province' => 'Riau', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '27', 'province' => 'Sulawesi Barat', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '28', 'province' => 'Sulawesi Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '29', 'province' => 'Sulawesi Tengah', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '30', 'province' => 'Sulawesi Tenggara', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '31', 'province' => 'Sulawesi Utara', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '32', 'province' => 'Sumatera Barat', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '33', 'province' => 'Sumatera Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['province_id' => '34', 'province' => 'Sumatera Utara', 'created_at' => now(), 'updated_at' => now()],
        ];
    }

    private function getCities()
    {
        return [
            // NTB (Nusa Tenggara Barat) - Lombok ada di sini
            ['city_id' => '256', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kota', 'city_name' => 'Mataram', 'postal_code' => '83115', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '257', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kabupaten', 'city_name' => 'Lombok Barat', 'postal_code' => '83311', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '258', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kabupaten', 'city_name' => 'Lombok Tengah', 'postal_code' => '83511', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '259', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kabupaten', 'city_name' => 'Lombok Timur', 'postal_code' => '83611', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '260', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kabupaten', 'city_name' => 'Lombok Utara', 'postal_code' => '83711', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '261', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kabupaten', 'city_name' => 'Sumbawa', 'postal_code' => '84311', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '262', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kabupaten', 'city_name' => 'Sumbawa Barat', 'postal_code' => '84411', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '263', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kabupaten', 'city_name' => 'Dompu', 'postal_code' => '84211', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '264', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kabupaten', 'city_name' => 'Bima', 'postal_code' => '84171', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '265', 'province_id' => '22', 'province' => 'Nusa Tenggara Barat (NTB)', 'type' => 'Kota', 'city_name' => 'Bima', 'postal_code' => '84139', 'created_at' => now(), 'updated_at' => now()],

            // DKI Jakarta
            ['city_id' => '151', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Barat', 'postal_code' => '11220', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '152', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat', 'postal_code' => '10540', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '153', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Selatan', 'postal_code' => '12230', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '154', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Timur', 'postal_code' => '13330', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '155', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kota', 'city_name' => 'Jakarta Utara', 'postal_code' => '14140', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '156', 'province_id' => '6', 'province' => 'DKI Jakarta', 'type' => 'Kabupaten', 'city_name' => 'Kepulauan Seribu', 'postal_code' => '14550', 'created_at' => now(), 'updated_at' => now()],

            // Jawa Barat (Major cities)
            ['city_id' => '23', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bandung', 'postal_code' => '40111', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '24', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bandung', 'postal_code' => '40311', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '25', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bandung Barat', 'postal_code' => '40721', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '26', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Banjar', 'postal_code' => '46311', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '27', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bekasi', 'postal_code' => '17837', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '28', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bekasi', 'postal_code' => '17837', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '29', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Bogor', 'postal_code' => '16119', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '30', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Bogor', 'postal_code' => '16911', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '31', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Cimahi', 'postal_code' => '40512', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '32', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Cirebon', 'postal_code' => '45116', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '33', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Cirebon', 'postal_code' => '45611', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '34', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Depok', 'postal_code' => '16416', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '35', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Garut', 'postal_code' => '44126', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '36', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Indramayu', 'postal_code' => '45214', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '37', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Karawang', 'postal_code' => '41311', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '38', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Kuningan', 'postal_code' => '45511', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '39', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Majalengka', 'postal_code' => '45412', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '40', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Pangandaran', 'postal_code' => '46511', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '41', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Purwakarta', 'postal_code' => '41119', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '42', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Subang', 'postal_code' => '41211', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '43', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Sukabumi', 'postal_code' => '43311', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '44', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Sukabumi', 'postal_code' => '43114', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '45', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Sumedang', 'postal_code' => '45326', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '46', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kota', 'city_name' => 'Tasikmalaya', 'postal_code' => '46116', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '47', 'province_id' => '9', 'province' => 'Jawa Barat', 'type' => 'Kabupaten', 'city_name' => 'Tasikmalaya', 'postal_code' => '46411', 'created_at' => now(), 'updated_at' => now()],

            // Jawa Tengah (Major cities)
            ['city_id' => '399', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Semarang', 'postal_code' => '50135', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '400', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kabupaten', 'city_name' => 'Semarang', 'postal_code' => '50511', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '401', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Surakarta', 'postal_code' => '57113', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '402', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Magelang', 'postal_code' => '56133', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '403', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kabupaten', 'city_name' => 'Magelang', 'postal_code' => '56519', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '404', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Pekalongan', 'postal_code' => '51122', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '405', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kabupaten', 'city_name' => 'Pekalongan', 'postal_code' => '51161', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '406', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Salatiga', 'postal_code' => '50711', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '407', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kota', 'city_name' => 'Tegal', 'postal_code' => '52114', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '408', 'province_id' => '10', 'province' => 'Jawa Tengah', 'type' => 'Kabupaten', 'city_name' => 'Tegal', 'postal_code' => '52419', 'created_at' => now(), 'updated_at' => now()],

            // Jawa Timur (Major cities)
            ['city_id' => '444', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Surabaya', 'postal_code' => '60119', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '445', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Malang', 'postal_code' => '65112', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '446', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kabupaten', 'city_name' => 'Malang', 'postal_code' => '65163', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '447', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Kediri', 'postal_code' => '64123', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '448', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kabupaten', 'city_name' => 'Kediri', 'postal_code' => '64184', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '449', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kota', 'city_name' => 'Mojokerto', 'postal_code' => '61316', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '450', 'province_id' => '11', 'province' => 'Jawa Timur', 'type' => 'Kabupaten', 'city_name' => 'Mojokerto', 'postal_code' => '61382', 'created_at' => now(), 'updated_at' => now()],

            // DI Yogyakarta
            ['city_id' => '501', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kota', 'city_name' => 'Yogyakarta', 'postal_code' => '55111', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '502', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kabupaten', 'city_name' => 'Bantul', 'postal_code' => '55711', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '503', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kabupaten', 'city_name' => 'Gunung Kidul', 'postal_code' => '55812', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '504', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kabupaten', 'city_name' => 'Kulon Progo', 'postal_code' => '55611', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '505', 'province_id' => '5', 'province' => 'DI Yogyakarta', 'type' => 'Kabupaten', 'city_name' => 'Sleman', 'postal_code' => '55511', 'created_at' => now(), 'updated_at' => now()],

            // Bali
            ['city_id' => '17', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Badung', 'postal_code' => '80351', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '18', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Bangli', 'postal_code' => '80619', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '19', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Buleleng', 'postal_code' => '81111', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '20', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kota', 'city_name' => 'Denpasar', 'postal_code' => '80227', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '21', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Gianyar', 'postal_code' => '80519', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '22', 'province_id' => '1', 'province' => 'Bali', 'type' => 'Kabupaten', 'city_name' => 'Jembrana', 'postal_code' => '82251', 'created_at' => now(), 'updated_at' => now()],

            // Sumatera Utara (Major cities)
            ['city_id' => '56', 'province_id' => '34', 'province' => 'Sumatera Utara', 'type' => 'Kota', 'city_name' => 'Medan', 'postal_code' => '20228', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '57', 'province_id' => '34', 'province' => 'Sumatera Utara', 'type' => 'Kabupaten', 'city_name' => 'Deli Serdang', 'postal_code' => '20511', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '58', 'province_id' => '34', 'province' => 'Sumatera Utara', 'type' => 'Kota', 'city_name' => 'Binjai', 'postal_code' => '20712', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '59', 'province_id' => '34', 'province' => 'Sumatera Utara', 'type' => 'Kota', 'city_name' => 'Pematangsiantar', 'postal_code' => '21126', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '60', 'province_id' => '34', 'province' => 'Sumatera Utara', 'type' => 'Kota', 'city_name' => 'Tebing Tinggi', 'postal_code' => '20632', 'created_at' => now(), 'updated_at' => now()],

            // Sumatera Barat (Major cities)
            ['city_id' => '61', 'province_id' => '32', 'province' => 'Sumatera Barat', 'type' => 'Kota', 'city_name' => 'Padang', 'postal_code' => '25112', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '62', 'province_id' => '32', 'province' => 'Sumatera Barat', 'type' => 'Kota', 'city_name' => 'Bukittinggi', 'postal_code' => '26115', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '63', 'province_id' => '32', 'province' => 'Sumatera Barat', 'type' => 'Kota', 'city_name' => 'Padang Panjang', 'postal_code' => '27118', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '64', 'province_id' => '32', 'province' => 'Sumatera Barat', 'type' => 'Kota', 'city_name' => 'Payakumbuh', 'postal_code' => '26213', 'created_at' => now(), 'updated_at' => now()],

            // Riau (Major cities)
            ['city_id' => '65', 'province_id' => '26', 'province' => 'Riau', 'type' => 'Kota', 'city_name' => 'Pekanbaru', 'postal_code' => '28112', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '66', 'province_id' => '26', 'province' => 'Riau', 'type' => 'Kota', 'city_name' => 'Dumai', 'postal_code' => '28811', 'created_at' => now(), 'updated_at' => now()],

            // Kepulauan Riau
            ['city_id' => '67', 'province_id' => '17', 'province' => 'Kepulauan Riau', 'type' => 'Kota', 'city_name' => 'Batam', 'postal_code' => '29432', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '68', 'province_id' => '17', 'province' => 'Kepulauan Riau', 'type' => 'Kota', 'city_name' => 'Tanjung Pinang', 'postal_code' => '29111', 'created_at' => now(), 'updated_at' => now()],

            // Lampung
            ['city_id' => '69', 'province_id' => '18', 'province' => 'Lampung', 'type' => 'Kota', 'city_name' => 'Bandar Lampung', 'postal_code' => '35139', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '70', 'province_id' => '18', 'province' => 'Lampung', 'type' => 'Kota', 'city_name' => 'Metro', 'postal_code' => '34111', 'created_at' => now(), 'updated_at' => now()],

            // Sumatera Selatan
            ['city_id' => '71', 'province_id' => '33', 'province' => 'Sumatera Selatan', 'type' => 'Kota', 'city_name' => 'Palembang', 'postal_code' => '30111', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '72', 'province_id' => '33', 'province' => 'Sumatera Selatan', 'type' => 'Kota', 'city_name' => 'Prabumulih', 'postal_code' => '31121', 'created_at' => now(), 'updated_at' => now()],

            // Bengkulu
            ['city_id' => '73', 'province_id' => '4', 'province' => 'Bengkulu', 'type' => 'Kota', 'city_name' => 'Bengkulu', 'postal_code' => '38229', 'created_at' => now(), 'updated_at' => now()],

            // Jambi
            ['city_id' => '74', 'province_id' => '8', 'province' => 'Jambi', 'type' => 'Kota', 'city_name' => 'Jambi', 'postal_code' => '36111', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '75', 'province_id' => '8', 'province' => 'Jambi', 'type' => 'Kota', 'city_name' => 'Sungai Penuh', 'postal_code' => '37113', 'created_at' => now(), 'updated_at' => now()],

            // Aceh
            ['city_id' => '76', 'province_id' => '21', 'province' => 'Nanggroe Aceh Darussalam (NAD)', 'type' => 'Kota', 'city_name' => 'Banda Aceh', 'postal_code' => '23238', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '77', 'province_id' => '21', 'province' => 'Nanggroe Aceh Darussalam (NAD)', 'type' => 'Kota', 'city_name' => 'Langsa', 'postal_code' => '24412', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '78', 'province_id' => '21', 'province' => 'Nanggroe Aceh Darussalam (NAD)', 'type' => 'Kota', 'city_name' => 'Lhokseumawe', 'postal_code' => '24352', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '79', 'province_id' => '21', 'province' => 'Nanggroe Aceh Darussalam (NAD)', 'type' => 'Kota', 'city_name' => 'Sabang', 'postal_code' => '23512', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '80', 'province_id' => '21', 'province' => 'Nanggroe Aceh Darussalam (NAD)', 'type' => 'Kota', 'city_name' => 'Subulussalam', 'postal_code' => '24882', 'created_at' => now(), 'updated_at' => now()],

            // Kalimantan Timur
            ['city_id' => '81', 'province_id' => '15', 'province' => 'Kalimantan Timur', 'type' => 'Kota', 'city_name' => 'Samarinda', 'postal_code' => '75133', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '82', 'province_id' => '15', 'province' => 'Kalimantan Timur', 'type' => 'Kota', 'city_name' => 'Balikpapan', 'postal_code' => '76114', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '83', 'province_id' => '15', 'province' => 'Kalimantan Timur', 'type' => 'Kota', 'city_name' => 'Bontang', 'postal_code' => '75313', 'created_at' => now(), 'updated_at' => now()],

            // Kalimantan Selatan
            ['city_id' => '84', 'province_id' => '13', 'province' => 'Kalimantan Selatan', 'type' => 'Kota', 'city_name' => 'Banjarmasin', 'postal_code' => '70117', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '85', 'province_id' => '13', 'province' => 'Kalimantan Selatan', 'type' => 'Kota', 'city_name' => 'Banjarbaru', 'postal_code' => '70712', 'created_at' => now(), 'updated_at' => now()],

            // Kalimantan Tengah
            ['city_id' => '86', 'province_id' => '14', 'province' => 'Kalimantan Tengah', 'type' => 'Kota', 'city_name' => 'Palangka Raya', 'postal_code' => '73112', 'created_at' => now(), 'updated_at' => now()],

            // Kalimantan Barat
            ['city_id' => '87', 'province_id' => '12', 'province' => 'Kalimantan Barat', 'type' => 'Kota', 'city_name' => 'Pontianak', 'postal_code' => '78115', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '88', 'province_id' => '12', 'province' => 'Kalimantan Barat', 'type' => 'Kota', 'city_name' => 'Singkawang', 'postal_code' => '79117', 'created_at' => now(), 'updated_at' => now()],

            // Kalimantan Utara
            ['city_id' => '89', 'province_id' => '16', 'province' => 'Kalimantan Utara', 'type' => 'Kota', 'city_name' => 'Tarakan', 'postal_code' => '77114', 'created_at' => now(), 'updated_at' => now()],

            // Sulawesi Utara
            ['city_id' => '90', 'province_id' => '31', 'province' => 'Sulawesi Utara', 'type' => 'Kota', 'city_name' => 'Manado', 'postal_code' => '95117', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '91', 'province_id' => '31', 'province' => 'Sulawesi Utara', 'type' => 'Kota', 'city_name' => 'Bitung', 'postal_code' => '95512', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '92', 'province_id' => '31', 'province' => 'Sulawesi Utara', 'type' => 'Kota', 'city_name' => 'Kotamobagu', 'postal_code' => '95711', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '93', 'province_id' => '31', 'province' => 'Sulawesi Utara', 'type' => 'Kota', 'city_name' => 'Tomohon', 'postal_code' => '95416', 'created_at' => now(), 'updated_at' => now()],

            // Sulawesi Tengah
            ['city_id' => '94', 'province_id' => '29', 'province' => 'Sulawesi Tengah', 'type' => 'Kota', 'city_name' => 'Palu', 'postal_code' => '94111', 'created_at' => now(), 'updated_at' => now()],

            // Sulawesi Selatan
            ['city_id' => '95', 'province_id' => '28', 'province' => 'Sulawesi Selatan', 'type' => 'Kota', 'city_name' => 'Makassar', 'postal_code' => '90111', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '96', 'province_id' => '28', 'province' => 'Sulawesi Selatan', 'type' => 'Kota', 'city_name' => 'Palopo', 'postal_code' => '91911', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '97', 'province_id' => '28', 'province' => 'Sulawesi Selatan', 'type' => 'Kota', 'city_name' => 'Parepare', 'postal_code' => '91123', 'created_at' => now(), 'updated_at' => now()],

            // Sulawesi Tenggara
            ['city_id' => '98', 'province_id' => '30', 'province' => 'Sulawesi Tenggara', 'type' => 'Kota', 'city_name' => 'Kendari', 'postal_code' => '93117', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '99', 'province_id' => '30', 'province' => 'Sulawesi Tenggara', 'type' => 'Kota', 'city_name' => 'Bau-Bau', 'postal_code' => '93719', 'created_at' => now(), 'updated_at' => now()],

            // Gorontalo
            ['city_id' => '100', 'province_id' => '7', 'province' => 'Gorontalo', 'type' => 'Kota', 'city_name' => 'Gorontalo', 'postal_code' => '96115', 'created_at' => now(), 'updated_at' => now()],

            // Sulawesi Barat
            ['city_id' => '101', 'province_id' => '27', 'province' => 'Sulawesi Barat', 'type' => 'Kabupaten', 'city_name' => 'Majene', 'postal_code' => '91411', 'created_at' => now(), 'updated_at' => now()],

            // Maluku
            ['city_id' => '102', 'province_id' => '19', 'province' => 'Maluku', 'type' => 'Kota', 'city_name' => 'Ambon', 'postal_code' => '97124', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '103', 'province_id' => '19', 'province' => 'Maluku', 'type' => 'Kota', 'city_name' => 'Tual', 'postal_code' => '97612', 'created_at' => now(), 'updated_at' => now()],

            // Maluku Utara
            ['city_id' => '104', 'province_id' => '20', 'province' => 'Maluku Utara', 'type' => 'Kota', 'city_name' => 'Ternate', 'postal_code' => '97714', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '105', 'province_id' => '20', 'province' => 'Maluku Utara', 'type' => 'Kota', 'city_name' => 'Tidore Kepulauan', 'postal_code' => '97815', 'created_at' => now(), 'updated_at' => now()],

            // Papua Barat
            ['city_id' => '106', 'province_id' => '25', 'province' => 'Papua Barat', 'type' => 'Kota', 'city_name' => 'Sorong', 'postal_code' => '98411', 'created_at' => now(), 'updated_at' => now()],

            // Papua
            ['city_id' => '107', 'province_id' => '24', 'province' => 'Papua', 'type' => 'Kota', 'city_name' => 'Jayapura', 'postal_code' => '99113', 'created_at' => now(), 'updated_at' => now()],

            // NTT (Nusa Tenggara Timur)
            ['city_id' => '108', 'province_id' => '23', 'province' => 'Nusa Tenggara Timur (NTT)', 'type' => 'Kota', 'city_name' => 'Kupang', 'postal_code' => '85119', 'created_at' => now(), 'updated_at' => now()],

            // Banten
            ['city_id' => '109', 'province_id' => '3', 'province' => 'Banten', 'type' => 'Kota', 'city_name' => 'Serang', 'postal_code' => '42111', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '110', 'province_id' => '3', 'province' => 'Banten', 'type' => 'Kota', 'city_name' => 'Tangerang', 'postal_code' => '15111', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '111', 'province_id' => '3', 'province' => 'Banten', 'type' => 'Kabupaten', 'city_name' => 'Tangerang', 'postal_code' => '15914', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '112', 'province_id' => '3', 'province' => 'Banten', 'type' => 'Kota', 'city_name' => 'Tangerang Selatan', 'postal_code' => '15332', 'created_at' => now(), 'updated_at' => now()],
            ['city_id' => '113', 'province_id' => '3', 'province' => 'Banten', 'type' => 'Kota', 'city_name' => 'Cilegon', 'postal_code' => '42417', 'created_at' => now(), 'updated_at' => now()],

            // Bangka Belitung
            ['city_id' => '114', 'province_id' => '2', 'province' => 'Bangka Belitung', 'type' => 'Kota', 'city_name' => 'Pangkal Pinang', 'postal_code' => '33115', 'created_at' => now(), 'updated_at' => now()],
        ];
    }
}