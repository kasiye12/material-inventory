@extends('layouts.app')
@section('title', 'Stock Ledger')
@section('page-title', 'Project Material Ledger')

@section('content')
<!-- Filter Form -->
<div class="card mb-3 no-print">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Report Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.stock-ledger') }}" class="row align-items-end">
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from', date('Y-m-01')) }}">
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">Project/Location</label>
                <select class="form-select select2-search" name="location_id" style="width: 100%;">
                    <option value="">🏢 All Locations</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">Category</label>
                <select class="form-select" name="category_id">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">🔍 Search Item</label>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search...">
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@php
    $hasFilters = request('date_from') || request('location_id') || request('search') || request('category_id');
    $page = request('page', 1);
    $perPage = request('per_page', 50);
    $dateFrom = request('date_from', date('Y-m-01'));
    $dateTo = request('date_to', date('Y-m-d'));
    $locationId = request('location_id'); // Can be null = All Locations
    
    $user = auth()->user();
    $accessibleIds = $user->getAccessibleProjectIds();
    
    // If no location selected, show ALL locations combined
    // If location selected and user has access, show that location
    if ($locationId && !$user->isHighLevelRole() && !in_array($locationId, $accessibleIds)) {
        $locationId = null; // Reset to all locations if no access
    }
    
    $locationName = $locationId ? App\Models\Location::find($locationId)->name : 'All Locations';
@endphp

@if($hasFilters || request('date_from'))
    <div class="no-print mb-3 d-flex gap-2 justify-content-end">
        <button class="btn btn-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('reports.ledger.export', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'location_id' => $locationId, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('reports.ledger.export', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'location_id' => $locationId, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel me-1"></i> Excel
        </a>
    </div>

    <div class="print-area">
        <div class="card">
            <div class="card-body p-4">
                <!-- Header -->
                <div style="display: flex; align-items: center; justify-content: center; border-bottom: 3px double #1e293b; padding-bottom: 10px; margin-bottom: 10px; position: relative;">
                    <div style="position: absolute; left: 0;">
                        <img src="{{ asset('images/company-logo.png') }}" alt="Logo" style="width: 70px; height: 45px;">
                    </div>
                    <div style="text-align: center;">
                        <h5 style="font-weight: bold; margin: 0; font-size: 14px;">ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች</h5>
                        <p style="font-style: italic; margin: 0; font-size: 11px;">TNT Construction & Trading</p>
                    </div>
                    <div style="position: absolute; right: 0; font-size: 9px;">
                        <strong>Document No:</strong> OF/TNT/SUP/033
                    </div>
                </div>
                
                <div style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 13px; margin-bottom: 5px;">
                    {{ $locationName }}
                </div>
                
                @php
                    $allInTypes = ['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE', 'SRV', 'TTRV', 'FARV', 'UMTRV', 'FGRV', 'FRV'];
                    $allOutTypes = ['SIV', 'TRANSFER_OUT', 'FIV', 'UMIV', 'UMTV'];
                    
                    $itemsQuery = App\Models\Item::with('category')->where('is_active', true);
                    
                    if (request('category_id')) {
                        $itemsQuery->where('category_id', request('category_id'));
                    }
                    if (request('search')) {
                        $searchTerm = request('search');
                        $itemsQuery->where(function($q) use ($searchTerm) {
                            $q->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('code', 'like', "%{$searchTerm}%");
                        });
                    }
                    
                    $allItems = $itemsQuery->orderBy('category_id')->orderBy('name')->get();
                    $totalItems = $allItems->count();
                    $totalPages = ceil($totalItems / $perPage);
                    $items = $allItems->forPage($page, $perPage);
                    $groupedItems = $items->groupBy(fn($item) => $item->category->name ?? 'Uncategorized');
                @endphp
                
                <div class="alert alert-info no-print py-1 mb-2" style="font-size: 11px;">
                    Showing {{ $items->count() }} of {{ $totalItems }} items | Page {{ $page }} of {{ $totalPages }}
                    @if($locationId)
                    | Location: <strong>{{ $locationName }}</strong>
                    @else
                    | <strong>All Locations Combined</strong>
                    @endif
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 8px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th rowspan="2">Item No.</th>
                                <th rowspan="2">Description</th>
                                <th rowspan="2">Unit</th>
                                <th rowspan="2">Delivery Date</th>
                                <th rowspan="2">Beg. Balance</th>
                                <th colspan="2">GRV</th>
                                <th colspan="2">ISTRV</th>
                                <th colspan="2">SIV</th>
                                <th colspan="2">Transferred Out</th>
                                <th colspan="2">Store return</th>
                                <th rowspan="2">Ending Balance</th>
                                <th rowspan="2">Remark</th>
                            </tr>
                            <tr style="background: #4b5563; color: #fff;">
                                <th>Ref</th><th>Qty</th>
                                <th>Ref</th><th>Qty</th>
                                <th>Ref</th><th>Qty</th>
                                <th>Ref</th><th>Qty</th>
                                <th>Ref</th><th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groupedItems as $category => $categoryItems)
                            <tr style="background: #e5e7eb; font-weight: bold;">
                                <td colspan="16">{{ $category }}</td>
                            </tr>
                            @php $categoryCounter = 1; @endphp
                            @foreach($categoryItems as $item)
                            @php
                                // If locationId is null (All Locations), sum across ALL locations
                                $openingQuery = App\Models\StockTransaction::where('item_id', $item->id)
                                    ->where('transaction_date', '<', $dateFrom);
                                    
                                if ($locationId) {
                                    $openingQuery->where('to_location_id', $locationId);
                                }
                                $openingReceived = (clone $openingQuery)->whereIn('transaction_type', $allInTypes)->sum('quantity');
                                
                                $openingIssueQuery = App\Models\StockTransaction::where('item_id', $item->id)
                                    ->where('transaction_date', '<', $dateFrom);
                                if ($locationId) {
                                    $openingIssueQuery->where('from_location_id', $locationId);
                                }
                                $openingIssued = (clone $openingIssueQuery)->whereIn('transaction_type', $allOutTypes)->sum('quantity');
                                
                                $openingBalance = max(0, $openingReceived - $openingIssued);
                                
                                // Period transactions
                                $periodQuery = App\Models\StockTransaction::where('item_id', $item->id)
                                    ->whereBetween('transaction_date', [$dateFrom, $dateTo]);
                                    
                                if ($locationId) {
                                    $periodQuery->where(function($q) use ($locationId) {
                                        $q->where('from_location_id', $locationId)
                                          ->orWhere('to_location_id', $locationId);
                                    });
                                }
                                $periodTransactions = $periodQuery->get();
                                
                                $grvQty = $periodTransactions->where('transaction_type','GRV')->sum('quantity');
                                $grvRef = $periodTransactions->where('transaction_type','GRV')->pluck('reference_number')->filter()->first() ?? '';
                                
                                $istrvQty = $periodTransactions->where('transaction_type','ISTRV')->sum('quantity');
                                $istrvRef = $periodTransactions->where('transaction_type','ISTRV')->pluck('reference_number')->filter()->first() ?? '';
                                
                                $sivQty = $periodTransactions->where('transaction_type','SIV')->sum('quantity');
                                $sivRef = $periodTransactions->where('transaction_type','SIV')->pluck('reference_number')->filter()->first() ?? '';
                                
                                $transferQty = $periodTransactions->whereIn('transaction_type',['TRANSFER_OUT','UMTV'])->sum('quantity');
                                $transferRef = $periodTransactions->whereIn('transaction_type',['TRANSFER_OUT','UMTV'])->pluck('reference_number')->filter()->first() ?? '';
                                
                                $returnQty = $periodTransactions->whereIn('transaction_type',['STORE_RETURN','SRV'])->sum('quantity');
                                $returnRef = $periodTransactions->whereIn('transaction_type',['STORE_RETURN','SRV'])->pluck('reference_number')->filter()->first() ?? '';
                                
                                // Other IN types
                                $otherInQty = $periodTransactions->whereIn('transaction_type',['FARV','UMTRV','FRV','TTRV','FGRV'])->sum('quantity');
                                // Other OUT types
                                $otherOutQty = $periodTransactions->whereIn('transaction_type',['FIV','UMIV'])->sum('quantity');
                                
                                $totalPeriodIn = $grvQty + $istrvQty + $returnQty + $otherInQty;
                                $totalPeriodOut = $sivQty + $transferQty + $otherOutQty;
                                $endingBalance = max(0, $openingBalance + $totalPeriodIn - $totalPeriodOut);
                                
                                $deliveryDate = $periodTransactions->sortBy('transaction_date')->first() 
                                    ? $periodTransactions->sortBy('transaction_date')->first()->transaction_date->format('d/m/Y') 
                                    : '';
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $categoryCounter++ }}</td>
                                <td>{{ $item->name }}</td>
                                <td style="text-align: center;">{{ $item->unit }}</td>
                                <td style="text-align: center;">{{ $deliveryDate }}</td>
                                <td style="text-align: right; font-weight: bold;">
                                    {{ $openingBalance > 0 ? number_format($openingBalance, 2) : '0' }}
                                </td>
                                <td style="text-align: center; font-size: 7px;">{{ $grvRef }}</td>
                                <td style="text-align: right;">{{ $grvQty > 0 ? number_format($grvQty, 2) : '' }}</td>
                                <td style="text-align: center; font-size: 7px;">{{ $istrvRef }}</td>
                                <td style="text-align: right;">{{ $istrvQty > 0 ? number_format($istrvQty, 2) : '' }}</td>
                                <td style="text-align: center; font-size: 7px;">{{ $sivRef }}</td>
                                <td style="text-align: right;">{{ $sivQty > 0 ? number_format($sivQty, 2) : '' }}</td>
                                <td style="text-align: center; font-size: 7px;">{{ $transferRef }}</td>
                                <td style="text-align: right;">{{ $transferQty > 0 ? number_format($transferQty, 2) : '' }}</td>
                                <td style="text-align: center; font-size: 7px;">{{ $returnRef }}</td>
                                <td style="text-align: right;">{{ $returnQty > 0 ? number_format($returnQty, 2) : '' }}</td>
                                <td style="text-align: right; font-weight: bold;">
                                    {{ $endingBalance > 0 ? number_format($endingBalance, 2) : '0' }}
                                </td>
                                <td></td>
                            </tr>
                            @endforeach
                            @empty
                            <tr><td colspan="16" style="text-align: center; padding: 20px;">No items found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($totalPages > 1)
                <nav class="no-print mt-3">
                    <ul class="pagination justify-content-center">
                        @if($page > 1)
                        <li class="page-item"><a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}">Previous</a></li>
                        @endif
                        @for($i = max(1, $page - 5); $i <= min($totalPages, $page + 5); $i++)
                        <li class="page-item {{ $page == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                        </li>
                        @endfor
                        @if($page < $totalPages)
                        <li class="page-item"><a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}">Next</a></li>
                        @endif
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info no-print">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Select date range and click "Generate" to view the Stock Ledger.</strong>
        <br><br>
        <strong>Location Options:</strong>
        <ul class="mb-0">
            <li><strong>All Locations</strong> - Combined balance across all projects</li>
            <li><strong>Specific Location</strong> - Balance for one project only</li>
        </ul>
    </div>
@endif

<style>
@media print {
    body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .no-print, .breadcrumb, #sidebar, .dropdown { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2-search').select2({
        placeholder: '🔍 Search project...',
        allowClear: true,
        width: '100%',
    });
});
</script>
@endpush
@endsection
