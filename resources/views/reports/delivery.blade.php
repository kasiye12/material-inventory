@extends('layouts.app')
@section('title', 'Delivery Report')
@section('page-title', 'Material Delivery Report')

@section('content')
<!-- Filter Form -->
<div class="card mb-3 no-print">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Report Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.delivery') }}" class="row align-items-end">
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2 mb-2">
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
            <div class="col-md-2 mb-2">
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
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-bold">Report Section</label>
                <select class="form-select" name="section">
                    <option value="">All Sections</option>
                    <option value="regular" {{ request('section') == 'regular' ? 'selected' : '' }}>Regular Materials</option>
                    <option value="fuel" {{ request('section') == 'fuel' ? 'selected' : '' }}>Fuel Receiving (FRV)</option>
                    <option value="fixed" {{ request('section') == 'fixed' ? 'selected' : '' }}>Fixed Assets</option>
                    <option value="used" {{ request('section') == 'used' ? 'selected' : '' }}>Used Materials</option>
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
    $hasFilters = request('date_from') || request('location_id') || request('from_location_id') || request('section');
    $dateFrom = request('date_from', date('Y-m-d'));
    $dateTo = request('date_to', date('Y-m-d'));
    $section = request('section');
    
    $user = auth()->user();
    $accessibleIds = $user->getAccessibleProjectIds();
    $locationName = request('location_id') ? App\Models\Location::find(request('location_id'))->name : 'All Locations';
    
    $baseQuery = App\Models\StockTransaction::with(['item.category', 'fromLocation', 'toLocation'])
        ->whereBetween('transaction_date', [$dateFrom, $dateTo]);
    
    if (!$user->isHighLevelRole()) {
        $baseQuery->whereIn('to_location_id', $accessibleIds);
    }
    if (request('from_location_id')) {
        $baseQuery->where('from_location_id', request('from_location_id'));
    }
    if (request('location_id')) {
        $baseQuery->where('to_location_id', request('location_id'));
    }
@endphp

@if($hasFilters || request('date_from'))
    <div class="no-print mb-3 d-flex gap-2 justify-content-end">
        <button class="btn btn-primary btn-sm" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print
        </button>
        <a href="{{ route('reports.delivery.export', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'from_location_id' => request('from_location_id'), 'location_id' => request('location_id'), 'section' => $section, 'format' => 'pdf']) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('reports.delivery.export', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'from_location_id' => request('from_location_id'), 'location_id' => request('location_id'), 'section' => $section, 'format' => 'excel']) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel me-1"></i> Excel
        </a>
    </div>

    <div class="print-area">
        <div class="card">
            <div class="card-body p-4">
                
                {{-- SECTION 1: Regular Materials --}}
                @if(!$section || $section == 'regular')
                @php
                    $regularItems = (clone $baseQuery)->whereIn('transaction_type', ['GRV', 'ISTRV'])
                        ->orderBy('transaction_date', 'asc')->get();
                    $groupedRegular = $regularItems->groupBy(fn($d) => $d->item->category->name ?? 'Uncategorized');
                @endphp
                
                @if($regularItems->count() > 0)
                <div style="display: flex; align-items: center; justify-content: center; border-bottom: 3px double #1e293b; padding-bottom: 10px; margin-bottom: 15px; position: relative;">
                    <div style="position: absolute; left: 0;">
                        <img src="{{ asset('images/company-logo.png') }}" alt="Logo" style="width: 80px; height: 50px;">
                    </div>
                    <div style="text-align: center;">
                        <h5 style="font-weight: bold; margin: 0;">ቲ ኤን ቲ ኮንስትራክሽንና ንግድ ሥራዎች</h5>
                        <p style="font-style: italic; margin: 0;">TNT Construction & Trading</p>
                    </div>
                    <div style="position: absolute; right: 0; font-size: 10px; text-align: right;">
                        <strong>Document No:</strong> OF/TNT/SUP/033<br>
                        <strong>Issue No:</strong> 1<br>
                        <strong>Page No:</strong> Page 1 of 1
                    </div>
                </div>
                
                <div style="text-align: center; margin-bottom: 10px;">
                    <strong>Daily Material Delivery Report</strong>
                </div>
                
                <p style="text-align: center; text-decoration: underline; font-weight: bold; margin-bottom: 5px;">
                    List of Items Purchased through Head Office, Transfer from project & Main Store to {{ $locationName }}
                </p>
                
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 10px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th style="text-align: center;">NO</th>
                                <th>Item Description</th>
                                <th style="text-align: center;">Unit</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">ISTV NO</th>
                                <th style="text-align: center;">ISTRV NO</th>
                                <th style="text-align: center;">Delivery Date</th>
                                <th style="text-align: center;">FROM</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @forelse($groupedRegular as $category => $items)
                            <tr style="background: #e5e7eb;">
                                <td colspan="9"><strong>{{ $category }}</strong></td>
                            </tr>
                            @foreach($items as $d)
                            <tr>
                                <td style="text-align: center;">{{ $counter++ }}</td>
                                <td>{{ $d->item->name }}</td>
                                <td style="text-align: center;">{{ $d->item->unit }}</td>
                                <td style="text-align: center;">{{ $d->quantity }}</td>
                                <td style="text-align: center;">{{ $d->reference_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $d->document_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $d->transaction_date->format('d/m/Y') }}</td>
                                <td style="text-align: center;">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                                <td>{{ $d->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                            @empty
                            <tr><td colspan="9" style="text-align: center; padding: 15px; color: #999;">No regular materials found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif
                @endif
                
                {{-- SECTION 2: Fuel Receiving Voucher (FRV) --}}
                @if(!$section || $section == 'fuel')
                @php
                    $fuelItems = (clone $baseQuery)->where('transaction_type', 'FRV')
                        ->orderBy('transaction_date', 'asc')->get();
                @endphp
                
                @if($fuelItems->count() > 0)
                <div style="display: flex; align-items: center; justify-content: center; border-bottom: 3px double #1e293b; padding-bottom: 10px; margin: 20px 0 15px; position: relative;">
                    <div style="position: absolute; left: 0;">
                        <img src="{{ asset('images/company-logo.png') }}" alt="Logo" style="width: 80px; height: 50px;">
                    </div>
                    <div style="text-align: center;">
                        <h5 style="font-weight: bold; margin: 0;">ቲ. ኤን. ቲ. ኮንስትራክሽንና ንግድ ሥራዎች</h5>
                        <p style="font-style: italic; margin: 0;">TNT Construction & Trading</p>
                    </div>
                    <div style="position: absolute; right: 0; font-size: 10px; text-align: right;">
                        <strong>Document No:</strong> OF/TNT/SUP/032<br>
                        <strong>Issue No:</strong> 1<br>
                        <strong>Page No:</strong> Page 1 of 1
                    </div>
                </div>
                
                <div style="text-align: center; margin-bottom: 10px;">
                    <strong>Daily Material Delivery Report</strong>
                </div>
                
                <p style="text-align: center; text-decoration: underline; font-weight: bold; margin-bottom: 5px;">
                    List of Items Purchased On Site To {{ $locationName }}
                </p>
                
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 10px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th style="text-align: center;">No</th>
                                <th>Item Description</th>
                                <th style="text-align: center;">Unit</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">FRV NO.</th>
                                <th style="text-align: center;">Delivery Date</th>
                                <th style="text-align: center;">FROM</th>
                                <th style="text-align: center;">Plate No</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($fuelItems as $d)
                            <tr>
                                <td style="text-align: center;">{{ $counter++ }}</td>
                                <td>{{ $d->item->name }}</td>
                                <td style="text-align: center;">{{ $d->item->unit }}</td>
                                <td style="text-align: center;">{{ $d->quantity }}</td>
                                <td style="text-align: center;">{{ $d->reference_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $d->transaction_date->format('d/m/Y') }}</td>
                                <td style="text-align: center;">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                                <td style="text-align: center;">{{ $d->remarks ?? '' }}</td>
                                <td>{{ $d->document_number ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @endif
                
                {{-- SECTION 3: Fixed Assets --}}
                @if(!$section || $section == 'fixed')
                @php
                    $fixedItems = (clone $baseQuery)->where('transaction_type', 'FARV')
                        ->orderBy('transaction_date', 'asc')->get();
                @endphp
                
                @if($fixedItems->count() > 0)
                <p style="text-align: center; text-decoration: underline; font-weight: bold; margin: 20px 0 5px;">
                    List of Fixed Items Purchased Through Head Office to {{ $locationName }}
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 10px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th style="text-align: center;">No</th>
                                <th>Item Description</th>
                                <th style="text-align: center;">Unit</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">ISFATV NO</th>
                                <th style="text-align: center;">ISFATRV NO</th>
                                <th style="text-align: center;">Delivery Date</th>
                                <th style="text-align: center;">From</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($fixedItems as $d)
                            <tr>
                                <td style="text-align: center;">{{ $counter++ }}</td>
                                <td>{{ $d->item->name }}</td>
                                <td style="text-align: center;">{{ $d->item->unit }}</td>
                                <td style="text-align: center;">{{ $d->quantity }}</td>
                                <td style="text-align: center;">{{ $d->reference_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $d->document_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $d->transaction_date->format('d/m/Y') }}</td>
                                <td style="text-align: center;">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                                <td>{{ $d->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @endif
                
                {{-- SECTION 4: Used Materials --}}
                @if(!$section || $section == 'used')
                @php
                    $usedItems = (clone $baseQuery)->where('transaction_type', 'UMTRV')
                        ->orderBy('transaction_date', 'asc')->get();
                @endphp
                
                @if($usedItems->count() > 0)
                <p style="text-align: center; text-decoration: underline; font-weight: bold; margin: 20px 0 5px;">
                    List of Used Items Purchased through Head Office to {{ $locationName }}
                </p>
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: 10px;">
                        <thead>
                            <tr style="background: #4b5563; color: #fff;">
                                <th style="text-align: center;">No</th>
                                <th>Item Description</th>
                                <th style="text-align: center;">Unit</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">UMTR NO</th>
                                <th style="text-align: center;">UMTRV No</th>
                                <th style="text-align: center;">Delivery Date</th>
                                <th style="text-align: center;">FROM</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($usedItems as $d)
                            <tr>
                                <td style="text-align: center;">{{ $counter++ }}</td>
                                <td>{{ $d->item->name }}</td>
                                <td style="text-align: center;">{{ $d->item->unit }}</td>
                                <td style="text-align: center;">{{ $d->quantity }}</td>
                                <td style="text-align: center;">{{ $d->reference_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $d->document_number ?? '' }}</td>
                                <td style="text-align: center;">{{ $d->transaction_date->format('d/m/Y') }}</td>
                                <td style="text-align: center;">{{ $d->fromLocation->name ?? 'Head Office' }}</td>
                                <td>{{ $d->remarks ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info no-print">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Select filters and click "Generate" to view the report.</strong>
        <br><br>
        <strong>Report Sections:</strong>
        <ul class="mb-0">
            <li>📦 Regular Materials (ISTV NO + ISTRV NO) - OF/TNT/SUP/033</li>
            <li>⛽ Fuel Receiving (FRV NO) - OF/TNT/SUP/032</li>
            <li>🏗️ Fixed Assets (ISFATV NO + ISFATRV NO)</li>
            <li>♻️ Used Materials (UMTR NO + UMTRV NO)</li>
        </ul>
    </div>
@endif

<style>
@media print {
    body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
    .sidebar, .no-print, .breadcrumb, #sidebar, .dropdown { display: none !important; }
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
