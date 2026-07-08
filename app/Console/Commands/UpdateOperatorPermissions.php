<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class UpdateOperatorPermissions extends Command
{
    protected $signature = 'permissions:update-operator';
    protected $description = 'Update operator role permissions to include delete permissions';

    public function handle()
    {
        $operatorRole = Role::findByName('operator');
        
        if (!$operatorRole) {
            $this->error('Operator role not found!');
            return 1;
        }

        // Add product-delete permission
        $operatorRole->givePermissionTo('product-delete');
        
        // Remove order-delete permission if exists
        if ($operatorRole->hasPermissionTo('order-delete')) {
            $operatorRole->revokePermissionTo('order-delete');
            $this->info('Removed permission: order-delete');
        }

        $this->info('✅ Operator permissions updated successfully!');
        $this->info('Added permission: product-delete');
        
        return 0;
    }
}
