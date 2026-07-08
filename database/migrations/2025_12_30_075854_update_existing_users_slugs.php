<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add slug column if it doesn't exist
        if (!Schema::hasColumn('users', 'slug')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('email');
            });
        }

        // Generate slugs for existing users
        $users = DB::table('users')->whereNull('slug')->get();

        foreach ($users as $user) {
            $slug = Str::slug($user->name);
            $originalSlug = $slug;
            $counter = 1;

            // Make sure slug is unique
            while (DB::table('users')->where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            DB::table('users')->where('id', $user->id)->update(['slug' => $slug]);
        }

        // Now make the column not nullable and unique
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
        });
    }
};
