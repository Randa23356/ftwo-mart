<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_province_multipliers', function (Blueprint $table) {
            $table->id();
            $table->string('province_name')->unique();
            $table->decimal('distance_multiplier', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_province_multipliers');
    }
};
