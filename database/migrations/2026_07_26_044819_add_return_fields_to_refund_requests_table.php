<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->string('return_tracking_number')->nullable()->after('admin_notes');
            $table->string('return_evidence_image')->nullable()->after('return_tracking_number');
            $table->timestamp('buyer_returned_at')->nullable()->after('return_evidence_image');
            $table->timestamp('seller_returned_at')->nullable()->after('buyer_returned_at');
        });
    }

    public function down(): void
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            $table->dropColumn(['return_tracking_number', 'return_evidence_image', 'buyer_returned_at', 'seller_returned_at']);
        });
    }
};
