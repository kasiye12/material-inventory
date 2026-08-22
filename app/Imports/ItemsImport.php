<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class ItemsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, SkipsOnError
{
    use SkipsErrors;
    
    public function model(array $row)
    {
        // Handle different column names
        $code = $row['code'] ?? $row['item_code'] ?? $row['item_no'] ?? null;
        $name = $row['name'] ?? $row['item_name'] ?? $row['description'] ?? $row['item_description'] ?? null;
        $categoryName = $row['category'] ?? $row['category_name'] ?? 'Uncategorized';
        $unit = $row['unit'] ?? $row['uom'] ?? $row['unit_of_measure'] ?? 'Pcs';
        $itemType = $row['item_type'] ?? $row['type'] ?? 'regular';
        $unitPrice = $row['unit_price'] ?? $row['price'] ?? 0;
        $minStock = $row['min_stock'] ?? $row['min_stock_level'] ?? 0;
        $maxStock = $row['max_stock'] ?? $row['max_stock_level'] ?? 0;
        
        // Skip if no name or code
        if (!$name || !$code) {
            return null;
        }
        
        // Find or create category - use firstOrCreate with name only
        $category = Category::firstOrCreate(
            ['name' => $categoryName],
            ['code' => $this->generateCategoryCode($categoryName), 'is_active' => true]
        );
        
        // If category exists but code was generated, ensure code is set
        if (!$category->code) {
            $category->code = $this->generateCategoryCode($categoryName);
            $category->save();
        }
        
        // Validate item type
        $validTypes = ['regular', 'fixed_asset', 'used_material', 'fuel'];
        if (!in_array($itemType, $validTypes)) {
            $itemType = 'regular';
        }
        
        // Check if item exists, update if found
        $existingItem = Item::where('code', $code)->first();
        
        if ($existingItem) {
            $existingItem->update([
                'name' => $name,
                'category_id' => $category->id,
                'unit' => $unit,
                'item_type' => $itemType,
                'unit_price' => $unitPrice ?: $existingItem->unit_price,
                'min_stock_level' => $minStock ?: $existingItem->min_stock_level,
                'max_stock_level' => $maxStock ?: $existingItem->max_stock_level,
            ]);
            return null;
        }
        
        return new Item([
            'code' => $code,
            'name' => $name,
            'description' => $row['description'] ?? null,
            'category_id' => $category->id,
            'unit' => $unit,
            'item_type' => $itemType,
            'unit_price' => $unitPrice ?: 0,
            'min_stock_level' => $minStock ?: 0,
            'max_stock_level' => $maxStock ?: 0,
            'is_active' => true,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.code' => 'required|string',
            '*.name' => 'required|string|max:255',
            '*.unit' => 'required|string|max:20',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Generate unique category code
     */
    private function generateCategoryCode($name)
    {
        // Clean the name
        $clean = preg_replace('/[^a-zA-Z0-9]/', '', $name);
        $code = strtoupper(substr($clean, 0, 3));
        
        // If code is empty, generate random
        if (empty($code)) {
            $code = 'CAT';
        }
        
        // Check if code exists, add suffix if needed
        $originalCode = $code;
        $counter = 1;
        
        while (Category::where('code', $code)->exists()) {
            $code = $originalCode . $counter;
            $counter++;
        }
        
        return $code;
    }
}
