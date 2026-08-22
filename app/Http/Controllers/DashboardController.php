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
        $isReportOnly = $user->hasAnyRole(['gm', 'manager', 'checker']);
        
        // Base stats
        $totalItems = Item::where('is_active', true)->count();
        $totalLocations = count($accessibleIds);
        
        // Transaction stats
        if ($user->isHighLevelRole()) {
            $todayTransactions = StockTransaction::whereDate('created_at', Carbon::today())->count();
            $weekTransactions = StockTransaction::whereBetween('created_at', [Carbon::now('Africa/Addis_Ababa')->startOfWeek(), Carbon::now('Africa/Addis_Ababa')->endOfWeek()])->count();
            $monthTransactions = StockTransaction::whereMonth('created_at', Carbon::now('Africa/Addis_Ababa')->month)->count();
        } else {
            $todayTransactions = StockTransaction::whereDate('created_at', Carbon::today())
                ->where(function($q) use ($accessibleIds) {
                    $q->whereIn('from_location_id', $accessibleIds)
                      ->orWhereIn('to_location_id', $accessibleIds);
                })->count();
            $weekTransactions = StockTransaction::whereBetween('created_at', [Carbon::now('Africa/Addis_Ababa')->startOfWeek(), Carbon::now('Africa/Addis_Ababa')->endOfWeek()])
                ->where(function($q) use ($accessibleIds) {
                    $q->whereIn('from_location_id', $accessibleIds)
                      ->orWhereIn('to_location_id', $accessibleIds);
                })->count();
            $monthTransactions = StockTransaction::whereMonth('created_at', Carbon::now('Africa/Addis_Ababa')->month)
                ->where(function($q) use ($accessibleIds) {
                    $q->whereIn('from_location_id', $accessibleIds)
                      ->orWhereIn('to_location_id', $accessibleIds);
                })->count();
        }
        
        // Recent transactions (limit 10)
        if ($isReportOnly) {
            $recentTransactions = StockTransaction::with(['item', 'fromLocation', 'toLocation'])
                ->latest()->take(10)->get();
        } elseif (!$user->isHighLevelRole()) {
            $recentTransactions = StockTransaction::with(['item', 'fromLocation', 'toLocation'])
                ->where(function($q) use ($accessibleIds) {
                    $q->whereIn('from_location_id', $accessibleIds)
                      ->orWhereIn('to_location_id', $accessibleIds);
                })
                ->latest()->take(10)->get();
        } else {
            $recentTransactions = StockTransaction::with(['item', 'fromLocation', 'toLocation'])
                ->latest()->take(10)->get();
        }
        
        // Low stock items - LIMITED TO 5 for dashboard
        $allLowStockItems = Item::where('is_active', true)
            ->get()
            ->filter(function($item) use ($locationId) {
                return $item->getCurrentStock($locationId) <= $item->min_stock_level;
            });
        
        // Show only 5 on dashboard
        $lowStockItems = $allLowStockItems->take(5);
        $totalLowStockCount = $allLowStockItems->count();
        
        $userRole = $user->roles->first()->name ?? 'user';
        $roleLabel = ucwords(str_replace('_', ' ', $userRole));
        
        return view('dashboard', compact(
            'totalItems',
            'totalLocations',
            'todayTransactions',
            'weekTransactions',
            'monthTransactions',
            'recentTransactions',
            'lowStockItems',
            'totalLowStockCount',
            'isReportOnly',
            'userRole',
            'roleLabel'
        ));
    }
}
