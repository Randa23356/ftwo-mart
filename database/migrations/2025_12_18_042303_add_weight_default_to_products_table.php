<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Update existing weight column to have default value and ensure it's not null
            $table->integer('weight')->default(500)->change();
        });
        
        // Update existing products that have null weight
        DB::table('products')->whereNull('weight')->update(['weight' => 500]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert weight column to nullable
            $table->integer('weight')->nullable()->change();
        });
    }
};
