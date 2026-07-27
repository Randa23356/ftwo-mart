<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'origin_city_id')) {
                $table->string('origin_city_id')->nullable()->after('email');
            } else {
                $table->string('origin_city_id')->nullable()->change();
            }
            $table->foreign('origin_city_id')->references('city_id')->on('cities')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['origin_city_id']);
            $table->dropColumn('origin_city_id');
        });
    }
};