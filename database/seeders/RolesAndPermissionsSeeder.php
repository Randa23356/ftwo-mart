<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

public function run(): void
    {
        // Reset cached roles and permissions
        app()["\Spatie\Permission\PermissionRegistrar"]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'dashboard-view',

            'product-view',
            'product-create',
            'product-edit',
            'product-delete',

            'category-view',
            'category-create',
            'category-edit',
            'category-delete',

            'order-view',
            'order-edit',
            'order-delete',

            'customer-view',
            'customer-edit',
            'customer-delete',

            'user-view',
            'user-create',
            'user-edit',
            'user-delete',

            'setting-view',
            'setting-edit',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // create roles and assign existing permissions
        $operatorRole = Role::create(['name' => 'operator']);
        $operatorRole->givePermissionTo([
            'dashboard-view',
            'product-view',
            'product-create',
            'product-edit',
            'product-delete',
            'category-view',
            'order-view',
            'order-edit',
            'customer-view',
            'customer-edit',
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // create a default user role
        Role::create(['name' => 'user']);
    }
}
