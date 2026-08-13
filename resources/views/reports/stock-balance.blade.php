@extends('layouts.app')
@section('title', 'Stock Balance')
@section('page-title', 'Current Stock Balance Report')

@section('content')
<!-- Filter Form -->
<div class="card mb-3 no-print">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.stock-balance') }}" class="row align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Project/Location</label>
                <select class="form-select" name="location_id" onchange="this.form.submit()">
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Category</label>
                <select class="form-select" name="category_id" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-success w-100" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </form>
    </div>
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
                    <h6 class="mt-1 mb-0 text-decoration-underline">{{ $selectedLocation->name ?? '' }}</h6>
                </div>
                <div class="doc-info">
                    <strong>Document No:</strong> OF/TNT/SUP/033<br>
                    <strong>Date:</strong> {{ date('d/m/Y') }}
                </div>
            </div>
            
            <!-- Title -->
            <div class="report-title">
                Stock Balance Report
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-sm report-table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th class="text-center">Unit</th>
                            <th class="text-end">Current Stock</th>
                            <th class="text-end">Min Level</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $k => $item)
                        @php
                            $stock = $item->current_stock;
                            $min = $item->min_stock_level;
                            if ($stock <= 0) { $status = 'Out of Stock'; $color = 'danger'; }
                            elseif ($stock <= $min) { $status = 'Low Stock'; $color = 'warning'; }
                            else { $status = 'In Stock'; $color = 'success'; }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $k + 1 }}</td>
                            <td class="text-center"><strong>{{ $item->code }}</strong></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->unit }}</td>
                            <td class="text-end fw-bold">{{ number_format($stock, 2) }}</td>
                            <td class="text-end">{{ number_format($min, 2) }}</td>
                            <td class="text-center"><span class="badge bg-{{ $color }}">{{ $status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-4">No items found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .report-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px double #1e293b;
        padding-bottom: 12px;
        margin-bottom: 15px;
    }
    .logo-container { width: 90px; text-align: center; }
    .logo-container img { width: 90px; height: 45px; object-fit: contain; }
    .company-info { text-align: center; flex: 1; }
    .doc-info { text-align: right; font-size: 11px; }
    .report-title { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; background: #fbbf24; padding: 8px; margin: 15px 0; }
    .report-table th { background: #4b5563; color: #fff; border: 1px solid #000; }
    .report-table td { border: 1px solid #000; }
    
    @media print {
        body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
        .sidebar, .no-print, .breadcrumb, #sidebar { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        .card { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
        .card-body { padding: 20px !important; }
    }
</style>
@endsection
