<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "Seeding SaveParking Database...\n";

        // Create users with password hints
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@saveparking.com',
                'password' => Hash::make('password'),
                'password_hint' => 'Default password is: password',
                'security_question' => 'What is the default password?',
                'security_answer' => 'password',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gate Attendant 1',
                'email' => 'attendant@saveparking.com',
                'password' => Hash::make('password'),
                'password_hint' => 'Default password is: password',
                'security_question' => 'What is the default password?',
                'security_answer' => 'password',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gate Attendant 2',
                'email' => 'attendant2@saveparking.com',
                'password' => Hash::make('password'),
                'password_hint' => 'Default password is: password',
                'security_question' => 'What is the default password?',
                'security_answer' => 'password',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        echo "✓ Users created with password hints\n";

        // Create tariff plans
        DB::table('tariff_plans')->insert([
            [
                'plan_name' => 'Standard Rate',
                'grace_period_mins' => 15,
                'first_hour_rate' => 20.00,
                'subsequent_hour_rate' => 30.00,
                'overnight_flat_rate' => 300.00,
                'daily_max_rate' => 200.00,
                'lost_ticket_penalty' => 500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        echo "✓ Tariff plan created\n";

        // Create zones with slots
        $zones = [
            ['zone_code' => 'ZONE-A', 'zone_name' => 'Ground Floor', 'total_slots' => 50],
            ['zone_code' => 'ZONE-B', 'zone_name' => 'First Floor', 'total_slots' => 30],
            ['zone_code' => 'ZONE-C', 'zone_name' => 'VIP Section', 'total_slots' => 20]
        ];

        foreach ($zones as $zoneData) {
            $zoneId = DB::table('parking_zones')->insertGetId([
                'zone_code' => $zoneData['zone_code'],
                'zone_name' => $zoneData['zone_name'],
                'total_slots' => $zoneData['total_slots'],
                'occupied_slots' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $slots = [];
            for ($i = 1; $i <= $zoneData['total_slots']; $i++) {
                $slots[] = [
                    'zone_id' => $zoneId,
                    'slot_number' => substr($zoneData['zone_code'], -1) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('parking_slots')->insert($slots);
        }
        echo "✓ Created " . array_sum(array_column($zones, 'total_slots')) . " parking slots\n";

        echo "\n✅ Database seeded successfully!\n";
    }
}
