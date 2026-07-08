<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add COD to payment_method enum and SHIPPED to order_status enum
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('ewallet','qris','bank_transfer','midtrans','cod') NOT NULL");
        DB::statement("ALTER TABLE orders MODIFY order_status ENUM('pending','processing','ready','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert to previous enum definitions
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('ewallet','qris','bank_transfer','midtrans') NOT NULL");
        DB::statement("ALTER TABLE orders MODIFY order_status ENUM('pending','processing','ready','delivered','cancelled') NOT NULL DEFAULT 'pending'");
    }
};


