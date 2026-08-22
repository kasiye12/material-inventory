<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class FuelFixedItemsSeeder extends Seeder
{
    public function run(): void
    {
        // Find or create Fuel category
        $fuelCategory = Category::firstOrCreate(
            ['code' => 'FUEL'],
            ['name' => 'Fuel', 'is_active' => true]
        );

        // Find or create Fixed Asset category
        $fixedCategory = Category::firstOrCreate(
            ['code' => 'FIXED'],
            ['name' => 'Fixed Assets', 'is_active' => true]
        );

        // Find or create Used Material category
        $usedCategory = Category::firstOrCreate(
            ['code' => 'USED'],
            ['name' => 'Used Materials', 'is_active' => true]
        );

        // Fuel items
        $fuelItems = [
            ['code' => 'FUEL-001', 'name' => 'Gas Oil (Diesel)', 'category_id' => $fuelCategory->id, 'unit' => 'Ltr', 'unit_price' => 80.00],
            ['code' => 'FUEL-002', 'name' => 'Benzene (Petrol)', 'category_id' => $fuelCategory->id, 'unit' => 'Ltr', 'unit_price' => 75.00],
            ['code' => 'FUEL-003', 'name' => 'Engine Oil', 'category_id' => $fuelCategory->id, 'unit' => 'Ltr', 'unit_price' => 450.00],
            ['code' => 'FUEL-004', 'name' => 'Hydraulic Oil', 'category_id' => $fuelCategory->id, 'unit' => 'Ltr', 'unit_price' => 350.00],
            ['code' => 'FUEL-005', 'name' => 'Grease', 'category_id' => $fuelCategory->id, 'unit' => 'Kg', 'unit_price' => 250.00],
        ];

        foreach ($fuelItems as $item) {
            Item::firstOrCreate(['code' => $item['code']], $item);
        }

        // Fixed Asset items
        $fixedItems = [
            ['code' => 'FA-001', 'name' => 'Metal Container', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 85000.00],
            ['code' => 'FA-002', 'name' => 'Total Station', 'category_id' => $fixedCategory->id, 'unit' => 'Set', 'unit_price' => 450000.00],
            ['code' => 'FA-003', 'name' => 'Total Station Accessories', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 15000.00],
            ['code' => 'FA-004', 'name' => 'Reflector Pole', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 3500.00],
            ['code' => 'FA-005', 'name' => 'Managerial Chair', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 8500.00],
            ['code' => 'FA-006', 'name' => 'Grease Gun', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 2500.00],
            ['code' => 'FA-007', 'name' => 'Diesel Water Pump 4 inch', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 89373.91],
            ['code' => 'FA-008', 'name' => 'Plastic Tanker 10000 ltr', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 115000.00],
            ['code' => 'FA-009', 'name' => 'Ppr Welding Machine', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 15000.00],
            ['code' => 'FA-010', 'name' => 'Surface Mounted Metalic Board', 'category_id' => $fixedCategory->id, 'unit' => 'Pcs', 'unit_price' => 222750.00],
        ];

        foreach ($fixedItems as $item) {
            Item::firstOrCreate(['code' => $item['code']], $item);
        }

        // Used Material items
        $usedItems = [
            ['code' => 'UM-001', 'name' => 'Ega Sheet', 'category_id' => $usedCategory->id, 'unit' => 'Pcs', 'unit_price' => 350.00],
            ['code' => 'UM-002', 'name' => 'Blanket', 'category_id' => $usedCategory->id, 'unit' => 'Pcs', 'unit_price' => 500.00],
            ['code' => 'UM-003', 'name' => 'Mattress', 'category_id' => $usedCategory->id, 'unit' => 'Pcs', 'unit_price' => 800.00],
            ['code' => 'UM-004', 'name' => 'Metal Bermel 200Ltr', 'category_id' => $usedCategory->id, 'unit' => 'Pcs', 'unit_price' => 1200.00],
            ['code' => 'UM-005', 'name' => 'Iron Sheet', 'category_id' => $usedCategory->id, 'unit' => 'Pcs', 'unit_price' => 250.00],
            ['code' => 'UM-006', 'name' => 'Dijino (Crow Bar)', 'category_id' => $usedCategory->id, 'unit' => 'Pcs', 'unit_price' => 350.00],
            ['code' => 'UM-007', 'name' => 'Used Eucalyptus', 'category_id' => $usedCategory->id, 'unit' => 'Trip', 'unit_price' => 2500.00],
            ['code' => 'UM-008', 'name' => 'Ply Wood & Purline', 'category_id' => $usedCategory->id, 'unit' => 'Trip', 'unit_price' => 3000.00],
            ['code' => 'UM-009', 'name' => 'Ply Wood & Eucalyptus', 'category_id' => $usedCategory->id, 'unit' => 'Trip', 'unit_price' => 2800.00],
            ['code' => 'UM-010', 'name' => 'Ega Sheet 220-450', 'category_id' => $usedCategory->id, 'unit' => 'Pcs', 'unit_price' => 400.00],
        ];

        foreach ($usedItems as $item) {
            Item::firstOrCreate(['code' => $item['code']], $item);
        }

        echo "✓ Fuel, Fixed Asset, and Used Material items added!\n";
        echo "\nFuel Items: " . count($fuelItems) . " items\n";
        echo "Fixed Assets: " . count($fixedItems) . " items\n";
        echo "Used Materials: " . count($usedItems) . " items\n";
    }
}
