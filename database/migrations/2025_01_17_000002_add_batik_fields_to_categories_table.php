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
        Schema::table('categories', function (Blueprint $table) {
            // Batik specific fields untuk categories
            $table->enum('category_type', ['clothing', 'fabric', 'accessories', 'home_decor'])->nullable()->after('description');
            $table->text('cultural_significance')->nullable()->after('category_type'); // Makna budaya kategori
            $table->string('target_gender')->nullable()->after('cultural_significance'); // Target gender (pria/wanita/unisex)
            $table->json('typical_occasions')->nullable()->after('target_gender'); // Acara yang cocok (formal, kasual, tradisional, dll)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'category_type',
                'cultural_significance',
                'target_gender',
                'typical_occasions'
            ]);
        });
    }
};
