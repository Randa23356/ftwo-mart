<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Shipping information
            $table->string('shipping_courier')->nullable()->after('delivery_phone'); // JNE, TIKI, POS
            $table->string('shipping_service')->nullable()->after('shipping_courier'); // REG, OKE, YES
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_service');
            $table->integer('shipping_etd')->nullable()->after('shipping_cost'); // Estimated delivery days
            $table->string('tracking_number')->nullable()->after('shipping_etd');
            $table->timestamp('shipped_at')->nullable()->after('tracking_number');
            
            // Origin and destination for shipping calculation
            $table->string('origin_city_id')->nullable()->after('shipped_at'); // RajaOngkir city ID
            $table->string('destination_city_id')->nullable()->after('origin_city_id');
            $table->string('destination_province')->nullable()->after('destination_city_id');
            $table->string('destination_city')->nullable()->after('destination_province');
            $table->string('destination_postal_code')->nullable()->after('destination_city');
            
            // Weight for shipping calculation
            $table->integer('total_weight')->default(0)->after('destination_postal_code'); // in grams
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_courier',
                'shipping_service', 
                'shipping_cost',
                'shipping_etd',
                'tracking_number',
                'shipped_at',
                'origin_city_id',
                'destination_city_id',
                'destination_province',
                'destination_city',
                'destination_postal_code',
                'total_weight'
            ]);
        });
    }
};