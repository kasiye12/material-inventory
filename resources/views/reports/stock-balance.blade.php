@extends('layouts.app')
@section('title', 'Stock Balance')
@section('page-title', 'Current Stock Balance Report')

@section('content')
<div class="card mb-3 no-print">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Report Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.stock-balance') }}" class="row align-items-end">
            <div class="col-md-4 mb-2">
                <label class="form-label small fw-bold">Project/Location</label>
                <select class="form-select select2-search" name="location_id" style="width: 100%;">
                    <option value="">🔍 Search project...</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">Category</label>
                <select class="form-select" name="category_id" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">Search Item</label>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="🔍 Search by name/code...">
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

@php
    // Pagination - show only 100 items per page
    $page = request('page', 1);
    $perPage = 100;
    $totalItems = $items->count();
    $totalPages = ceil($totalItems / $perPage);
    $paginatedItems = $items->forPage($page, $perPage);
@endphp

<div class="alert alert-info no-print">
    <i class="fas fa-info-circle me-2"></i>
    Showing {{ $paginatedItems->count() }} of {{ $totalItems }} items | Page {{ $page }} of {{ $totalPages }}
</div>

<div class="print-area">
    <div class="card">
        <div class="card-body p-4">
            <div style="display: flex; align-items: center; justify-content: center; border-bottom: 3px double #1e293b; padding-bottom: 10px; margin-bottom: 15px; position: relative;">
                <div style="position: absolute; left: 0;">
                    <img src="{{ asset('images/company-logo.png') }}" alt="Logo" style="width: 80px; height: 50px;">
                </div>
                <div style="text-align: center;">
                    <h5 style="font-weight: bold; margin: 0;">ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች</h5>
                    <p style="font-style: italic; margin: 0;">TNT Construction & Trading</p>
                </div>
                <div style="position: absolute; right: 0; font-size: 10px;">
                    <strong>Date:</strong> {{ date('d/m/Y') }}
                </div>
            </div>
            
            <div style="text-align: center; background: #fbbf24; padding: 8px; margin-bottom: 15px;">
                <strong>STOCK BALANCE REPORT</strong>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered" style="font-size: 10px;">
                    <thead>
                        <tr style="background: #4b5563; color: #fff;">
                            <th>#</th><th>Code</th><th>Item Name</th><th>Category</th>
                            <th>Unit</th><th>Current Stock</th><th>Min</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paginatedItems as $k => $item)
                        @php
                            $stock = $item->current_stock;
                            $min = $item->min_stock_level;
                            if ($stock <= 0) { $status = 'Out'; $color = 'danger'; }
                            elseif ($stock <= $min) { $status = 'Low'; $color = 'warning'; }
                            else { $status = 'OK'; $color = 'success'; }
                        @endphp
                        <tr>
                            <td>{{ ($page - 1) * $perPage + $k + 1 }}</td>
                            <td><strong>{{ $item->code }}</strong></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                            <td>{{ $item->unit }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($stock, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($min, 2) }}</td>
                            <td><span class="badge bg-{{ $color }}">{{ $status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="8" style="text-align: center; padding: 20px;">No items found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($totalPages > 1)
            <nav class="no-print">
                <ul class="pagination justify-content-center">
                    @for($i = 1; $i <= min($totalPages, 20); $i++)
                    <li class="page-item {{ $page == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                    </li>
                    @endfor
                    @if($totalPages > 20)
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                    <li class="page-item">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}">{{ $totalPages }}</a>
                    </li>
                    @endif
                </ul>
            </nav>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
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
