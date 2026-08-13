<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $locationId = $user->location_id ?? 1;
        $accessibleIds = $user->getAccessibleProjectIds();
        
        // Stats
        $totalItems = Item::where('is_active', true)->count();
        $totalLocations = count($accessibleIds);
        
        $todayTransactions = StockTransaction::whereDate('created_at', Carbon::today())
            ->when(!$user->isHighLevelRole(), function($q) use ($accessibleIds) {
                return $q->where(function($sub) use ($accessibleIds) {
                    $sub->whereIn('from_location_id', $accessibleIds)
                        ->orWhereIn('to_location_id', $accessibleIds);
                });
            })
            ->count();
        
        // This week transactions
        $weekTransactions = StockTransaction::whereBetween('created_at', [Carbon::now('Africa/Addis_Ababa')->startOfWeek(), Carbon::now('Africa/Addis_Ababa')->endOfWeek()])
            ->when(!$user->isHighLevelRole(), function($q) use ($accessibleIds) {
                return $q->where(function($sub) use ($accessibleIds) {
                    $sub->whereIn('from_location_id', $accessibleIds)
                        ->orWhereIn('to_location_id', $accessibleIds);
                });
            })
            ->count();
        
        // This month transactions
        $monthTransactions = StockTransaction::whereMonth('created_at', Carbon::now('Africa/Addis_Ababa')->month)
            ->whereYear('created_at', Carbon::now('Africa/Addis_Ababa')->year)
            ->when(!$user->isHighLevelRole(), function($q) use ($accessibleIds) {
                return $q->where(function($sub) use ($accessibleIds) {
                    $sub->whereIn('from_location_id', $accessibleIds)
                        ->orWhereIn('to_location_id', $accessibleIds);
                });
            })
            ->count();
        
        // Recent transactions
        $recentTransactions = StockTransaction::with(['item', 'fromLocation', 'toLocation', 'creator'])
            ->when(!$user->isHighLevelRole(), function($q) use ($accessibleIds) {
                return $q->where(function($sub) use ($accessibleIds) {
                    $sub->whereIn('from_location_id', $accessibleIds)
                        ->orWhereIn('to_location_id', $accessibleIds);
                });
            })
            ->latest()
            ->take(10)
            ->get();
        
        // Low stock items
        $lowStockItems = Item::where('is_active', true)
            ->get()
            ->filter(function($item) use ($locationId) {
                return $item->getCurrentStock($locationId) <= $item->min_stock_level;
            });
        
        // Transaction type summary
        $typeSummary = StockTransaction::whereMonth('created_at', Carbon::now('Africa/Addis_Ababa')->month)
            ->when(!$user->isHighLevelRole(), function($q) use ($accessibleIds) {
                return $q->where(function($sub) use ($accessibleIds) {
                    $sub->whereIn('from_location_id', $accessibleIds)
                        ->orWhereIn('to_location_id', $accessibleIds);
                });
            })
            ->selectRaw('transaction_type, COUNT(*) as count, SUM(quantity) as total_qty')
            ->groupBy('transaction_type')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->transaction_type => [
                    'count' => $item->count,
                    'total_qty' => $item->total_qty
                ]];
            });

        return view('dashboard', compact(
            'totalItems',
            'totalLocations',
            'todayTransactions',
            'weekTransactions',
            'monthTransactions',
            'recentTransactions',
            'lowStockItems',
            'typeSummary'
        ));
    }
}
