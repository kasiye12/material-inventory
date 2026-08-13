@extends('layouts.app')
@section('title', 'Weekly Transfer Report')
@section('page-title', 'Weekly Material Transfer Report')

@section('content')
<!-- Filter Form - Hidden on Print -->
<div class="card mb-3 no-print">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.weekly-transfer') }}" class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from', \Carbon\Carbon::now('Africa/Addis_Ababa')->startOfWeek()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to', \Carbon\Carbon::now('Africa/Addis_Ababa')->endOfWeek()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Project/Location</label>
                <select class="form-select" name="location_id">
                    <option value="">All Locations</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
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
    $hasFilters = request('date_from') || request('location_id') || request()->has('generate');
@endphp

@if($hasFilters)
    <!-- Action Buttons - Hidden on Print -->
    <div class="no-print mb-3 d-flex gap-2 justify-content-end">
        <button class="btn btn-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('reports.weekly-transfer.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'location_id' => request('location_id'), 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('reports.weekly-transfer.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'location_id' => request('location_id'), 'format' => 'excel']) }}" class="btn btn-success btn-sm">
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
                        <strong>Document No:</strong> OF/TNT/SUP/034<br>
                        <strong>Issue No:</strong> 1<br>
                        <strong>Page:</strong> 1 of 1
                    </div>
                </div>
                
                <!-- Title -->
                <div class="report-title">
                    Weekly Report {{ date('d/m/Y', strtotime(request('date_from', date('Y-m-d')))) }} - {{ date('d/m/Y', strtotime(request('date_to', date('Y-m-d')))) }}
                </div>
                
                <p class="text-center mb-3" style="text-decoration: underline; font-weight: bold;">
                    Material Transfer From Project To Project
                </p>
                
                @php
                    $user = auth()->user();
                    $accessibleIds = $user->getAccessibleProjectIds();
                    $query = App\Models\StockTransaction::with(['item', 'fromLocation', 'toLocation'])
                        ->where('transaction_type', 'TRANSFER_OUT')
                        ->whereBetween('transaction_date', [request('date_from', date('Y-m-d', strtotime('monday this week'))), request('date_to', date('Y-m-d', strtotime('sunday this week')))]);
                    
                    if (!$user->isHighLevelRole()) {
                        $query->where(function($q) use ($accessibleIds) {
                            $q->whereIn('from_location_id', $accessibleIds)
                              ->orWhereIn('to_location_id', $accessibleIds);
                        });
                    }
                    if (request('location_id')) {
                        $query->where(function($q) {
                            $q->where('from_location_id', request('location_id'))
                              ->orWhere('to_location_id', request('location_id'));
                        });
                    }
                    
                    $transfers = $query->orderBy('transaction_date', 'asc')->get();
                @endphp
                
                <div class="table-responsive">
                    <table class="table table-bordered table-sm report-table" style="font-size: 9px;">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <th width="20%">Item Description</th>
                                <th class="text-center" width="5%">Unit</th>
                                <th class="text-center" width="8%">Requested Qty</th>
                                <th class="text-center" width="8%">SR.No</th>
                                <th class="text-center" width="8%">Date</th>
                                <th width="10%">From Project</th>
                                <th class="text-center" width="8%">Out/SIV NO</th>
                                <th width="10%">To Project</th>
                                <th class="text-center" width="8%">In NO</th>
                                <th class="text-center" width="8%">Received QTY</th>
                                <th class="text-center" width="8%">Delivered Date</th>
                                <th class="text-center" width="8%">Remaining QTY</th>
                                <th width="8%">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @forelse($transfers as $t)
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td>{{ $t->item->name ?? '' }}</td>
                                <td class="text-center">{{ $t->item->unit ?? '' }}</td>
                                <td class="text-center">{{ $t->quantity }}</td>
                                <td class="text-center">{{ $t->reference_number ?? '' }}</td>
                                <td class="text-center">{{ $t->transaction_date->format('d/m/Y') }}</td>
                                <td>{{ $t->fromLocation->name ?? '' }}</td>
                                <td class="text-center">{{ $t->reference_number ?? '' }}</td>
                                <td>{{ $t->toLocation->name ?? '' }}</td>
                                <td class="text-center">{{ $t->document_number ?? '' }}</td>
                                <td class="text-center">{{ $t->quantity }}</td>
                                <td class="text-center">{{ $t->transaction_date->format('d/m/Y') }}</td>
                                <td class="text-center"></td>
                                <td>{{ $t->remarks ?? '' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    No transfer records found for the selected period
                                </td>
                            </tr>
                            @endforelse
                            @if($transfers->count() > 0)
                            <tr class="total-row">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-center"><strong>{{ $transfers->sum('quantity') }}</strong></td>
                                <td colspan="10"></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info no-print">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Select filters and click "Generate" to preview the Weekly Transfer Report.</strong>
    </div>
@endif

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
    .report-title { text-align: center; font-size: 13px; font-weight: bold; text-transform: uppercase; background: #fbbf24; padding: 8px; margin: 10px 0; }
    .report-table th { background: #4b5563; color: #fff; border: 1px solid #000; }
    .report-table td { border: 1px solid #000; }
    .total-row { background: #e5e7eb; }
    
    @media print {
        body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
        .sidebar, .no-print, .breadcrumb, #sidebar { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        .card { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
        .card-body { padding: 20px !important; }
        .report-table th { background: #4b5563 !important; color: #fff !important; }
    }
</style>
@endsection
