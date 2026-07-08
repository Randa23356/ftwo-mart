<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WebsiteSetting;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                "key" => "website_name",
                "value" => "FtwoMart",
                "type" => "text",
            ],
            [
                "key" => "website_description",
                "value" =>
                    "Marketplace terpercaya untuk berbagai produk berkualitas dengan pelayanan terbaik.",
                "type" => "textarea",
            ],
            [
                "key" => "phone",
                "value" => "+62 812-3456-7890",
                "type" => "text",
            ],
            [
                "key" => "address",
                "value" => "Sasambo, Nusa Tenggara, Indonesia",
                "type" => "textarea",
            ],
            [
                "key" => "email",
                "value" => "info@batiksasambo.com",
                "type" => "email",
            ],
            [
                "key" => "instagram",
                "value" => "https://instagram.com",
                "type" => "text",
            ],
            [
                "key" => "facebook",
                "value" => "https://facebook.com",
                "type" => "text",
            ],
            [
                "key" => "opening_hours",
                "value" => "Senin - Minggu: 08:00 - 20:00",
                "type" => "text",
            ],
            ["key" => "delivery_fee", "value" => "20000", "type" => "number"],
            ["key" => "min_order", "value" => "50000", "type" => "number"],
            [
                "key" => "contact_title",
                "value" => "Hubungi Kami",
                "type" => "text",
            ],
            [
                "key" => "contact_description",
                "value" =>
                    "Ada pertanyaan atau ingin memesan produk custom? Jangan ragu untuk menghubungi kami. Tim kami siap membantu Anda.",
                "type" => "textarea",
            ],
            [
                "key" => "contact_whatsapp",
                "value" => "6281234567890",
                "type" => "text",
            ],
            [
                "key" => "contact_maps_embed",
                "value" =>
                    '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3945.033573454944!2d116.1158486147831!3d-8.59319499383394!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcdbf5914d3e2c7%3A0x8564c07889a4616!2sSasak%20Village%20Sade!5e0!3m2!1sen!2sid!4v1628676834589!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                "type" => "textarea",
            ],
            [
                "key" => "about_title",
                "value" => "Tentang FtwoMart",
                "type" => "text",
            ],
            [
                "key" => "about_content",
                "value" =>
                    "<p>FtwoMart adalah marketplace modern yang menyediakan berbagai produk berkualitas tinggi untuk memenuhi kebutuhan Anda. Kami berkomitmen untuk memberikan pengalaman berbelanja yang terbaik dengan produk pilihan dan pelayanan prima.</p><p>Misi kami adalah menjadi marketplace terpercaya yang menghubungkan pembeli dengan produk berkualitas. Kami selalu mengutamakan kepuasan pelanggan dan kualitas produk yang kami tawarkan.</p>",
                "type" => "textarea",
            ],
            [
                "key" => "custom_order_info",
                "value" =>
                    "Kami menerima pesanan custom produk dengan minimum quantity tertentu. Hubungi kami untuk konsultasi desain.",
                "type" => "textarea",
            ],
            [
                "key" => "batik_care_instructions",
                "value" =>
                    "Cuci dengan tangan menggunakan detergen lembut, hindari pemutih, jemur tidak langsung terkena sinar matahari.",
                "type" => "textarea",
            ],
        ];

        foreach ($settings as $setting) {
            WebsiteSetting::updateOrCreate(
                ["key" => $setting["key"]],
                [
                    "value" => $setting["value"],
                    "type" => $setting["type"] ?? "text",
                ],
            );
        }
    }
}
