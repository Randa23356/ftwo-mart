<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                "name" => "Pemesanan Online",
                "url" => "#",
                "is_active" => true,
            ],
            [
                "name" => "Pengiriman",
                "url" => "#",
                "is_active" => true,
            ],
            [
                "name" => "Custom Design",
                "url" => "#",
                "is_active" => true,
            ],
            [
                "name" => "Konsultasi Motif",
                "url" => "#",
                "is_active" => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
