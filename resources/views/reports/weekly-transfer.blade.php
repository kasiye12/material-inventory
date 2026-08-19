@extends('layouts.app')
@section('title', 'Weekly Transfer')
@section('page-title', 'Weekly Material Transfer Report')

@section('content')
<!-- Filter Form - Always Visible -->
<div class="card mb-3 no-print">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Report Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.weekly-transfer') }}" class="row align-items-end">
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">Report Period</label>
                <select class="form-select" name="period_type" onchange="this.form.submit()">
                    <option value="daily" {{ request('period_type', 'daily') == 'daily' ? 'selected' : '' }}>📅 Daily</option>
                    <option value="weekly" {{ request('period_type') == 'weekly' ? 'selected' : '' }}>📅 Weekly</option>
                    <option value="monthly" {{ request('period_type') == 'monthly' ? 'selected' : '' }}>📅 Monthly</option>
                </select>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from', \Carbon\Carbon::now('Africa/Addis_Ababa')->startOfWeek()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-bold">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to', \Carbon\Carbon::now('Africa/Addis_Ababa')->endOfWeek()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4 mb-2">
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
            <div class="col-md-2 mb-2">
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

@if($hasFilters)
    <!-- Action Buttons -->
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
                <!-- Header -->
                <div style="display: flex; align-items: center; justify-content: center; border-bottom: 3px double #1e293b; padding-bottom: 10px; margin-bottom: 15px; position: relative;">
                    <div style="position: absolute; left: 0;">
                        <img src="{{ asset('images/company-logo.png') }}" alt="TNT Logo" style="width: 80px; height: 50px;">
                    </div>
                    <div style="text-align: center;">
                        <h5 style="font-weight: bold; margin: 0;">ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች</h5>
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
                    <strong style="font-size: 13px;">Weekly Report {{ date('d/m/Y', strtotime(request('date_from', date('Y-m-d')))) }} - {{ date('d/m/Y', strtotime(request('date_to', date('Y-m-d')))) }}</strong>
                </div>
                
                <p style="text-align: center; text-decoration: underline; font-weight: bold; margin-bottom: 10px;">
                    Material Transfer From Project To Project
                </p>
                
                @php
                    $user = auth()->user();
                    $accessibleIds = $user->getAccessibleProjectIds();
                    $dateFrom = request('date_from', \Carbon\Carbon::now('Africa/Addis_Ababa')->startOfWeek()->format('Y-m-d'));
                    $dateTo = request('date_to', \Carbon\Carbon::now('Africa/Addis_Ababa')->endOfWeek()->format('Y-m-d'));
                    
                    $query = App\Models\StockTransaction::with(['item', 'fromLocation', 'toLocation'])
                        ->where('transaction_type', 'TRANSFER_OUT')
                        ->whereBetween('transaction_date', [$dateFrom, $dateTo]);
                    
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
                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 10px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th style="text-align: center; width: 40px;">No</th>
                                <th>Item Description</th>
                                <th style="text-align: center; width: 50px;">Unit</th>
                                <th style="text-align: center; width: 70px;">Requested Qty</th>
                                <th style="text-align: center; width: 70px;">SR.No</th>
                                <th style="text-align: center; width: 80px;">Date</th>
                                <th>From Project</th>
                                <th style="text-align: center; width: 70px;">Out/SIV NO</th>
                                <th>To Project</th>
                                <th style="text-align: center; width: 70px;">In NO</th>
                                <th style="text-align: center; width: 70px;">Received QTY</th>
                                <th style="text-align: center; width: 80px;">Delivered Date</th>
                                <th style="text-align: center; width: 70px;">Remaining QTY</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @forelse($transfers as $t)
                            <tr>
                                <td style="text-align: center;">{{ $counter++ }}</td>
                                <td>{{ $t->item->name ?? '' }}</td>
                                <td style="text-align: center;">{{ $t->item->unit ?? '' }}</td>
                                <td style="text-align: center;">{{ $t->quantity }}</td>
                                <td style="text-align: center;">{{ $t->reference_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $t->transaction_date->format('d/m/Y') }}</td>
                                <td>{{ $t->fromLocation->name ?? '' }}</td>
                                <td style="text-align: center;">{{ $t->reference_number ?? '' }}</td>
                                <td>{{ $t->toLocation->name ?? '' }}</td>
                                <td style="text-align: center;">{{ $t->document_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $t->quantity }}</td>
                                <td style="text-align: center;">{{ $t->transaction_date->format('d/m/Y') }}</td>
                                <td style="text-align: center;"></td>
                                <td>{{ $t->remarks ?? '' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" style="text-align: center; padding: 30px; color: #999;">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    No transfer records found for the selected period
                                </td>
                            </tr>
                            @endforelse
                            @if($transfers->count() > 0)
                            <tr style="background: #e5e7eb; font-weight: bold;">
                                <td colspan="3" style="text-align: right;">Total:</td>
                                <td style="text-align: center;">{{ $transfers->sum('quantity') }}</td>
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
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Select filters above and click "Generate" to view the Weekly Material Transfer Report.</strong>
    </div>
@endif

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
