<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;

class FixCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        // Keep original categories
        $categories = [
            ['code' => 'CEM', 'name' => 'Cement', 'is_active' => true],
            ['code' => 'CON', 'name' => 'Concrete', 'is_active' => true],
            ['code' => 'RBR', 'name' => 'Re-Bar', 'is_active' => true],
            ['code' => 'SND', 'name' => 'Sand', 'is_active' => true],
            ['code' => 'AGG', 'name' => 'Aggregate', 'is_active' => true],
            ['code' => 'CHM', 'name' => 'Chemicals', 'is_active' => true],
            ['code' => 'STL', 'name' => 'Steel', 'is_active' => true],
            ['code' => 'WOD', 'name' => 'Wood', 'is_active' => true],
            ['code' => 'PLB', 'name' => 'Plumbing', 'is_active' => true],
            ['code' => 'ELC', 'name' => 'Electrical', 'is_active' => true],
            ['code' => 'FUEL', 'name' => 'Fuel & Oil', 'is_active' => true],
            ['code' => 'EQP', 'name' => 'Equipment', 'is_active' => true],
            ['code' => 'TOL', 'name' => 'Tools', 'is_active' => true],
            ['code' => 'FUR', 'name' => 'Furniture', 'is_active' => true],
        ];
        
        foreach ($categories as $cat) {
            Category::updateOrCreate(['code' => $cat['code']], $cat);
        }
        
        // Update fuel items to Fuel & Oil category
        $fuelCat = Category::where('code', 'FUEL')->first();
        Item::whereIn('code', ['FUEL-001', 'FUEL-002', 'FUEL-003', 'FUEL-004', 'FUEL-005'])
            ->update(['category_id' => $fuelCat->id]);
            
        // Update equipment items to Equipment category
        $eqpCat = Category::where('code', 'EQP')->first();
        Item::whereIn('code', ['FA-002', 'FA-003', 'FA-004', 'FA-006', 'FA-007', 'FA-009'])
            ->update(['category_id' => $eqpCat->id]);
            
        // Update container/storage items to Furniture category
        $furCat = Category::where('code', 'FUR')->first();
        Item::whereIn('code', ['FA-001', 'FA-005', 'FA-008', 'FA-010'])
            ->update(['category_id' => $furCat->id]);
        
        echo "✓ Categories fixed!\n";
        echo "\nFuel items → Fuel & Oil category\n";
        echo "Equipment items → Equipment category\n";
        echo "Furniture items → Furniture category\n";
    }
}
