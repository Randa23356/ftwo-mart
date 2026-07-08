<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Batik specific fields
            $table->string('motif_name')->nullable()->after('name'); // Nama motif batik
            $table->text('motif_meaning')->nullable()->after('description'); // Makna filosofis motif
            $table->string('origin_region')->nullable()->after('motif_meaning'); // Daerah asal
            $table->enum('batik_technique', ['tulis', 'cap', 'printing', 'kombinasi'])->nullable()->after('origin_region'); // Teknik pembuatan
            $table->text('material_description')->nullable()->after('batik_technique'); // Deskripsi bahan (katun, sutera, dll)
            $table->json('available_sizes')->nullable()->after('material_description'); // Size yang tersedia (S, M, L, XL, dll)
            $table->json('available_colors')->nullable()->after('available_sizes'); // Warna yang tersedia
            $table->string('pattern_category')->nullable()->after('available_colors'); // Kategori pola (geometris, flora, fauna, dll)
            $table->boolean('is_custom_available')->default(false)->after('pattern_category'); // Apakah bisa custom
            $table->integer('min_custom_quantity')->nullable()->after('is_custom_available'); // Minimum quantity untuk custom
            $table->decimal('custom_price_per_piece', 10, 2)->nullable()->after('min_custom_quantity'); // Harga custom per piece
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'motif_name',
                'motif_meaning',
                'origin_region',
                'batik_technique',
                'material_description',
                'available_sizes',
                'available_colors',
                'pattern_category',
                'is_custom_available',
                'min_custom_quantity',
                'custom_price_per_piece'
            ]);
        });
    }
};
