<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('province_id')->unique();
            $table->string('province');
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('city_id')->unique();
            $table->string('province_id');
            $table->string('province');
            $table->string('type'); // Kota/Kabupaten
            $table->string('city_name');
            $table->string('postal_code')->nullable();
            $table->timestamps();
            
            $table->index(['province_id']);
            $table->index(['city_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};