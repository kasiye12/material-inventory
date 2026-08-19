@extends('layouts.app')
@section('title', 'Stock Balance')
@section('page-title', 'Current Stock Balance Report')

@section('content')
<!-- Filter Form - Always Visible -->
<div class="card mb-3 no-print">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Report Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.stock-balance') }}" class="row align-items-end">
            <div class="col-md-5 mb-2">
                <label class="form-label small fw-bold">Project/Location</label>
                <select class="form-select select2-search" name="location_id" onchange="this.form.submit()" style="width: 100%;">
                    <option value="">🔍 Search project...</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label small fw-bold">Category</label>
                <select class="form-select" name="category_id" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Location Info -->
@if(isset($selectedLocation))
<div class="alert alert-info no-print">
    <i class="fas fa-map-marker-alt me-2"></i>
    Showing stock for: <strong>{{ $selectedLocation->code }} - {{ $selectedLocation->name }}</strong>
</div>
@endif

<!-- Summary Cards -->
<div class="row mb-3 no-print">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-2">
                <i class="fas fa-check-circle fa-2x mb-1"></i>
                <h5 class="mb-0">{{ $items->where('current_stock', '>', 0)->where('current_stock', '>', 'min_stock_level')->count() }}</h5>
                <small>In Stock</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center py-2">
                <i class="fas fa-exclamation-triangle fa-2x mb-1"></i>
                <h5 class="mb-0">{{ $items->where('current_stock', '>', 0)->where('current_stock', '<=', 'min_stock_level')->count() }}</h5>
                <small>Low Stock</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center py-2">
                <i class="fas fa-times-circle fa-2x mb-1"></i>
                <h5 class="mb-0">{{ $items->where('current_stock', '<=', 0)->count() }}</h5>
                <small>Out of Stock</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-2">
                <i class="fas fa-boxes fa-2x mb-1"></i>
                <h5 class="mb-0">{{ $items->count() }}</h5>
                <small>Total Items</small>
            </div>
        </div>
    </div>
</div>

<!-- Report Content -->
<div class="print-area">
    <div class="card">
        <div class="card-body p-4">
            <!-- Header -->
            <div style="display: flex; align-items: center; justify-content: center; border-bottom: 3px double #1e293b; padding-bottom: 10px; margin-bottom: 15px; position: relative;">
                <div style="position: absolute; left: 0;">
                    <img src="{{ asset('images/company-logo.png') }}" alt="TNT Logo" style="width: 80px; height: 50px;">
                </div>
                <div style="text-align: center;">
                    <h5 style="font-weight: bold; margin: 0;">ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች</h5>
                    <p style="font-style: italic; margin: 0;">TNT Construction & Trading</p>
                    @if(isset($selectedLocation))
                    <h6 style="text-decoration: underline; margin: 5px 0 0;">{{ $selectedLocation->name }}</h6>
                    @endif
                </div>
                <div style="position: absolute; right: 0; font-size: 10px; text-align: right;">
                    <strong>Date:</strong> {{ date('d/m/Y') }}
                </div>
            </div>
            
            <!-- Title -->
            <div style="text-align: center; background: #fbbf24; padding: 8px; margin-bottom: 15px;">
                <strong style="font-size: 14px;">STOCK BALANCE REPORT</strong>
            </div>
            
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered" style="font-size: 11px;">
                    <thead>
                        <tr style="background: #4b5563; color: #fff;">
                            <th style="text-align: center; width: 40px;">#</th>
                            <th style="text-align: center; width: 80px;">Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th style="text-align: center; width: 50px;">Unit</th>
                            <th style="text-align: right; width: 90px;">Current Stock</th>
                            <th style="text-align: right; width: 70px;">Min Level</th>
                            <th style="text-align: right; width: 70px;">Max Level</th>
                            <th style="text-align: center; width: 90px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $k => $item)
                        @php
                            $stock = $item->current_stock;
                            $min = $item->min_stock_level;
                            $max = $item->max_stock_level;
                            
                            if ($stock <= 0) {
                                $status = 'Out of Stock';
                                $badgeColor = 'danger';
                                $stockColor = 'text-danger';
                            } elseif ($stock <= $min) {
                                $status = 'Low Stock';
                                $badgeColor = 'warning';
                                $stockColor = 'text-warning';
                            } else {
                                $status = 'In Stock';
                                $badgeColor = 'success';
                                $stockColor = 'text-success';
                            }
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $k + 1 }}</td>
                            <td style="text-align: center;"><strong>{{ $item->code }}</strong></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                            <td style="text-align: center;">{{ $item->unit }}</td>
                            <td style="text-align: right; font-weight: bold; {{ $stockColor }};">
                                {{ number_format($stock, 2) }}
                            </td>
                            <td style="text-align: right;">{{ number_format($min, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($max, 2) }}</td>
                            <td style="text-align: center;">
                                <span class="badge bg-{{ $badgeColor }}">{{ $status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 30px; color: #999;">
                                <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                No items found for this location
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .no-print, .breadcrumb, #sidebar, .dropdown, .d-flex.justify-content-between.align-items-center.mb-4 {
        display: none !important;
    }
    .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
    .card { box-shadow: none !important; border: none !important; }
    .card-body { padding: 15px !important; }
    .print-area { display: block !important; }
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
