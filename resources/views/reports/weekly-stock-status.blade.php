@extends('layouts.app')
@section('title', 'Weekly Stock Status')
@section('page-title', 'Weekly Stock Status Report')

@section('content')
<!-- Filter Form -->
<div class="card mb-3 no-print">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Report Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.weekly-stock-status') }}" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from', date('Y-m-01')) }}">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">Project/Location</label>
                <select class="form-select select2-search" name="location_id" style="width: 100%;">
                    <option value="">🔍 All Projects</option>
                    @foreach($locations as $loc)
                    @if($loc->type == 'project' || $loc->type == 'site')
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->code }} - {{ $loc->name }}
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Generate
                </button>
            </div>
        </form>
    </div>
</div>

@php
    $hasFilters = request('date_from') || request('location_id');
@endphp

@if($hasFilters || request('date_from'))
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
                <!-- Header -->
                <div style="display: flex; align-items: center; justify-content: center; border-bottom: 3px double #1e293b; padding-bottom: 10px; margin-bottom: 15px; position: relative;">
                    <div style="position: absolute; left: 0;">
                        <img src="{{ asset('images/company-logo.png') }}" alt="Logo" style="width: 80px; height: 50px;">
                    </div>
                    <div style="text-align: center;">
                        <h5 style="font-weight: bold; margin: 0;">ቲ. ኤን. ቲ. ኮንስትራክሽንና ንግድ ሥራዎች</h5>
                        <p style="font-style: italic; margin: 0;">TNT Construction & Trading</p>
                    </div>
                    <div style="position: absolute; right: 0; font-size: 10px; text-align: right;">
                        <strong>Document No:</strong> OF/TNT/SUP/034<br>
                        <strong>Issue No:</strong> 1<br>
                        <strong>Page No:</strong> Page 1 of 1
                    </div>
                </div>
                
                <!-- Title -->
                <div style="text-align: center; background: #fbbf24; padding: 8px; margin-bottom: 10px;">
                    <strong>Weekly Stock Status Report</strong>
                </div>
                
                <p style="text-align: center; text-decoration: underline; margin-bottom: 10px;">
                    From {{ date('d/m/Y', strtotime(request('date_from', date('Y-m-01')))) }} - {{ date('d/m/Y', strtotime(request('date_to', date('Y-m-d')))) }}
                </p>
                
                @php
                    $user = auth()->user();
                    $accessibleIds = $user->getAccessibleProjectIds();
                    $dateFrom = request('date_from', date('Y-m-01'));
                    $dateTo = request('date_to', date('Y-m-d'));
                    
                    $query = App\Models\StockTransaction::with(['item', 'fromLocation', 'toLocation'])
                        ->where('transaction_type', 'TRANSFER_OUT')
                        ->whereBetween('transaction_date', [$dateFrom, $dateTo]);
                    
                    if (!$user->isHighLevelRole()) {
                        $query->whereIn('to_location_id', $accessibleIds);
                    }
                    if (request('location_id')) {
                        $query->where('to_location_id', request('location_id'));
                    }
                    
                    $transfers = $query->orderBy('transaction_date', 'asc')->get();
                    
                    $hoTransfers = $transfers->filter(fn($t) => $t->fromLocation && $t->fromLocation->type === 'head_office');
                    $mainStoreTransfers = $transfers->filter(fn($t) => $t->fromLocation && $t->fromLocation->code === 'MAIN');
                    $projectToProject = $transfers->filter(fn($t) => $t->fromLocation && in_array($t->fromLocation->type, ['project', 'site']) && $t->toLocation && in_array($t->toLocation->type, ['project', 'site']));
                    
                    $grandTotal = 0;
                    foreach ($transfers as $t) {
                        $grandTotal += $t->quantity * ($t->item->unit_price ?? 0);
                    }
                @endphp
                
                <!-- SECTION A: HO to Projects -->
                @if($hoTransfers->count() > 0)
                <div class="section-heading">SECTION A: HEAD OFFICE TO PROJECTS</div>
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 9px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th>No.</th>
                                <th>Item Description</th>
                                <th>UOM</th>
                                <th>TR-Out Qty</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>TR-Out No.</th>
                                <th>TR-Out Date</th>
                                <th>TR-IN No.</th>
                                <th>TR-IN Qty</th>
                                <th>Project</th>
                                <th>TR-IN Date</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($hoTransfers as $t)
                            @php $totalPrice = $t->quantity * ($t->item->unit_price ?? 0); @endphp
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $t->item->name ?? '' }}</td>
                                <td>{{ $t->item->unit ?? '' }}</td>
                                <td style="text-align: right;">{{ $t->quantity }}</td>
                                <td style="text-align: right;">{{ $t->item->unit_price ? number_format($t->item->unit_price, 2) : '' }}</td>
                                <td style="text-align: right;">{{ $totalPrice ? number_format($totalPrice, 2) : '' }}</td>
                                <td>{{ $t->reference_number ?? '' }}</td>
                                <td>{{ $t->transaction_date->format('d-m-Y') }}</td>
                                <td>{{ $t->document_number ?? '' }}</td>
                                <td style="text-align: right;">{{ $t->quantity }}</td>
                                <td>{{ $t->toLocation->name ?? '' }}</td>
                                <td>{{ $t->transaction_date->format('d-m-Y') }}</td>
                                <td>{{ $t->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                
                <!-- SECTION B: Main Store to Projects -->
                @if($mainStoreTransfers->count() > 0)
                <div class="section-heading">SECTION B: MAIN STORE TO PROJECTS</div>
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 9px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th>No.</th>
                                <th>Item Description</th>
                                <th>UOM</th>
                                <th>TR-Out Qty</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>TR-Out No.</th>
                                <th>TR-Out Date</th>
                                <th>TR-IN No.</th>
                                <th>TR-IN Qty</th>
                                <th>Project</th>
                                <th>TR-IN Date</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($mainStoreTransfers as $t)
                            @php $totalPrice = $t->quantity * ($t->item->unit_price ?? 0); @endphp
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $t->item->name ?? '' }}</td>
                                <td>{{ $t->item->unit ?? '' }}</td>
                                <td style="text-align: right;">{{ $t->quantity }}</td>
                                <td style="text-align: right;">{{ $t->item->unit_price ? number_format($t->item->unit_price, 2) : '' }}</td>
                                <td style="text-align: right;">{{ $totalPrice ? number_format($totalPrice, 2) : '' }}</td>
                                <td>{{ $t->reference_number ?? '' }}</td>
                                <td>{{ $t->transaction_date->format('d-m-Y') }}</td>
                                <td>{{ $t->document_number ?? '' }}</td>
                                <td style="text-align: right;">{{ $t->quantity }}</td>
                                <td>{{ $t->toLocation->name ?? '' }}</td>
                                <td>{{ $t->transaction_date->format('d-m-Y') }}</td>
                                <td>{{ $t->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                
                <!-- SECTION C: Project to Project -->
                @if($projectToProject->count() > 0)
                <div class="section-heading">SECTION C: PROJECT TO PROJECT TRANSFERS</div>
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 9px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th>No</th>
                                <th>Item Description</th>
                                <th>Unit</th>
                                <th>Requested Qty</th>
                                <th>SR.No</th>
                                <th>Date</th>
                                <th>From Project</th>
                                <th>Out/SIV NO</th>
                                <th>To Project</th>
                                <th>In NO</th>
                                <th>Received QTY</th>
                                <th>Delivered Date</th>
                                <th>Remaining QTY</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($projectToProject as $t)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $t->item->name ?? '' }}</td>
                                <td>{{ $t->item->unit ?? '' }}</td>
                                <td style="text-align: center;">{{ $t->quantity }}</td>
                                <td>{{ $t->reference_number ?? '' }}</td>
                                <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                                <td>{{ $t->fromLocation->name ?? '' }}</td>
                                <td>{{ $t->reference_number ?? '' }}</td>
                                <td>{{ $t->toLocation->name ?? '' }}</td>
                                <td>{{ $t->document_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $t->quantity }}</td>
                                <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                                <td style="text-align: center;"></td>
                                <td>{{ $t->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                
                <!-- Grand Total -->
                @if($transfers->count() > 0)
                <div class="row mt-3">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-bordered" style="font-size: 11px;">
                            <tr>
                                <th style="text-align: right;">GRAND TOTAL:</th>
                                <td style="text-align: right; font-weight: bold;">ETB {{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
                
                @if($transfers->count() == 0)
                <div style="text-align: center; padding: 30px; color: #999;">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    No transfer records found
                </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info no-print">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Select filters and click "Generate" to view the Weekly Stock Status Report.</strong>
        <br><br>
        <strong>Report Sections:</strong>
        <ul class="mb-0">
            <li>Section A: Head Office to Projects</li>
            <li>Section B: Main Store to Projects</li>
            <li>Section C: Project to Project Transfers</li>
        </ul>
    </div>
@endif

<style>
    .section-heading {
        background: #1e3a8a;
        color: #fff;
        padding: 8px 12px;
        border-radius: 4px;
        margin: 15px 0 10px;
        font-size: 12px;
        font-weight: bold;
    }
    @media print {
        body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
        .sidebar, .no-print, .breadcrumb, #sidebar, .dropdown { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        .card { box-shadow: none !important; border: none !important; }
        .section-heading { background: #1e3a8a !important; -webkit-print-color-adjust: exact; }
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
