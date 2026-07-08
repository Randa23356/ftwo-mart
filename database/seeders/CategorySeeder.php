<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [
            [
                "name" => "Batik Kemeja Pria",
                "slug" => "batik-kemeja-pria",
                "description" =>
                    "Koleksi kemeja batik untuk pria dengan motif khas Sasambo",
                "is_active" => true,
            ],
            [
                "name" => "Batik Dress Wanita",
                "slug" => "batik-dress-wanita",
                "description" =>
                    "Dress batik elegan untuk wanita dengan sentuhan modern",
                "is_active" => true,
            ],
            [
                "name" => "Kain Batik Premium",
                "slug" => "kain-batik-premium",
                "description" =>
                    "Kain batik berkualitas tinggi untuk berbagai keperluan",
                "is_active" => true,
            ],
            [
                "name" => "Aksesoris Batik",
                "slug" => "aksesoris-batik",
                "description" => "Aksesoris dengan motif batik Sasambo",
                "is_active" => true,
            ],
            [
                "name" => "Batik Couple",
                "slug" => "batik-couple",
                "description" => "Set batik untuk pasangan dengan motif serasi",
                "is_active" => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
