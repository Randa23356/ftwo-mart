<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->string('selected_size')->nullable()->after('quantity');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->string('selected_size')->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('selected_size');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('selected_size');
        });
    }
};
