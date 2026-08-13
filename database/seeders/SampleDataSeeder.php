<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\StockTransaction;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "Creating sample items...\n";
        
        // Sample Items
        $items = [
            // Cement Category (1)
            ['code' => 'CEM-001', 'name' => 'PPC Cement 50kg', 'category_id' => 1, 'unit' => 'Bag', 'unit_price' => 850.00, 'min_stock_level' => 50, 'max_stock_level' => 500],
            ['code' => 'CEM-002', 'name' => 'OPC Cement 50kg', 'category_id' => 1, 'unit' => 'Bag', 'unit_price' => 780.00, 'min_stock_level' => 40, 'max_stock_level' => 400],
            ['code' => 'CEM-003', 'name' => 'White Cement 25kg', 'category_id' => 1, 'unit' => 'Bag', 'unit_price' => 450.00, 'min_stock_level' => 20, 'max_stock_level' => 200],
            
            // Concrete Category (2)
            ['code' => 'CON-001', 'name' => 'Ready Mix Concrete C25', 'category_id' => 2, 'unit' => 'm3', 'unit_price' => 5500.00, 'min_stock_level' => 10, 'max_stock_level' => 100],
            ['code' => 'CON-002', 'name' => 'Concrete Additive Sika', 'category_id' => 2, 'unit' => 'Ltr', 'unit_price' => 350.00, 'min_stock_level' => 20, 'max_stock_level' => 200],
            
            // Re-Bar Category (3)
            ['code' => 'RBR-001', 'name' => 'Rebar Diameter 8mm', 'category_id' => 3, 'unit' => 'Qtl', 'unit_price' => 5200.00, 'min_stock_level' => 30, 'max_stock_level' => 300],
            ['code' => 'RBR-002', 'name' => 'Rebar Diameter 10mm', 'category_id' => 3, 'unit' => 'Qtl', 'unit_price' => 5100.00, 'min_stock_level' => 25, 'max_stock_level' => 250],
            ['code' => 'RBR-003', 'name' => 'Rebar Diameter 12mm', 'category_id' => 3, 'unit' => 'Qtl', 'unit_price' => 5050.00, 'min_stock_level' => 20, 'max_stock_level' => 200],
            ['code' => 'RBR-004', 'name' => 'Rebar Diameter 16mm', 'category_id' => 3, 'unit' => 'Qtl', 'unit_price' => 5000.00, 'min_stock_level' => 15, 'max_stock_level' => 150],
            
            // Sand Category (4)
            ['code' => 'SND-001', 'name' => 'River Sand Fine', 'category_id' => 4, 'unit' => 'm3', 'unit_price' => 1200.00, 'min_stock_level' => 50, 'max_stock_level' => 500],
            ['code' => 'SND-002', 'name' => 'Crushed Sand Coarse', 'category_id' => 4, 'unit' => 'm3', 'unit_price' => 1000.00, 'min_stock_level' => 40, 'max_stock_level' => 400],
            
            // Aggregate Category (5)
            ['code' => 'AGG-001', 'name' => 'Aggregate 20mm', 'category_id' => 5, 'unit' => 'm3', 'unit_price' => 1800.00, 'min_stock_level' => 30, 'max_stock_level' => 300],
            ['code' => 'AGG-002', 'name' => 'Aggregate 10mm', 'category_id' => 5, 'unit' => 'm3', 'unit_price' => 1900.00, 'min_stock_level' => 25, 'max_stock_level' => 250],
            ['code' => 'AGG-003', 'name' => 'Gravel Base Course', 'category_id' => 5, 'unit' => 'm3', 'unit_price' => 1500.00, 'min_stock_level' => 20, 'max_stock_level' => 200],
            
            // Chemicals Category (6)
            ['code' => 'CHM-001', 'name' => 'Sika Floor 263', 'category_id' => 6, 'unit' => 'Ltr', 'unit_price' => 450.00, 'min_stock_level' => 10, 'max_stock_level' => 100],
            ['code' => 'CHM-002', 'name' => 'Waterproofing Compound', 'category_id' => 6, 'unit' => 'Kg', 'unit_price' => 280.00, 'min_stock_level' => 15, 'max_stock_level' => 150],
            ['code' => 'CHM-003', 'name' => 'Curing Compound', 'category_id' => 6, 'unit' => 'Ltr', 'unit_price' => 180.00, 'min_stock_level' => 20, 'max_stock_level' => 200],
            
            // Steel Category (7)
            ['code' => 'STL-001', 'name' => 'Steel Plate 6mm', 'category_id' => 7, 'unit' => 'Pcs', 'unit_price' => 3500.00, 'min_stock_level' => 10, 'max_stock_level' => 100],
            ['code' => 'STL-002', 'name' => 'Steel Angle 50x50x6', 'category_id' => 7, 'unit' => 'Pcs', 'unit_price' => 1200.00, 'min_stock_level' => 15, 'max_stock_level' => 150],
            
            // Wood Category (8)
            ['code' => 'WOD-001', 'name' => 'Plywood 18mm', 'category_id' => 8, 'unit' => 'Pcs', 'unit_price' => 2200.00, 'min_stock_level' => 20, 'max_stock_level' => 200],
            ['code' => 'WOD-002', 'name' => 'Timber 2x4', 'category_id' => 8, 'unit' => 'Pcs', 'unit_price' => 850.00, 'min_stock_level' => 30, 'max_stock_level' => 300],
            
            // Plumbing Category (9)
            ['code' => 'PLB-001', 'name' => 'PVC Pipe 110mm', 'category_id' => 9, 'unit' => 'Mtr', 'unit_price' => 350.00, 'min_stock_level' => 50, 'max_stock_level' => 500],
            ['code' => 'PLB-002', 'name' => 'PVC Pipe 50mm', 'category_id' => 9, 'unit' => 'Mtr', 'unit_price' => 180.00, 'min_stock_level' => 40, 'max_stock_level' => 400],
            
            // Electrical Category (10)
            ['code' => 'ELC-001', 'name' => 'Electrical Cable 2.5mm', 'category_id' => 10, 'unit' => 'Mtr', 'unit_price' => 65.00, 'min_stock_level' => 100, 'max_stock_level' => 1000],
            ['code' => 'ELC-002', 'name' => 'LED Panel Light 60W', 'category_id' => 10, 'unit' => 'Pcs', 'unit_price' => 850.00, 'min_stock_level' => 20, 'max_stock_level' => 200],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        echo "Created " . count($items) . " sample items.\n";
        echo "Creating sample transactions...\n";

        // Create sample transactions for the last 30 days
        $items = Item::all();
        $transactionTypes = ['GRV', 'ISTRV', 'SIV', 'TRANSFER_OUT', 'STORE_RETURN'];
        $locations = [1, 2, 3, 4]; // Head Office, Nefas Silk, EAU South Campus, Main Store
        $references = [
            'GRV' => ['GRV-2024-001', 'GRV-2024-002', 'GRV-2024-003', 'GRV-2024-004', 'GRV-2024-005'],
            'ISTRV' => ['ISTRV-2024-001', 'ISTRV-2024-002', 'ISTRV-2024-003'],
            'SIV' => ['SIV-2024-001', 'SIV-2024-002', 'SIV-2024-003', 'SIV-2024-004'],
            'TRANSFER_OUT' => ['TO-2024-001', 'TO-2024-002', 'TO-2024-003'],
            'STORE_RETURN' => ['SR-2024-001', 'SR-2024-002'],
        ];

        $remarks = [
            'GRV' => ['Supplier delivery', 'Bulk order received', 'Partial delivery', 'Emergency stock'],
            'ISTRV' => ['Transfer from main store', 'Inter-store transfer', 'Project requirement'],
            'SIV' => ['Site issue for slab work', 'Column casting requirement', 'Daily issue', 'Beam work'],
            'TRANSFER_OUT' => ['Transfer to site', 'Excess stock return', 'Project relocation'],
            'STORE_RETURN' => ['Unused material return', 'Quality issue return', 'Excess material'],
        ];

        $transactionCount = 0;

        // Create beginning balances for all items at all locations
        foreach ($items as $item) {
            foreach ($locations as $locationId) {
                if ($locationId != 4) { // Not main store
                    StockTransaction::create([
                        'transaction_date' => Carbon::now()->subDays(30),
                        'transaction_type' => 'BEGINNING_BALANCE',
                        'item_id' => $item->id,
                        'to_location_id' => $locationId,
                        'quantity' => rand(20, 100),
                        'reference_number' => 'OPEN-BAL-' . date('Y'),
                        'remarks' => 'Opening balance',
                        'created_by' => 1, // Admin user
                    ]);
                    $transactionCount++;
                }
            }
        }

        // Create daily transactions for the last 30 days
        for ($days = 29; $days >= 0; $days--) {
            $date = Carbon::now()->subDays($days);
            
            // Create 3-8 transactions per day
            $dailyTransactionCount = rand(3, 8);
            
            for ($i = 0; $i < $dailyTransactionCount; $i++) {
                $item = $items->random();
                $type = $transactionTypes[array_rand($transactionTypes)];
                
                // Determine locations based on transaction type
                switch ($type) {
                    case 'GRV':
                        $fromId = null;
                        $toId = $locations[array_rand($locations)];
                        $qty = rand(5, 50);
                        break;
                        
                    case 'ISTRV':
                        $fromId = 4; // From main store
                        $toId = $locations[array_rand(array_diff($locations, [4]))];
                        $qty = rand(3, 30);
                        break;
                        
                    case 'SIV':
                        $fromId = $locations[array_rand($locations)];
                        $toId = null;
                        $qty = rand(1, 20);
                        break;
                        
                    case 'TRANSFER_OUT':
                        $fromId = $locations[array_rand($locations)];
                        do {
                            $toId = $locations[array_rand($locations)];
                        } while ($toId == $fromId);
                        $qty = rand(2, 25);
                        break;
                        
                    case 'STORE_RETURN':
                        $fromId = $locations[array_rand($locations)];
                        $toId = 4; // Return to main store
                        $qty = rand(1, 15);
                        break;
                        
                    default:
                        $fromId = null;
                        $toId = null;
                        $qty = 0;
                }
                
                $refArray = $references[$type];
                $remarkArray = $remarks[$type];
                
                StockTransaction::create([
                    'transaction_date' => $date,
                    'transaction_type' => $type,
                    'item_id' => $item->id,
                    'from_location_id' => $fromId,
                    'to_location_id' => $toId,
                    'quantity' => $qty,
                    'reference_number' => $refArray[array_rand($refArray)],
                    'document_number' => 'DOC-' . date('Ymd', strtotime($date)) . '-' . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT),
                    'remarks' => $remarkArray[array_rand($remarkArray)],
                    'created_by' => rand(1, 3),
                ]);
                
                $transactionCount++;
            }
        }

        echo "Created $transactionCount sample transactions.\n";
        echo "Sample data added successfully!\n";
    }
}
