<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreignId('seller_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->enum('visibility', ['staff', 'admin_only', 'internal', 'seller_buyer'])->default('staff')->change();
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn('seller_id');
            $table->enum('visibility', ['staff', 'admin_only', 'internal'])->default('staff')->change();
        });
    }
};
