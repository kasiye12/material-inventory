<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', function () { return view('profile'); })->name('profile');
    Route::get('/test-amharic', function () {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('test-amharic');
        return $pdf->download('amharic-test.pdf');
    });
    Route::get('/help', function () { return view('help'); })->name('help');
    
    // Items with price update route
    Route::get('/items/data', [ItemController::class, 'getData'])->name('items.data');
    Route::get('/items/search', [ItemController::class, 'search'])->name('items.search');
    Route::post('/items/{id}/update-price', [ItemController::class, 'updatePrice'])->name('items.update-price');
    Route::resource('items', ItemController::class);
    
    // Categories
    Route::get('/categories/data', [CategoryController::class, 'getData'])->name('categories.data');
    Route::resource('categories', CategoryController::class);
    
    // Locations
    Route::get('/locations/data', [LocationController::class, 'getData'])->name('locations.data');
    Route::get('/locations/search', [StockTransactionController::class, 'searchLocations'])->name('locations.search');
    Route::resource('locations', LocationController::class);
    
    // Transactions
    Route::get('/transactions/data', [StockTransactionController::class, 'getData'])->name('transactions.data');
    Route::resource('transactions', StockTransactionController::class);
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/delivery', [ReportController::class, 'delivery'])->name('delivery');
        Route::get('/stock-ledger', [ReportController::class, 'stockLedger'])->name('stock-ledger');
        Route::get('/stock-balance', [ReportController::class, 'stockBalance'])->name('stock-balance');
        Route::get('/weekly-transfer', [ReportController::class, 'weeklyTransfer'])->name('weekly-transfer');
        Route::get('/weekly-stock-status', [ReportController::class, 'weeklyStockStatus'])->name('weekly-stock-status');
        Route::get('/weekly-report', [ReportController::class, 'weeklyReport'])->name('weekly-report');
        Route::get('/monthly-report', [ReportController::class, 'monthlyReport'])->name('monthly-report');
        
        Route::get('/delivery/export', [ReportExportController::class, 'exportDeliveryReport'])->name('delivery.export');
        Route::get('/ledger/export', [ReportExportController::class, 'exportProjectLedger'])->name('ledger.export');
        Route::get('/weekly-transfer/export', [ReportExportController::class, 'exportWeeklyTransfer'])->name('weekly-transfer.export');
        Route::get('/weekly-stock-status/export', [ReportExportController::class, 'exportWeeklyStockStatus'])->name('weekly-stock-status.export');
    });
    
    // Users
    Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
    Route::resource('users', UserController::class);
    
    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/data', [ActivityLogController::class, 'getData'])->name('activity-logs.data');
});
