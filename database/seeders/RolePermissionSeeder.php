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

        // ==========================================
        // ADMIN - Full access
        // ==========================================
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // ==========================================
        // GM - VIEW ALL REPORTS ONLY (no master data, no transactions)
        // ==========================================
        $gmRole = Role::firstOrCreate(['name' => 'gm', 'guard_name' => 'web']);
        $gmRole->syncPermissions([
            'view all reports',
        ]);

        // ==========================================
        // MANAGER - VIEW ALL REPORTS ONLY (no master data, no transactions)
        // ==========================================
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions([
            'view all reports',
        ]);

        // ==========================================
        // CHECKER - VIEW ALL REPORTS ONLY
        // ==========================================
        $checkerRole = Role::firstOrCreate(['name' => 'checker', 'guard_name' => 'web']);
        $checkerRole->syncPermissions([
            'view all reports',
        ]);

        // ==========================================
        // HEAD OFFICE - Master Data + Transactions + Reports
        // ==========================================
        $hoRole = Role::firstOrCreate(['name' => 'head_office', 'guard_name' => 'web']);
        $hoRole->syncPermissions([
            'manage items',
            'manage categories',
            'manage locations',
            'create transactions',
            'view transactions',
            'view all reports',
        ]);

        // ==========================================
        // MASTER DATA - Master Data + View Reports
        // ==========================================
        $mdRole = Role::firstOrCreate(['name' => 'master_data', 'guard_name' => 'web']);
        $mdRole->syncPermissions([
            'manage items',
            'manage categories',
            'manage locations',
            'view all reports',
        ]);

        // ==========================================
        // STOREKEEPER - Create Transactions (assigned projects) + View Own Reports
        // ==========================================
        $storekeeperRole = Role::firstOrCreate(['name' => 'storekeeper', 'guard_name' => 'web']);
        $storekeeperRole->syncPermissions([
            'create transactions',
            'view own transactions',
            'view own reports',
        ]);

        // ==========================================
        // SITE ENGINEER - Create Transactions (assigned project) + View Own Reports
        // ==========================================
        $siteEngineerRole = Role::firstOrCreate(['name' => 'site_engineer', 'guard_name' => 'web']);
        $siteEngineerRole->syncPermissions([
            'create transactions',
            'view own transactions',
            'view own reports',
        ]);

        echo "✓ Role permissions updated!\n";
        echo "\nGM: VIEW ALL REPORTS ONLY\n";
        echo "MANAGER: VIEW ALL REPORTS ONLY\n";
        echo "CHECKER: VIEW ALL REPORTS ONLY\n";
    }
}
