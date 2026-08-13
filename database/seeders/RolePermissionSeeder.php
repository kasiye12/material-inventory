<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure all permissions exist
        $permissions = [
            'manage items',
            'manage categories',
            'manage locations',
            'create transactions',
            'view transactions',
            'view own transactions',
            'view all reports',
            'view own reports',
            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Fix GM Role - should have transaction access
        $gmRole = Role::firstOrCreate(['name' => 'gm', 'guard_name' => 'web']);
        $gmRole->syncPermissions([
            'manage items',
            'manage categories',
            'manage locations',
            'create transactions',
            'view transactions',
            'view all reports',
        ]);

        // Fix Manager Role
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions([
            'manage items',
            'create transactions',
            'view transactions',
            'view all reports',
        ]);

        // Fix Checker Role - view only
        $checkerRole = Role::firstOrCreate(['name' => 'checker', 'guard_name' => 'web']);
        $checkerRole->syncPermissions([
            'view transactions',
            'view all reports',
        ]);

        // Fix Storekeeper Role
        $storekeeperRole = Role::firstOrCreate(['name' => 'storekeeper', 'guard_name' => 'web']);
        $storekeeperRole->syncPermissions([
            'create transactions',
            'view own transactions',
            'view own reports',
        ]);

        // Fix Site Engineer Role
        $siteEngineerRole = Role::firstOrCreate(['name' => 'site_engineer', 'guard_name' => 'web']);
        $siteEngineerRole->syncPermissions([
            'create transactions',
            'view own transactions',
            'view own reports',
        ]);

        // Admin has all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        echo "✓ Role permissions updated successfully!\n";
        echo "\nGM now has: create transactions, view transactions\n";
        echo "Manager now has: create transactions, view transactions\n";
        echo "Checker now has: view transactions only\n";
    }
}
