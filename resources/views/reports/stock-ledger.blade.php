@extends('layouts.app')
@section('title', 'Stock Ledger')
@section('page-title', 'Project Material Ledger')

@section('content')
<div class="card mb-3 no-print">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.stock-ledger') }}" class="row align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold">From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from', date('Y-m-01')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <label class="form-label small fw-bold">Category</label>
                <select class="form-select" name="category_id">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
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
    <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="fas fa-print me-1"></i> Print</button>
    <a href="{{ route('reports.ledger.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'location_id' => request('location_id'), 'category_id' => request('category_id'), 'format' => 'pdf']) }}" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> PDF</a>
    <a href="{{ route('reports.ledger.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'location_id' => request('location_id'), 'category_id' => request('category_id'), 'format' => 'excel']) }}" class="btn btn-success btn-sm"><i class="fas fa-file-excel me-1"></i> Excel</a>
</div>

<div class="print-area">
    <div class="card">
        <div class="card-body p-4">
            <div class="report-header">
                <div class="logo-container">
                    <img src="{{ asset('images/company-logo.png') }}" alt="TNT Logo">
                </div>
                <div class="company-info">
                    <h5 class="fw-bold mb-0">ቲ. ኤን. ቲ. ኮንስትራክሽንና ንግድ ሥራዎች</h5>
                    <p class="mb-0" style="font-style: italic;">TNT Construction & Trading</p>
                </div>
                <div class="doc-info">
                    <strong>Document No:</strong> OF/TNT/SUP/033<br>
                    <strong>Period:</strong> {{ request('date_from') }} to {{ request('date_to') }}
                </div>
            </div>
            
            @php
                $user = auth()->user();
                $accessibleIds = $user->getAccessibleProjectIds();
                $locationId = request('location_id');
                
                if (!$user->isHighLevelRole() && (!$locationId || !in_array($locationId, $accessibleIds))) {
                    $locationId = $accessibleIds[0] ?? 1;
                }
                
                $items = App\Models\Item::with('category')
                    ->where('is_active', true)
                    ->when(request('category_id'), fn($q) => $q->where('category_id', request('category_id')))
                    ->orderBy('category_id')->orderBy('name')->get();
                    
                $groupedItems = $items->groupBy(fn($item) => $item->category->name ?? 'Uncategorized');
            @endphp
            
            <div class="table-responsive">
                <table class="table table-bordered table-sm report-table" style="font-size: 10px;">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">Item No.</th>
                            <th rowspan="2">Description</th>
                            <th rowspan="2" class="text-center">Unit</th>
                            <th rowspan="2" class="text-center">Beg. Balance</th>
                            <th colspan="2" class="text-center">GRV</th>
                            <th colspan="2" class="text-center">ISTRV</th>
                            <th colspan="2" class="text-center">SIV</th>
                            <th colspan="2" class="text-center">Transferred Out</th>
                            <th colspan="2" class="text-center">Store Return</th>
                            <th rowspan="2" class="text-center">Ending Balance</th>
                            <th rowspan="2" class="text-center">Remark</th>
                        </tr>
                        <tr>
                            <th class="text-center">Ref.No.</th><th class="text-center">Qty</th>
                            <th class="text-center">Ref.No.</th><th class="text-center">Qty</th>
                            <th class="text-center">Ref.No.</th><th class="text-center">Qty</th>
                            <th class="text-center">Ref.No.</th><th class="text-center">Qty</th>
                            <th class="text-center">Ref.No.</th><th class="text-center">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groupedItems as $category => $categoryItems)
                        <tr class="category-row"><td colspan="16"><strong>{{ strtoupper($category) }}</strong></td></tr>
                        @php $counter = 1; @endphp
                        @foreach($categoryItems as $item)
                        @php
                            $openingBalance = App\Models\StockTransaction::where('item_id', $item->id)
                                ->where('transaction_date', '<', request('date_from', date('Y-m-01')))
                                ->when($locationId, fn($q) => $q->where('to_location_id', $locationId))
                                ->whereIn('transaction_type', ['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE'])
                                ->sum('quantity')
                                - App\Models\StockTransaction::where('item_id', $item->id)
                                ->where('transaction_date', '<', request('date_from', date('Y-m-01')))
                                ->when($locationId, fn($q) => $q->where('from_location_id', $locationId))
                                ->whereIn('transaction_type', ['SIV', 'TRANSFER_OUT'])
                                ->sum('quantity');
                            
                            $transactions = App\Models\StockTransaction::where('item_id', $item->id)
                                ->whereBetween('transaction_date', [request('date_from', date('Y-m-01')), request('date_to', date('Y-m-d'))])
                                ->when($locationId, fn($q) => $q->where(fn($sub) => $sub->where('from_location_id', $locationId)->orWhere('to_location_id', $locationId)))
                                ->get();
                            
                            $grvQty = $transactions->where('transaction_type','GRV')->sum('quantity');
                            $istrvQty = $transactions->where('transaction_type','ISTRV')->sum('quantity');
                            $sivQty = $transactions->where('transaction_type','SIV')->sum('quantity');
                            $transferQty = $transactions->where('transaction_type','TRANSFER_OUT')->sum('quantity');
                            $returnQty = $transactions->where('transaction_type','STORE_RETURN')->sum('quantity');
                            $endingBalance = max(0, $openingBalance + $grvQty + $istrvQty - $sivQty - $transferQty + $returnQty);
                        @endphp
                        <tr>
                            <td class="text-center">{{ $counter++ }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-center">{{ $item->unit }}</td>
                            <td class="text-end">{{ max(0, $openingBalance) ?: '' }}</td>
                            <td class="text-center ref-text">{{ $transactions->where('transaction_type','GRV')->pluck('reference_number')->filter()->implode(', ') }}</td>
                            <td class="text-end">{{ $grvQty ?: '' }}</td>
                            <td class="text-center ref-text">{{ $transactions->where('transaction_type','ISTRV')->pluck('reference_number')->filter()->implode(', ') }}</td>
                            <td class="text-end">{{ $istrvQty ?: '' }}</td>
                            <td class="text-center ref-text">{{ $transactions->where('transaction_type','SIV')->pluck('reference_number')->filter()->implode(', ') }}</td>
                            <td class="text-end">{{ $sivQty ?: '' }}</td>
                            <td class="text-center ref-text">{{ $transactions->where('transaction_type','TRANSFER_OUT')->pluck('reference_number')->filter()->implode(', ') }}</td>
                            <td class="text-end">{{ $transferQty ?: '' }}</td>
                            <td class="text-center ref-text">{{ $transactions->where('transaction_type','STORE_RETURN')->pluck('reference_number')->filter()->implode(', ') }}</td>
                            <td class="text-end">{{ $returnQty ?: '' }}</td>
                            <td class="text-end"><strong>{{ $endingBalance ?: '' }}</strong></td>
                            <td></td>
                        </tr>
                        @endforeach
                        @empty
                        <tr><td colspan="16" class="text-center py-4">No items found</td></tr>
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
    Select filters and click <strong>Generate</strong> to view the Project Material Ledger.
</div>
@endif

<style>
    .report-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px double #1e293b; padding-bottom: 12px; margin-bottom: 15px; }
    .logo-container { width: 90px; text-align: center; }
    .logo-container img { width: 90px; height: 45px; object-fit: contain; }
    .company-info { text-align: center; flex: 1; }
    .doc-info { text-align: right; font-size: 11px; }
    .report-table th { background: #4b5563; color: #fff; border: 1px solid #000; }
    .report-table td { border: 1px solid #000; }
    .category-row { background: #e5e7eb; }
    .ref-text { font-size: 8px; color: #475569; }
    @media print {
        body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
        .sidebar, .no-print, .breadcrumb, #sidebar { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        .card { box-shadow: none !important; border: none !important; }
    }
</style>

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2-search').select2({
        placeholder: '🔍 Search project by code or name...',
        allowClear: true,
        width: '100%',
    });
});
</script>
@endpush
@endsection
