@extends('layouts.app')
@section('title', 'Weekly Report')
@section('page-title', 'Weekly Transaction Report')

@section('content')
<!-- Filters -->
<div class="card mb-4 no-print">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.weekly-report') }}" class="row">
            <div class="col-md-3">
                <label class="form-label small">From Date</label>
                <input type="date" class="form-control" name="date_from" value="{{ $dateFrom ?? date('Y-m-d', strtotime('monday this week')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">To Date</label>
                <input type="date" class="form-control" name="date_to" value="{{ $dateTo ?? date('Y-m-d', strtotime('sunday this week')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Generate
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label small">&nbsp;</label>
                <button type="button" class="btn btn-success w-100" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Week Info -->
<div class="alert alert-info no-print">
    <i class="fas fa-calendar-week me-2"></i>
    <strong>Week {{ $weekNumber ?? '' }}</strong> | Period: {{ date('d/m/Y', strtotime($dateFrom ?? date('Y-m-d'))) }} - {{ date('d/m/Y', strtotime($dateTo ?? date('Y-m-d'))) }}
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <i class="fas fa-download fa-2x mb-2"></i>
                <h4 class="mb-0">{{ number_format($summary['total_grv'] ?? 0, 2) }}</h4>
                <small>GRV Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center py-3">
                <i class="fas fa-upload fa-2x mb-2"></i>
                <h4 class="mb-0">{{ number_format($summary['total_siv'] ?? 0, 2) }}</h4>
                <small>SIV Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center py-3">
                <i class="fas fa-exchange-alt fa-2x mb-2"></i>
                <h4 class="mb-0">{{ number_format($summary['total_transfer'] ?? 0, 2) }}</h4>
                <small>Transfer Out</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <i class="fas fa-undo fa-2x mb-2"></i>
                <h4 class="mb-0">{{ number_format($summary['total_return'] ?? 0, 2) }}</h4>
                <small>Store Return</small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Transactions This Week</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $user = auth()->user();
                        $accessibleIds = $user->getAccessibleProjectIds();
                        $weekTransactions = App\Models\StockTransaction::with(['item', 'fromLocation', 'toLocation'])
                            ->whereBetween('transaction_date', [$dateFrom ?? date('Y-m-d', strtotime('monday this week')), $dateTo ?? date('Y-m-d', strtotime('sunday this week'))])
                            ->when(!$user->isHighLevelRole(), function($q) use ($accessibleIds) {
                                return $q->where(function($sub) use ($accessibleIds) {
                                    $sub->whereIn('from_location_id', $accessibleIds)
                                        ->orWhereIn('to_location_id', $accessibleIds);
                                });
                            })
                            ->orderBy('transaction_date', 'desc')
                            ->take(30)
                            ->get();
                    @endphp
                    
                    @forelse($weekTransactions as $t)
                    <tr>
                        <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $badges = ['GRV'=>'success','ISTRV'=>'info','SIV'=>'warning','TRANSFER_OUT'=>'danger','STORE_RETURN'=>'primary','BEGINNING_BALANCE'=>'secondary'];
                            @endphp
                            <span class="badge bg-{{ $badges[$t->transaction_type] ?? 'secondary' }}">{{ $t->transaction_type }}</span>
                        </td>
                        <td>{{ $t->item->name ?? 'N/A' }}</td>
                        <td class="text-end">{{ $t->quantity }}</td>
                        <td>{{ $t->fromLocation->name ?? '-' }}</td>
                        <td>{{ $t->toLocation->name ?? '-' }}</td>
                        <td>{{ $t->reference_number ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No transactions found for this week</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
