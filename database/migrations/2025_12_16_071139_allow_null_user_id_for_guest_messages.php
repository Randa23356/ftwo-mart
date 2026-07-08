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
            // Drop foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Modify column to allow null
            $table->foreignId('user_id')->nullable()->change();
            
            // Add foreign key constraint back with nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Modify column to allow null
            $table->foreignId('user_id')->nullable()->change();
            
            // Add foreign key constraint back with nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Modify column to not allow null
            $table->foreignId('user_id')->nullable(false)->change();
            
            // Add foreign key constraint back
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Modify column to not allow null
            $table->foreignId('user_id')->nullable(false)->change();
            
            // Add foreign key constraint back
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
