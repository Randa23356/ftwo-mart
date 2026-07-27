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
        DB::statement('ALTER TABLE orders MODIFY COLUMN total_amount DECIMAL(15, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE orders MODIFY COLUMN shipping_cost DECIMAL(15, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE order_items MODIFY COLUMN price DECIMAL(15, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE order_items MODIFY COLUMN subtotal DECIMAL(15, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE payment_transactions MODIFY COLUMN amount DECIMAL(15, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY COLUMN price DECIMAL(15, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY COLUMN custom_price_per_piece DECIMAL(15, 2) NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE orders MODIFY COLUMN total_amount DECIMAL(10, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE orders MODIFY COLUMN shipping_cost DECIMAL(10, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE order_items MODIFY COLUMN price DECIMAL(10, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE order_items MODIFY COLUMN subtotal DECIMAL(10, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE payment_transactions MODIFY COLUMN amount DECIMAL(10, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY COLUMN price DECIMAL(10, 2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY COLUMN custom_price_per_piece DECIMAL(10, 2) NOT NULL DEFAULT 0');
    }
};
