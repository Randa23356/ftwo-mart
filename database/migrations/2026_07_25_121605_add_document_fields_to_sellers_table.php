<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('ktp_path')->nullable()->after('banner');
            $table->string('nib_path')->nullable()->after('ktp_path');
            $table->string('npwp_path')->nullable()->after('nib_path');
            $table->string('rekening_tabungan_path')->nullable()->after('npwp_path');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_active');
            $table->text('rejection_reason')->nullable()->after('approval_status');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'ktp_path', 'nib_path', 'npwp_path', 'rekening_tabungan_path',
                'approval_status', 'rejection_reason', 'approved_at', 'approved_by'
            ]);
        });
    }
};
