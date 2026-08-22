<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class ClassifyItemsSeeder extends Seeder
{
    public function run(): void
    {
        // Classify items based on their category
        $fuelCategory = Category::where('code', 'FUEL')->first();
        $equipmentCategory = Category::where('code', 'EQP')->first();
        $toolsCategory = Category::where('code', 'TOL')->first();
        $furnitureCategory = Category::where('code', 'FUR')->first();
        
        // Fuel items
        if ($fuelCategory) {
            Item::where('category_id', $fuelCategory->id)
                ->update(['item_type' => 'fuel']);
        }
        
        // Fixed Asset items (Equipment, Tools, Furniture)
        if ($equipmentCategory) {
            Item::where('category_id', $equipmentCategory->id)
                ->update(['item_type' => 'fixed_asset']);
        }
        if ($toolsCategory) {
            Item::where('category_id', $toolsCategory->id)
                ->update(['item_type' => 'fixed_asset']);
        }
        if ($furnitureCategory) {
            Item::where('category_id', $furnitureCategory->id)
                ->update(['item_type' => 'fixed_asset']);
        }
        
        // Used material items (based on name)
        Item::where(function($q) {
            $q->where('name', 'like', '%Used%')
              ->orWhere('name', 'like', '%Ega Sheet%')
              ->orWhere('name', 'like', '%Blanket%')
              ->orWhere('name', 'like', '%Mattress%');
        })->update(['item_type' => 'used_material']);
        
        echo "✓ Items classified!\n";
    }
}
