<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                "name" => "Kemeja Batik Motif Sasambo Classic",
                "slug" => "kemeja-batik-sasambo-classic",
                "description" =>
                    "Kemeja batik premium dengan motif klasik Sasambo yang elegan dan berkelas",
                "price" => 350000,
                "stock" => 25,
                "is_active" => true,
                "is_featured" => true,
                "category_id" => 1,
            ],
            [
                "name" => "Dress Batik Bunga Sasambo",
                "slug" => "dress-batik-bunga-sasambo",
                "description" =>
                    "Dress batik feminim dengan motif bunga khas Sasambo yang memukau",
                "price" => 425000,
                "stock" => 15,
                "is_active" => true,
                "is_featured" => true,
                "category_id" => 2,
            ],
            [
                "name" => "Kain Batik Mega Mendung Sasambo",
                "slug" => "kain-batik-mega-mendung-sasambo",
                "description" =>
                    "Kain batik dengan motif mega mendung interpretasi Sasambo",
                "price" => 285000,
                "stock" => 30,
                "is_active" => true,
                "is_featured" => false,
                "category_id" => 3,
            ],
            [
                "name" => "Tas Selempang Motif Sasambo",
                "slug" => "tas-selempang-motif-sasambo",
                "description" =>
                    "Tas selempang dengan motif anyaman khas Sasambo",
                "price" => 145000,
                "stock" => 20,
                "is_active" => true,
                "is_featured" => false,
                "category_id" => 4,
            ],
            [
                "name" => "Set Couple Batik Sasambo Harmony",
                "slug" => "set-couple-batik-sasambo-harmony",
                "description" =>
                    "Set couple kemeja dan dress dengan motif serasi",
                "price" => 695000,
                "stock" => 10,
                "is_active" => true,
                "is_featured" => true,
                "category_id" => 5,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ["slug" => $productData["slug"]],
                $productData,
            );
        }
    }
}
