<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        // Create admin user
        $admin = User::firstOrCreate(
            ["email" => "admin@batiksambo.com"],
            [
                "name" => "Admin",
                "password" => Hash::make("password"),
                "role" => "admin",
                "phone" => "081234567890",
                "address" => "Jl. Admin No. 1",
                "is_active" => true,
            ],
        );
        $admin->assignRole("admin");

        // Create operator user
        $operator = User::firstOrCreate(
            ["email" => "operator@batiksambo.com"],
            [
                "name" => "Operator",
                "password" => Hash::make("password"),
                "role" => "operator",
                "phone" => "081234567891",
                "address" => "Jl. Operator No. 1",
                "is_active" => true,
            ],
        );
        $operator->assignRole("operator");

        // Create sample user
        $user = User::firstOrCreate(
            ["email" => "user@batiksambo.com"],
            [
                "name" => "User",
                "password" => Hash::make("password"),
                "role" => "user",
                "phone" => "081234567892",
                "address" => "Jl. User No. 1",
                "is_active" => true,
            ],
        );
        $user->assignRole("user");

        $this->call([
            BatikSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            WebsiteSettingSeeder::class,
            ServiceSeeder::class,
            ChatSeeder::class,
            IndonesiaWilayahSeeder::class,
            ComprehensiveCitiesSeeder::class,
        ]);
    }
}
