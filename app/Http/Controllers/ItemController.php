<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActivityLogger;

class ItemController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('items.index', compact('categories'));
    }

    public function create()
    {
        return redirect()->route('items.index');
    }

    public function getData(Request $request)
    {
        $items = Item::with('category')->select('items.*');
        
        if ($request->category_id) {
            $items->where('category_id', $request->category_id);
        }

        return DataTables::of($items)
            ->addColumn('category_name', fn($item) => $item->category->name ?? 'N/A')
            ->addColumn('current_stock', function($item) {
                $locationId = auth()->user()->location_id ?? 1;
                return number_format($item->getCurrentStock($locationId), 2);
            })
            ->addColumn('status', function($item) {
                $locationId = auth()->user()->location_id ?? 1;
                $stock = $item->getCurrentStock($locationId);
                if ($stock <= 0) return '<span class="badge bg-danger">Out of Stock</span>';
                if ($stock <= $item->min_stock_level) return '<span class="badge bg-warning">Low Stock</span>';
                return '<span class="badge bg-success">In Stock</span>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:items,code',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:20',
        ]);

        $item = Item::create($request->all());

        ActivityLogger::log('CREATE', 'Item created: ' . $item->name, 'ITEM', $item->id, $item->name, 'Items Management');

        return response()->json(['success' => true, 'message' => 'Item created successfully!']);
    }

    public function show($id)
    {
        $item = Item::with('category')->findOrFail($id);
        $locationId = auth()->user()->location_id ?? 1;
        $currentStock = $item->getCurrentStock($locationId);
        
        return response()->json([
            'item' => $item,
            'current_stock' => $currentStock
        ]);
    }

    public function edit($id)
    {
        return response()->json(Item::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:items,code,'.$id,
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:20',
        ]);

        $item->update($request->all());

        ActivityLogger::log('UPDATE', 'Item updated: ' . $item->name, 'ITEM', $item->id, $item->name, 'Items Management');

        return response()->json(['success' => true, 'message' => 'Item updated successfully!']);
    }

    /**
     * Update only the price of an item
     */
    public function updatePrice(Request $request, $id)
    {
        try {
            $request->validate([
                'unit_price' => 'required|numeric|min:0.01',
            ]);
            
            $item = Item::findOrFail($id);
            $oldPrice = $item->unit_price;
            $item->unit_price = $request->unit_price;
            $item->save();
            
            ActivityLogger::log(
                'UPDATE', 
                'Item price updated: ' . $item->name . ' (ETB ' . $oldPrice . ' → ETB ' . $request->unit_price . ')',
                'ITEM', $item->id, $item->name, 
                'Items Management'
            );
            
            return response()->json([
                'success' => true, 
                'message' => 'Price updated successfully! New price: ETB ' . number_format($item->unit_price, 2)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to update price: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        
        ActivityLogger::log('DELETE', 'Item deleted: ' . $item->name, 'ITEM', $item->id, $item->name, 'Items Management');
        
        $item->delete();
        
        return response()->json(['success' => true, 'message' => 'Item deleted successfully!']);
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $items = Item::where('is_active', true)
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get(['id', 'code', 'name', 'unit', 'category_id']);

        return response()->json($items);
    }
}
