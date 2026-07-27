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
        Schema::table('shipping_settings', function (Blueprint $table) {
            $table->integer('base_cost')->default(8000)->after('is_active');
            $table->integer('cost_per_kg')->default(2500)->after('base_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_settings', function (Blueprint $table) {
            $table->dropColumn(['base_cost', 'cost_per_kg']);
        });
    }
};
