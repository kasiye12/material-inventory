<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Location;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class HeadOfficeUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Head Office role
        $hoRole = Role::firstOrCreate(['name' => 'head_office', 'guard_name' => 'web']);
        $hoRole->syncPermissions([
            'manage items',
            'manage categories',
            'manage locations',
            'create transactions',
            'view transactions',
            'view all reports',
        ]);

        // Find Head Office location
        $headOffice = Location::where('code', '0001')->first();
        
        if (!$headOffice) {
            $headOffice = Location::where('type', 'head_office')->first();
        }

        // Create Head Office user
        $hoUser = User::firstOrCreate(
            ['email' => 'headoffice@mims.com'],
            [
                'name' => 'Head Office Store',
                'password' => bcrypt('password'),
                'phone' => '+251977123456',
                'location_id' => $headOffice ? $headOffice->id : 1,
                'is_active' => true,
            ]
        );
        $hoUser->syncRoles(['head_office']);

        // Also create a Master Data Admin user
        $mdRole = Role::firstOrCreate(['name' => 'master_data', 'guard_name' => 'web']);
        $mdRole->syncPermissions([
            'manage items',
            'manage categories',
            'manage locations',
            'view transactions',
            'view all reports',
        ]);

        $mdUser = User::firstOrCreate(
            ['email' => 'masterdata@mims.com'],
            [
                'name' => 'Master Data Manager',
                'password' => bcrypt('password'),
                'phone' => '+251988123456',
                'location_id' => $headOffice ? $headOffice->id : 1,
                'is_active' => true,
            ]
        );
        $mdUser->syncRoles(['master_data']);

        echo "✓ Head Office users created!\n";
        echo "\nHead Office Store: headoffice@mims.com / password\n";
        echo "Master Data Manager: masterdata@mims.com / password\n";
    }
}
