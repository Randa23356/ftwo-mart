<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\WebsiteSetting;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class BatikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories and products
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        // Create batik categories
        $categories = [
            [
                'name' => 'Batik Kemeja Pria',
                'slug' => 'batik-kemeja-pria',
                'description' => 'Koleksi kemeja batik untuk pria dengan motif khas Sasambo',
                'category_type' => 'clothing',
                'cultural_significance' => 'Kemeja batik melambangkan kebanggaan akan budaya lokal dan cocok untuk acara formal maupun semi-formal',
                'target_gender' => 'pria',
                'typical_occasions' => json_encode(['formal', 'kantor', 'acara budaya', 'pernikahan']),
                'is_active' => true
            ],
            [
                'name' => 'Batik Dress Wanita',
                'slug' => 'batik-dress-wanita',
                'description' => 'Dress batik elegan untuk wanita dengan sentuhan modern',
                'category_type' => 'clothing',
                'cultural_significance' => 'Dress batik menggabungkan keanggunan tradisional dengan gaya kontemporer',
                'target_gender' => 'wanita',
                'typical_occasions' => json_encode(['formal', 'pesta', 'acara budaya', 'kondangan']),
                'is_active' => true
            ],
            [
                'name' => 'Kain Batik Premium',
                'slug' => 'kain-batik-premium',
                'description' => 'Kain batik berkualitas tinggi untuk berbagai keperluan',
                'category_type' => 'fabric',
                'cultural_significance' => 'Kain batik adalah warisan budaya yang dapat dibentuk sesuai kreativitas',
                'target_gender' => 'unisex',
                'typical_occasions' => json_encode(['custom', 'jahit', 'koleksi', 'hadiah']),
                'is_active' => true
            ],
            [
                'name' => 'Aksesoris Batik',
                'slug' => 'aksesoris-batik',
                'description' => 'Aksesoris dengan motif batik Sasambo',
                'category_type' => 'accessories',
                'cultural_significance' => 'Aksesoris batik memberikan sentuhan budaya pada penampilan sehari-hari',
                'target_gender' => 'unisex',
                'typical_occasions' => json_encode(['casual', 'sehari-hari', 'hadiah', 'souvenir']),
                'is_active' => true
            ],
            [
                'name' => 'Batik Couple',
                'slug' => 'batik-couple',
                'description' => 'Set batik untuk pasangan dengan motif serasi',
                'category_type' => 'clothing',
                'cultural_significance' => 'Batik couple melambangkan keharmonisan dan persatuan dalam keberagaman',
                'target_gender' => 'unisex',
                'typical_occasions' => json_encode(['pernikahan', 'acara keluarga', 'foto pre-wedding', 'anniversary']),
                'is_active' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Create sample batik products
        $products = [
            [
                'name' => 'Kemeja Batik Motif Sasambo Classic',
                'motif_name' => 'Sasambo Classic',
                'slug' => 'kemeja-batik-sasambo-classic',
                'description' => 'Kemeja batik premium dengan motif klasik Sasambo yang elegan dan berkelas',
                'motif_meaning' => 'Motif ini melambangkan kebijaksanaan leluhur dan keharmonisan alam Sasambo',
                'origin_region' => 'Sasambo, Nusa Tenggara',
                'batik_technique' => 'tulis',
                'material_description' => 'Katun premium berkualitas tinggi, adem dan nyaman digunakan',
                'available_sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'available_colors' => json_encode(['Biru Navy', 'Cokelat', 'Hijau Tua']),
                'pattern_category' => 'geometris',
                'is_custom_available' => true,
                'min_custom_quantity' => 5,
                'custom_price_per_piece' => 450000,
                'price' => 350000,
                'stock' => 25,
                'is_active' => true,
                'is_featured' => true,
                'category_id' => 1
            ],
            [
                'name' => 'Dress Batik Bunga Sasambo',
                'motif_name' => 'Bunga Sasambo',
                'slug' => 'dress-batik-bunga-sasambo',
                'description' => 'Dress batik feminim dengan motif bunga khas Sasambo yang memukau',
                'motif_meaning' => 'Melambangkan keindahan dan kelembutan wanita Sasambo',
                'origin_region' => 'Sasambo, Nusa Tenggara',
                'batik_technique' => 'kombinasi',
                'material_description' => 'Viscose halus dengan aksen sutera pada bagian tertentu',
                'available_sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'available_colors' => json_encode(['Merah Maroon', 'Ungu Tua', 'Biru Dongker']),
                'pattern_category' => 'flora',
                'is_custom_available' => true,
                'min_custom_quantity' => 3,
                'custom_price_per_piece' => 520000,
                'price' => 425000,
                'stock' => 15,
                'is_active' => true,
                'is_featured' => true,
                'category_id' => 2
            ],
            [
                'name' => 'Kain Batik Mega Mendung Sasambo',
                'motif_name' => 'Mega Mendung Sasambo',
                'slug' => 'kain-batik-mega-mendung-sasambo',
                'description' => 'Kain batik dengan motif mega mendung interpretasi Sasambo',
                'motif_meaning' => 'Melambangkan kesabaran dan ketabahan menghadapi cobaan hidup',
                'origin_region' => 'Sasambo, Nusa Tenggara',
                'batik_technique' => 'cap',
                'material_description' => 'Katun primissima halus, cocok untuk berbagai keperluan jahit',
                'available_sizes' => json_encode(['2 meter', '2.5 meter']),
                'available_colors' => json_encode(['Biru Gradasi', 'Hijau Gradasi', 'Cokelat Gradasi']),
                'pattern_category' => 'awan',
                'is_custom_available' => false,
                'price' => 285000,
                'stock' => 30,
                'is_active' => true,
                'is_featured' => false,
                'category_id' => 3
            ],
            [
                'name' => 'Tas Selempang Motif Sasambo',
                'motif_name' => 'Anyaman Sasambo',
                'slug' => 'tas-selempang-motif-sasambo',
                'description' => 'Tas selempang dengan motif anyaman khas Sasambo',
                'motif_meaning' => 'Melambangkan keterikatan dan persatuan dalam keberagaman',
                'origin_region' => 'Sasambo, Nusa Tenggara',
                'batik_technique' => 'printing',
                'material_description' => 'Canvas berkualitas dengan lapisan waterproof',
                'available_sizes' => json_encode(['One Size']),
                'available_colors' => json_encode(['Cokelat Natural', 'Hitam', 'Navy']),
                'pattern_category' => 'geometris',
                'is_custom_available' => true,
                'min_custom_quantity' => 10,
                'custom_price_per_piece' => 180000,
                'price' => 145000,
                'stock' => 20,
                'is_active' => true,
                'is_featured' => false,
                'category_id' => 4
            ],
            [
                'name' => 'Set Couple Batik Sasambo Harmony',
                'motif_name' => 'Sasambo Harmony',
                'slug' => 'set-couple-batik-sasambo-harmony',
                'description' => 'Set couple kemeja dan dress dengan motif serasi',
                'motif_meaning' => 'Melambangkan keharmonisan dan cinta yang abadi',
                'origin_region' => 'Sasambo, Nusa Tenggara',
                'batik_technique' => 'tulis',
                'material_description' => 'Katun premium untuk kemeja, viscose sutera untuk dress',
                'available_sizes' => json_encode(['S-S', 'M-M', 'L-L', 'XL-XL']),
                'available_colors' => json_encode(['Biru Dongker', 'Cokelat Tua', 'Hijau Emerald']),
                'pattern_category' => 'kombinasi',
                'is_custom_available' => true,
                'min_custom_quantity' => 2,
                'custom_price_per_piece' => 850000,
                'price' => 695000,
                'stock' => 10,
                'is_active' => true,
                'is_featured' => true,
                'category_id' => 5
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Update website settings untuk batik
        WebsiteSetting::updateOrCreate(
            ['key' => 'website_name'],
            ['value' => 'Batik Sasambo', 'type' => 'text']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'website_description'],
            ['value' => 'Marketplace terpercaya untuk batik Sasambo asli dengan motif tradisional dan kualitas terjamin', 'type' => 'textarea']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'address'],
            ['value' => 'Sasambo, Nusa Tenggara, Indonesia', 'type' => 'textarea']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'email'],
            ['value' => 'info@batiksambo.com', 'type' => 'email']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'instagram'],
            ['value' => '@batiksambo', 'type' => 'text']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'facebook'],
            ['value' => 'Batik Sasambo Official', 'type' => 'text']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'opening_hours'],
            ['value' => 'Senin - Minggu: 08:00 - 20:00', 'type' => 'text']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'delivery_fee'],
            ['value' => '15000', 'type' => 'number']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'min_order'],
            ['value' => '200000', 'type' => 'number']
        );

        // Add new batik-specific settings
        WebsiteSetting::updateOrCreate(
            ['key' => 'custom_order_info'],
            ['value' => 'Kami menerima pesanan custom batik dengan minimum quantity tertentu. Hubungi kami untuk konsultasi desain.', 'type' => 'textarea']
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'batik_care_instructions'],
            ['value' => 'Cuci dengan tangan menggunakan detergen lembut, hindari pemutih, jemur tidak langsung terkena sinar matahari.', 'type' => 'textarea']
        );
    }
}
