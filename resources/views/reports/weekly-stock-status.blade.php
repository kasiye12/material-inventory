@extends('layouts.app')
@section('title', 'Weekly Stock Status')
@section('page-title', 'Weekly Stock Status Report')

@section('content')
<!-- Filter Form -->
<div class="card mb-3 no-print">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.weekly-stock-status') }}" class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from', \Carbon\Carbon::now('Africa/Addis_Ababa')->startOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to', \Carbon\Carbon::now('Africa/Addis_Ababa')->endOfMonth()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">To Project</label>
                <select class="form-select" name="location_id">
                    <option value="">All Projects</option>
                    @foreach($locations as $loc)
                    @if($loc->type == 'project' || $loc->type == 'site')
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endif
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
    <!-- Action Buttons -->
    <div class="no-print mb-3 d-flex gap-2 justify-content-end">
        <button class="btn btn-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('reports.weekly-stock-status.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'location_id' => request('location_id'), 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('reports.weekly-stock-status.export', ['date_from' => request('date_from'), 'date_to' => request('date_to'), 'location_id' => request('location_id'), 'format' => 'excel']) }}" class="btn btn-success btn-sm">
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
                    Weekly Stock Status Report
                </div>
                
                <p class="text-center mb-3" style="text-decoration: underline;">
                    <strong>From {{ date('d/m/Y', strtotime(request('date_from', date('Y-m-01')))) }} - {{ date('d/m/Y', strtotime(request('date_to', date('Y-m-d')))) }}</strong>
                </p>
                
                @php
                    $user = auth()->user();
                    $accessibleIds = $user->getAccessibleProjectIds();
                    $dateFrom = request('date_from', date('Y-m-01'));
                    $dateTo = request('date_to', date('Y-m-d'));
                    $locationId = request('location_id');
                    
                    // Get all transfer transactions
                    $query = App\Models\StockTransaction::with(['item', 'fromLocation', 'toLocation'])
                        ->where('transaction_type', 'TRANSFER_OUT')
                        ->whereBetween('transaction_date', [$dateFrom, $dateTo]);
                    
                    if (!$user->isHighLevelRole()) {
                        $query->whereIn('to_location_id', $accessibleIds);
                    }
                    if ($locationId) {
                        $query->where('to_location_id', $locationId);
                    }
                    
                    $allTransfers = $query->orderBy('transaction_date', 'asc')->get();
                    
                    // Categorize transfers by source
                    $hoTransfers = $allTransfers->filter(function($t) {
                        return $t->fromLocation && in_array($t->fromLocation->type, ['head_office']);
                    });
                    
                    $mainStoreTransfers = $allTransfers->filter(function($t) {
                        return $t->fromLocation && $t->fromLocation->code === 'MAIN';
                    });
                    
                    $projectToProjectTransfers = $allTransfers->filter(function($t) {
                        return $t->fromLocation && in_array($t->fromLocation->type, ['project', 'site']) 
                            && $t->toLocation && in_array($t->toLocation->type, ['project', 'site']);
                    });
                    
                    $grandTotal = 0;
                    foreach ($allTransfers as $t) {
                        $grandTotal += $t->quantity * ($t->item->unit_price ?? 0);
                    }
                @endphp
                
                <!-- Section 1: Head Office to Projects -->
                @if($hoTransfers->count() > 0)
                <h6 class="section-heading">
                    <i class="fas fa-building me-2"></i>
                    SECTION A: Head Office to Projects
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm report-table">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Item Description</th>
                                <th class="text-center">UOM</th>
                                <th class="text-center">TR-Out Qty</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">TR-Out No.</th>
                                <th class="text-center">TR-Out Date</th>
                                <th class="text-center">TR-IN No.</th>
                                <th class="text-center">TR-IN Qty</th>
                                <th>Project</th>
                                <th class="text-center">TR-IN Date</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($hoTransfers as $t)
                            @php $totalPrice = $t->quantity * ($t->item->unit_price ?? 0); @endphp
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td>{{ $t->item->name ?? '' }}</td>
                                <td class="text-center">{{ $t->item->unit ?? '' }}</td>
                                <td class="text-end">{{ $t->quantity }}</td>
                                <td class="text-end">{{ $t->item->unit_price ? number_format($t->item->unit_price, 2) : '' }}</td>
                                <td class="text-end">{{ $totalPrice ? number_format($totalPrice, 2) : '' }}</td>
                                <td class="text-center">{{ $t->reference_number ?? '' }}</td>
                                <td class="text-center">{{ $t->transaction_date->format('d-m-Y') }}</td>
                                <td class="text-center">{{ $t->document_number ?? '' }}</td>
                                <td class="text-end">{{ $t->quantity }}</td>
                                <td>{{ $t->toLocation->name ?? '' }}</td>
                                <td class="text-center">{{ $t->transaction_date->format('d-m-Y') }}</td>
                                <td>{{ $t->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                
                <!-- Section 2: Main Store to Projects -->
                @if($mainStoreTransfers->count() > 0)
                <h6 class="section-heading mt-4">
                    <i class="fas fa-warehouse me-2"></i>
                    SECTION B: Main Store to Projects
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm report-table">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Item Description</th>
                                <th class="text-center">UOM</th>
                                <th class="text-center">TR-Out Qty</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">TR-Out No.</th>
                                <th class="text-center">TR-Out Date</th>
                                <th class="text-center">TR-IN No.</th>
                                <th class="text-center">TR-IN Qty</th>
                                <th>Project</th>
                                <th class="text-center">TR-IN Date</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($mainStoreTransfers as $t)
                            @php $totalPrice = $t->quantity * ($t->item->unit_price ?? 0); @endphp
                            <tr>
                                <td class="text-center">{{ $counter++ }}</td>
                                <td>{{ $t->item->name ?? '' }}</td>
                                <td class="text-center">{{ $t->item->unit ?? '' }}</td>
                                <td class="text-end">{{ $t->quantity }}</td>
                                <td class="text-end">{{ $t->item->unit_price ? number_format($t->item->unit_price, 2) : '' }}</td>
                                <td class="text-end">{{ $totalPrice ? number_format($totalPrice, 2) : '' }}</td>
                                <td class="text-center">{{ $t->reference_number ?? '' }}</td>
                                <td class="text-center">{{ $t->transaction_date->format('d-m-Y') }}</td>
                                <td class="text-center">{{ $t->document_number ?? '' }}</td>
                                <td class="text-end">{{ $t->quantity }}</td>
                                <td>{{ $t->toLocation->name ?? '' }}</td>
                                <td class="text-center">{{ $t->transaction_date->format('d-m-Y') }}</td>
                                <td>{{ $t->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                
                <!-- Section 3: Project to Project -->
                @if($projectToProjectTransfers->count() > 0)
                <h6 class="section-heading mt-4">
                    <i class="fas fa-exchange-alt me-2"></i>
                    SECTION C: Project to Project Transfers
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm report-table">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Item Description</th>
                                <th class="text-center">UOM</th>
                                <th class="text-center">Requested Qty</th>
                                <th class="text-center">SR.No</th>
                                <th class="text-center">Date</th>
                                <th>From Project</th>
                                <th class="text-center">Out/SIV NO</th>
                                <th>To Project</th>
                                <th class="text-center">In NO</th>
                                <th class="text-center">Received QTY</th>
                                <th class="text-center">Delivered Date</th>
                                <th class="text-center">Remaining QTY</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($projectToProjectTransfers as $t)
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                
                <!-- Grand Total -->
                @if($allTransfers->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th>Grand Total Amount:</th>
                                <td class="text-end fw-bold">{{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
                
                @if($allTransfers->count() == 0)
                <div class="alert alert-warning text-center">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    <strong>No transfer records found for the selected period</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info no-print">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Select filters and click "Generate" to preview the Weekly Stock Status Report.</strong>
        <br><br>
        <strong>Report Sections:</strong>
        <ul class="mb-0">
            <li><strong>Section A:</strong> Head Office to Projects</li>
            <li><strong>Section B:</strong> Main Store to Projects</li>
            <li><strong>Section C:</strong> Project to Project Transfers</li>
        </ul>
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
    .report-title { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; background: #fbbf24; padding: 8px; margin: 15px 0; }
    .section-heading { background: #1e3a8a; color: #fff; padding: 8px 12px; border-radius: 4px; margin: 15px 0 10px; }
    .report-table th { background: #4b5563; color: #fff; border: 1px solid #000; text-align: center; }
    .report-table td { border: 1px solid #000; }
    
    @media print {
        body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
        .sidebar, .no-print, .breadcrumb, #sidebar { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        .card { box-shadow: none !important; border: none !important; border-radius: 0 !important; }
        .card-body { padding: 20px !important; }
        .section-heading { background: #1e3a8a !important; -webkit-print-color-adjust: exact; }
        .report-table th { background: #4b5563 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
