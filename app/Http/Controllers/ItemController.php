<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActivityLogger;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemsImport;

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
        // Use pagination and eager loading for performance
        $items = Item::with(['category:id,name'])
            ->select(['items.id', 'items.code', 'items.name', 'items.category_id', 
                      'items.unit', 'items.item_type', 'items.unit_price',
                      'items.min_stock_level', 'items.max_stock_level', 'items.is_active']);
        
        if ($request->category_id) {
            $items->where('category_id', $request->category_id);
        }
        if ($request->item_type) {
            $items->where('item_type', $request->item_type);
        }

        return DataTables::of($items)
            ->addColumn('category_name', fn($item) => $item->category->name ?? 'N/A')
            ->addColumn('current_stock', function($item) {
                // Use caching for stock calculation
                $cacheKey = 'item_stock_' . $item->id . '_' . (auth()->user()->location_id ?? 1);
                return cache()->remember($cacheKey, 300, function() use ($item) {
                    $locationId = auth()->user()->location_id ?? 1;
                    return number_format($item->getCurrentStock($locationId), 2);
                });
            })
            ->addColumn('status', function($item) {
                $locationId = auth()->user()->location_id ?? 1;
                $stock = $item->getCurrentStock($locationId);
                if ($stock <= 0) return '<span class="badge bg-danger">Out</span>';
                if ($stock <= $item->min_stock_level) return '<span class="badge bg-warning">Low</span>';
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
            'item_type' => 'required|in:regular,fixed_asset,used_material,fuel',
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
            'item_type' => 'required|in:regular,fixed_asset,used_material,fuel',
        ]);

        $item->update($request->all());

        ActivityLogger::log('UPDATE', 'Item updated: ' . $item->name, 'ITEM', $item->id, $item->name, 'Items Management');

        return response()->json(['success' => true, 'message' => 'Item updated successfully!']);
    }

    public function updatePrice(Request $request, $id)
    {
        try {
            $request->validate(['unit_price' => 'required|numeric|min:0.01']);
            $item = Item::findOrFail($id);
            $item->unit_price = $request->unit_price;
            $item->save();
            return response()->json(['success' => true, 'message' => 'Price updated!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update price'], 500);
        }
    }

    public function destroy($id)
    {
        Item::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Item deleted!']);
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $items = Item::where('is_active', true)
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->limit(30)
            ->get(['id', 'code', 'name', 'unit', 'category_id', 'item_type']);

        return response()->json($items);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv']);

        try {
            Excel::import(new ItemsImport(), $request->file('file'));
            return response()->json(['success' => true, 'message' => 'Items imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Import failed: ' . $e->getMessage()], 422);
        }
    }
}
