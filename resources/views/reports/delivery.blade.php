@extends('layouts.app')
@section('title', 'Delivery Report')
@section('page-title', 'Daily Material Delivery Report')

@section('content')
<!-- Filter Form -->
<div class="card mb-3 no-print">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.delivery') }}" class="row align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold">From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from', date('Y-m-01')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">From (Source)</label>
                <select class="form-select select2-search" name="from_location_id" style="width: 100%;">
                    <option value="">🔍 All Sources</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('from_location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">To (Destination)</label>
                <select class="form-select select2-search" name="location_id" style="width: 100%;">
                    <option value="">🔍 All Destinations</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
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
    $hasFilters = request('date_from') || request('location_id') || request('from_location_id') || request('category_id');
@endphp

@if($hasFilters)
    <!-- Action Buttons -->
    <div class="no-print mb-3 d-flex gap-2 justify-content-end">
        <button class="btn btn-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('reports.delivery.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'from_location_id' => request('from_location_id'), 'location_id' => request('location_id'), 'category_id' => request('category_id'), 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('reports.delivery.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'from_location_id' => request('from_location_id'), 'location_id' => request('location_id'), 'category_id' => request('category_id'), 'format' => 'excel']) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel me-1"></i> Excel
        </a>
    </div>

    <!-- Report Content -->
    <div class="print-area">
        <div class="card">
            <div class="card-body p-4">
                <!-- Header with Logo -->
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
                        <strong>Issue No:</strong> 1<br>
                        <strong>Page:</strong> 1 of 1
                    </div>
                </div>
                
                <!-- Title -->
                <div class="report-title">Daily Material Delivery Report</div>
                
                <!-- Filter Info -->
                <p class="text-center mb-1">
                    <strong>Period:</strong> {{ request('date_from', date('Y-m-01')) }} to {{ request('date_to', date('Y-m-d')) }}
                </p>
                @if(request('from_location_id') || request('location_id'))
                <p class="text-center mb-3">
                    @if(request('from_location_id'))
                    <strong>From:</strong> {{ App\Models\Location::find(request('from_location_id'))->name ?? 'N/A' }}
                    @endif
                    @if(request('from_location_id') && request('location_id'))
                    |
                    @endif
                    @if(request('location_id'))
                    <strong>To:</strong> {{ App\Models\Location::find(request('location_id'))->name ?? 'N/A' }}
                    @endif
                </p>
                @endif
                
                @php
                    $user = auth()->user();
                    $accessibleIds = $user->getAccessibleProjectIds();
                    $query = App\Models\StockTransaction::with(['item.category', 'fromLocation', 'toLocation'])
                        ->whereIn('transaction_type', ['GRV', 'ISTRV'])
                        ->whereBetween('transaction_date', [request('date_from', date('Y-m-01')), request('date_to', date('Y-m-d'))]);
                    
                    if (!$user->isHighLevelRole()) {
                        $query->whereIn('to_location_id', $accessibleIds);
                    }
                    if (request('from_location_id')) {
                        $query->where('from_location_id', request('from_location_id'));
                    }
                    if (request('location_id')) {
                        $query->where('to_location_id', request('location_id'));
                    }
                    if (request('category_id')) {
                        $query->whereHas('item', fn($q) => $q->where('category_id', request('category_id')));
                    }
                    
                    $deliveries = $query->orderBy('transaction_date', 'asc')->get();
                    $groupedDeliveries = $deliveries->groupBy(fn($d) => $d->item->category->name ?? 'Uncategorized');
                @endphp
                
                <div class="table-responsive">
                    <table class="table table-bordered table-sm report-table">
                        <thead>
                            <tr>
                                <th class="text-center">NO</th>
                                <th>Item Description</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">ISTV NO</th>
                                <th class="text-center">ISTRV NO</th>
                                <th class="text-center">Delivery Date</th>
                                <th class="text-center">FROM</th>
                                <th class="text-center">TO</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @forelse($groupedDeliveries as $category => $items)
                            <tr class="category-row">
                                <td colspan="10"><strong>{{ strtoupper($category) }}</strong></td>
                            </tr>
                            @foreach($items as $d)
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td>{{ $d->item->name }}</td>
                                <td class="text-center">{{ $d->item->unit }}</td>
                                <td class="text-center">{{ $d->quantity }}</td>
                                <td class="text-center">{{ $d->transaction_type === 'GRV' ? $d->reference_number : '' }}</td>
                                <td class="text-center">{{ $d->transaction_type === 'ISTRV' ? ($d->reference_number ?? $d->document_number) : '' }}</td>
                                <td class="text-center">{{ $d->transaction_date->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                                <td class="text-center">{{ $d->toLocation->name ?? '-' }}</td>
                                <td>{{ $d->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    No delivery records found for the selected filters
                                </td>
                            </tr>
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
        <strong>Select filters and click "Generate" to view the Daily Material Delivery Report.</strong>
        <br><br>
        <strong>Available filters:</strong>
        <ul class="mb-0">
            <li><strong>From (Source):</strong> Filter by Head Office, Main Store, or specific project</li>
            <li><strong>To (Destination):</strong> Filter by receiving project</li>
            <li><strong>Category:</strong> Filter by material category</li>
        </ul>
    </div>
@endif

<style>
    .report-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px double #1e293b; padding-bottom: 12px; margin-bottom: 15px; }
    .logo-container { width: 90px; text-align: center; }
    .logo-container img { width: 90px; height: 45px; object-fit: contain; }
    .company-info { text-align: center; flex: 1; }
    .doc-info { text-align: right; font-size: 11px; }
    .report-title { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; background: #fbbf24; padding: 8px; margin: 15px 0; }
    .report-table th { background: #4b5563; color: #fff; border: 1px solid #000; }
    .report-table td { border: 1px solid #000; }
    .category-row { background: #e5e7eb; }
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
        placeholder: '🔍 Search location...',
        allowClear: true,
        width: '100%',
    });
});
</script>
@endpush
@endsection
