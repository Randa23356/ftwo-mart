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
                "icon" => "shopping_cart",
                "url" => "#",
                "is_active" => true,
            ],
            [
                "name" => "Pengiriman",
                "icon" => "truck",
                "url" => "#",
                "is_active" => true,
            ],
            [
                "name" => "Custom Design",
                "icon" => "diamond",
                "url" => "#",
                "is_active" => true,
            ],
            [
                "name" => "Konsultasi Motif",
                "icon" => "headset",
                "url" => "#",
                "is_active" => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
