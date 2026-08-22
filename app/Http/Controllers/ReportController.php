<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\Item;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function delivery(Request $request)
    {
        $user = auth()->user();
        $accessibleIds = $user->getAccessibleProjectIds();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('reports.delivery', compact('locations', 'categories', 'request'));
    }

    public function quarryDelivery(Request $request)
    {
        $user = auth()->user();
        $accessibleIds = $user->getAccessibleProjectIds();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('reports.quarry-delivery', compact('locations', 'categories', 'request'));
    }

    public function stockLedger(Request $request)
    {
        $user = auth()->user();
        $accessibleIds = $user->getAccessibleProjectIds();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('reports.stock-ledger', compact('locations', 'categories', 'request'));
    }

    public function stockBalance(Request $request)
    {
        $user = auth()->user();
        $accessibleIds = $user->getAccessibleProjectIds();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        if (!$user->isHighLevelRole()) {
            if ($request->location_id && !in_array($request->location_id, $accessibleIds)) {
                $locationId = $accessibleIds[0] ?? 1;
            } else {
                $locationId = $request->location_id ?? ($accessibleIds[0] ?? 1);
            }
        } else {
            $locationId = $request->location_id ?? 1;
        }
        
        $selectedLocation = Location::find($locationId);
        
        // Build items query with search and category filter
        $itemsQuery = Item::with('category')
            ->where('is_active', true);
        
        // Apply search filter
        if ($request->search) {
            $searchTerm = $request->search;
            $itemsQuery->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%");
            });
        }
        
        // Apply category filter
        if ($request->category_id) {
            $itemsQuery->where('category_id', $request->category_id);
        }
        
        // Get items
        $items = $itemsQuery->get()->map(function($item) use ($locationId) {
            $item->current_stock = $this->calculateLocationStock($item->id, $locationId);
            return $item;
        });
        
        // Pagination
        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 100;
        $totalItems = $items->count();
        $totalPages = ceil($totalItems / $perPage);
        $paginatedItems = $items->forPage($page, $perPage);

        return view('reports.stock-balance', compact(
            'items', 'paginatedItems', 'locations', 'categories', 'request',
            'locationId', 'selectedLocation', 'page', 'perPage', 'totalItems', 'totalPages'
        ));
    }

    public function weeklyTransfer(Request $request)
    {
        $user = auth()->user();
        $accessibleIds = $user->getAccessibleProjectIds();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        return view('reports.weekly-transfer', compact('locations'));
    }

    public function weeklyStockStatus(Request $request)
    {
        $user = auth()->user();
        $accessibleIds = $user->getAccessibleProjectIds();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        return view('reports.weekly-stock-status', compact('locations'));
    }

    public function weeklyReport(Request $request)
    {
        $user = auth()->user();
        $accessibleIds = $user->getAccessibleProjectIds();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $dateFrom = $request->date_from ?? Carbon::now('Africa/Addis_Ababa')->startOfWeek()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now('Africa/Addis_Ababa')->endOfWeek()->format('Y-m-d');
        $weekNumber = Carbon::parse($dateFrom)->weekOfYear;
        
        $summary = [
            'total_grv' => StockTransaction::where('transaction_type', 'GRV')->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_siv' => StockTransaction::where('transaction_type', 'SIV')->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_transfer' => StockTransaction::where('transaction_type', 'TRANSFER_OUT')->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_return' => StockTransaction::whereIn('transaction_type', ['STORE_RETURN', 'SRV'])->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_transactions' => StockTransaction::whereBetween('transaction_date', [$dateFrom, $dateTo])->count(),
        ];
        
        return view('reports.weekly-report', compact('locations', 'categories', 'dateFrom', 'dateTo', 'weekNumber', 'summary'));
    }

    public function monthlyReport(Request $request)
    {
        $user = auth()->user();
        $accessibleIds = $user->getAccessibleProjectIds();
        
        if ($user->isHighLevelRole()) {
            $locations = Location::where('is_active', true)->orderBy('code')->get();
        } else {
            $locations = Location::whereIn('id', $accessibleIds)->where('is_active', true)->orderBy('code')->get();
        }
        
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $dateFrom = $request->date_from ?? Carbon::now('Africa/Addis_Ababa')->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now('Africa/Addis_Ababa')->endOfMonth()->format('Y-m-d');
        $monthName = Carbon::parse($dateFrom)->format('F Y');
        
        $summary = [
            'total_grv' => StockTransaction::where('transaction_type', 'GRV')->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_siv' => StockTransaction::where('transaction_type', 'SIV')->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_transfer' => StockTransaction::where('transaction_type', 'TRANSFER_OUT')->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_return' => StockTransaction::whereIn('transaction_type', ['STORE_RETURN', 'SRV'])->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_istrv' => StockTransaction::where('transaction_type', 'ISTRV')->whereBetween('transaction_date', [$dateFrom, $dateTo])->sum('quantity'),
            'total_transactions' => StockTransaction::whereBetween('transaction_date', [$dateFrom, $dateTo])->count(),
        ];
        
        return view('reports.monthly-report', compact('locations', 'categories', 'dateFrom', 'dateTo', 'monthName', 'summary'));
    }

    private function calculateLocationStock($itemId, $locationId)
    {
        $inTypes = ['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE', 'SRV', 'TTRV', 'FARV', 'UMTRV', 'FGRV', 'FRV'];
        $outTypes = ['SIV', 'TRANSFER_OUT', 'FIV', 'UMIV', 'UMTV'];
        
        $received = StockTransaction::where('item_id', $itemId)
            ->where('to_location_id', $locationId)
            ->whereIn('transaction_type', $inTypes)
            ->sum('quantity');
        
        $issued = StockTransaction::where('item_id', $itemId)
            ->where('from_location_id', $locationId)
            ->whereIn('transaction_type', $outTypes)
            ->sum('quantity');
        
        return max(0, round($received - $issued, 2));
    }
}
