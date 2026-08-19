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
                    <option value="">🔍 Search project...</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">Category</label>
                <select class="form-select" name="category_id">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Generate
                </button>
            </div>
        </form>
    </div>
</div>

@php
    $hasFilters = request('date_from') || request('location_id') || request('category_id');
@endphp

@if($hasFilters)
    <div class="no-print mb-3 d-flex gap-2 justify-content-end">
        <button class="btn btn-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('reports.ledger.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'location_id' => request('location_id'), 'category_id' => request('category_id'), 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('reports.ledger.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'location_id' => request('location_id'), 'category_id' => request('category_id'), 'format' => 'excel']) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel me-1"></i> Excel
        </a>
    </div>

    <div class="print-area">
        <div class="card">
            <div class="card-body p-4">
                <!-- Header: Company Name + Document No -->
                <div style="display: flex; align-items: center; justify-content: center; border-bottom: 3px double #1e293b; padding-bottom: 10px; margin-bottom: 10px; position: relative;">
                    <div style="position: absolute; left: 0;">
                        <img src="{{ asset('images/company-logo.png') }}" alt="Logo" style="width: 70px; height: 45px;">
                    </div>
                    <div style="text-align: center;">
                        <h5 style="font-weight: bold; margin: 0; font-size: 14px;">ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች</h5>
                        <p style="font-style: italic; margin: 0; font-size: 11px;">TNT Construction & Trading</p>
                    </div>
                    <div style="position: absolute; right: 0; font-size: 9px; text-align: right;">
                        <strong>Document No:</strong> OF/TNT/SUP/033
                    </div>
                </div>
                
                <!-- Project Name -->
                @php
                    $locationName = request('location_id') ? App\Models\Location::find(request('location_id'))->name : 'All Locations';
                @endphp
                
                <div style="text-align: center; font-weight: bold; text-decoration: underline; font-size: 13px; margin-bottom: 10px;">
                    {{ $locationName }}
                </div>
                
                @php
                    $user = auth()->user();
                    $accessibleIds = $user->getAccessibleProjectIds();
                    $locationId = request('location_id');
                    
                    if (!$user->isHighLevelRole() && (!$locationId || !in_array($locationId, $accessibleIds))) {
                        $locationId = $accessibleIds[0] ?? 1;
                    }
                    
                    $dateFrom = request('date_from', date('Y-m-01'));
                    $dateTo = request('date_to', date('Y-m-d'));
                    
                    $items = App\Models\Item::with('category')
                        ->where('is_active', true)
                        ->when(request('category_id'), fn($q) => $q->where('category_id', request('category_id')))
                        ->orderBy('category_id')->orderBy('name')->get();
                    
                    $groupedItems = $items->groupBy(fn($item) => $item->category->name ?? 'Uncategorized');
                @endphp
                
                <!-- Table with exact format -->
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 8px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th rowspan="2" style="text-align: center; width: 40px;">Item No.</th>
                                <th rowspan="2">Description</th>
                                <th rowspan="2" style="text-align: center; width: 40px;">Unit</th>
                                <th rowspan="2" style="text-align: center; width: 70px;">Delivery Issued Date</th>
                                <th rowspan="2" style="text-align: center; width: 55px;">Beg. Balance</th>
                                <th colspan="2" style="text-align: center;">GRV</th>
                                <th colspan="2" style="text-align: center;">ISTRV</th>
                                <th colspan="2" style="text-align: center;">SIV</th>
                                <th colspan="2" style="text-align: center;">Transferred Out</th>
                                <th colspan="2" style="text-align: center;">Store return</th>
                                <th rowspan="2" style="text-align: center; width: 55px;">Ending Balance</th>
                                <th rowspan="2" style="text-align: center; width: 50px;">Remark</th>
                            </tr>
                            <tr style="background: #4b5563; color: #fff;">
                                <th style="text-align: center;">Pad Ref.No.</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">Pad Ref.No.</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">Pad Ref.No.</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">Pad Ref.No.</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">Pad Ref.No.</th>
                                <th style="text-align: center;">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groupedItems as $category => $categoryItems)
                            <tr style="background: #e5e7eb; font-weight: bold; text-transform: uppercase;">
                                <td colspan="16">{{ $category }}</td>
                            </tr>
                            @php $counter = 1; @endphp
                            @foreach($categoryItems as $item)
                            @php
                                $inTypes = ['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE', 'SRV', 'TTRV', 'FARV', 'UMTRV', 'FGRV', 'FRV'];
                                $outTypes = ['SIV', 'TRANSFER_OUT', 'FIV', 'UMIV', 'UMTV'];
                                
                                $openingBalance = App\Models\StockTransaction::where('item_id', $item->id)
                                    ->where('transaction_date', '<', $dateFrom)
                                    ->when($locationId, fn($q) => $q->where('to_location_id', $locationId))
                                    ->whereIn('transaction_type', $inTypes)->sum('quantity')
                                    - App\Models\StockTransaction::where('item_id', $item->id)
                                    ->where('transaction_date', '<', $dateFrom)
                                    ->when($locationId, fn($q) => $q->where('from_location_id', $locationId))
                                    ->whereIn('transaction_type', $outTypes)->sum('quantity');
                                
                                $transactions = App\Models\StockTransaction::where('item_id', $item->id)
                                    ->whereBetween('transaction_date', [$dateFrom, $dateTo])
                                    ->when($locationId, fn($q) => $q->where(fn($sub) => $sub->where('from_location_id', $locationId)->orWhere('to_location_id', $locationId)))
                                    ->get();
                                
                                $grvRef = $transactions->where('transaction_type','GRV')->pluck('reference_number')->filter()->implode(', ');
                                $grvQty = $transactions->where('transaction_type','GRV')->sum('quantity');
                                
                                $istrvRef = $transactions->where('transaction_type','ISTRV')->pluck('reference_number')->filter()->implode(', ');
                                $istrvQty = $transactions->where('transaction_type','ISTRV')->sum('quantity');
                                
                                $sivRef = $transactions->where('transaction_type','SIV')->pluck('reference_number')->filter()->implode(', ');
                                $sivQty = $transactions->where('transaction_type','SIV')->sum('quantity');
                                
                                $transferRef = $transactions->whereIn('transaction_type',['TRANSFER_OUT','UMTV'])->pluck('reference_number')->filter()->implode(', ');
                                $transferQty = $transactions->whereIn('transaction_type',['TRANSFER_OUT','UMTV'])->sum('quantity');
                                
                                $returnRef = $transactions->whereIn('transaction_type',['STORE_RETURN','SRV'])->pluck('reference_number')->filter()->implode(', ');
                                $returnQty = $transactions->whereIn('transaction_type',['STORE_RETURN','SRV'])->sum('quantity');
                                
                                $totalReceived = $grvQty + $istrvQty + $returnQty;
                                $totalIssued = $sivQty + $transferQty;
                                $endingBalance = max(0, $openingBalance + $totalReceived - $totalIssued);
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $counter++ }}</td>
                                <td>{{ $item->name }}</td>
                                <td style="text-align: center;">{{ $item->unit }}</td>
                                <td></td>
                                <td style="text-align: right;">{{ max(0, $openingBalance) ?: '' }}</td>
                                <td style="font-size: 6px;">{{ $grvRef }}</td>
                                <td style="text-align: right;">{{ $grvQty ?: '' }}</td>
                                <td style="font-size: 6px;">{{ $istrvRef }}</td>
                                <td style="text-align: right;">{{ $istrvQty ?: '' }}</td>
                                <td style="font-size: 6px;">{{ $sivRef }}</td>
                                <td style="text-align: right;">{{ $sivQty ?: '' }}</td>
                                <td style="font-size: 6px;">{{ $transferRef }}</td>
                                <td style="text-align: right;">{{ $transferQty ?: '' }}</td>
                                <td style="font-size: 6px;">{{ $returnRef }}</td>
                                <td style="text-align: right;">{{ $returnQty ?: '' }}</td>
                                <td style="text-align: right; font-weight: bold;">{{ $endingBalance ?: '' }}</td>
                                <td></td>
                            </tr>
                            @endforeach
                            @empty
                            <tr><td colspan="16" style="text-align: center; padding: 20px;">No items found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info no-print">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Select filters and click "Generate" to view the Project Material Ledger.</strong>
    </div>
@endif

<style>
@media print {
    body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .no-print, .breadcrumb, #sidebar, .dropdown { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
    .card { box-shadow: none !important; border: none !important; }
    .table th { background: #4b5563 !important; color: #fff !important; -webkit-print-color-adjust: exact; }
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
