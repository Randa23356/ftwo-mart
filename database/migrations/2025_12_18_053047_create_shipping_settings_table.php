<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->string('origin_city_id')->default('256'); // Mataram, Lombok
            $table->string('origin_city_name')->default('Kota Mataram');
            $table->string('origin_province')->default('Nusa Tenggara Barat (NTB)');
            $table->string('origin_postal_code')->default('83115');
            $table->decimal('origin_latitude', 10, 7)->default(-8.5833);
            $table->decimal('origin_longitude', 10, 7)->default(116.1167);
            $table->string('warehouse_name')->default('Picia Bakery Warehouse');
            $table->text('warehouse_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default data
        DB::table('shipping_settings')->insert([
            'origin_city_id' => '256',
            'origin_city_name' => 'Kota Mataram',
            'origin_province' => 'Nusa Tenggara Barat (NTB)',
            'origin_postal_code' => '83115',
            'origin_latitude' => -8.5833,
            'origin_longitude' => 116.1167,
            'warehouse_name' => 'Picia Bakery Warehouse',
            'warehouse_address' => 'Mataram, Lombok, Nusa Tenggara Barat',
            'contact_phone' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_settings');
    }
};