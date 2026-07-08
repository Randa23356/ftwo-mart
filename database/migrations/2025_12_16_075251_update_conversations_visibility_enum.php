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
        Schema::table('conversations', function (Blueprint $table) {
            // Update visibility enum to include more options
            $table->enum('visibility', ['staff', 'admin_only', 'internal'])->default('staff')->change();
            
            // Add fields for better chat management
            $table->timestamp('last_activity_at')->nullable()->after('updated_at');
            $table->boolean('has_unread_admin')->default(false)->after('is_important');
            $table->boolean('has_unread_operator')->default(false)->after('has_unread_admin');
            $table->boolean('has_unread_user')->default(false)->after('has_unread_operator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->enum('visibility', ['staff', 'admin_only'])->default('staff')->change();
            $table->dropColumn(['last_activity_at', 'has_unread_admin', 'has_unread_operator', 'has_unread_user']);
        });
    }
};
