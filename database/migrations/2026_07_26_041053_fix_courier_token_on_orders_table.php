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
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'buyer_confirmed_delivery_at')) {
                $table->dropColumn('buyer_confirmed_delivery_at');
            }
            if (Schema::hasColumn('orders', 'seller_confirmed_delivery_at')) {
                $table->dropColumn('seller_confirmed_delivery_at');
            }
            if (!Schema::hasColumn('orders', 'courier_token')) {
                $table->string('courier_token', 64)->nullable()->unique()->after('expires_at');
            }
            if (!Schema::hasColumn('orders', 'courier_confirmed_at')) {
                $table->timestamp('courier_confirmed_at')->nullable()->after('courier_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_token', 'courier_confirmed_at']);
            $table->timestamp('buyer_confirmed_delivery_at')->nullable();
            $table->timestamp('seller_confirmed_delivery_at')->nullable();
        });
    }
};
