@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Current Time -->
<div class="card mb-3 bg-dark text-white">
    <div class="card-body d-flex justify-content-between align-items-center py-2">
        <div>
            <i class="fas fa-clock me-2"></i> Current Time (EAT)
        </div>
        <div class="text-end">
            <h5 class="mb-0" id="currentTime">{{ \Carbon\Carbon::now('Africa/Addis_Ababa')->format('d/m/Y H:i:s') }}</h5>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center py-3">
                <i class="fas fa-box fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $totalItems }}</h4>
                <small>Items</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center py-3">
                <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $totalLocations }}</h4>
                <small>Projects</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center py-3">
                <i class="fas fa-calendar-day fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $todayTransactions }}</h4>
                <small>Today</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body text-center py-3">
                <i class="fas fa-calendar-week fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $weekTransactions }}</h4>
                <small>This Week</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body text-center py-3">
                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $monthTransactions }}</h4>
                <small>This Month</small>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body text-center py-3">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <h4 class="mb-0">{{ $lowStockItems->count() }}</h4>
                <small>Low Stock</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Transactions -->
    <div class="col-lg-7 mb-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Transactions</h6>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th><th>Type</th><th>Item</th><th>Qty</th><th>From/To</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $t)
                            <tr>
                                <td><small>{{ $t->transaction_date->format('d/m/Y') }}</small></td>
                                <td>
                                    @php
                                        $badges = ['GRV'=>'success','ISTRV'=>'info','SIV'=>'warning','TRANSFER_OUT'=>'danger','STORE_RETURN'=>'primary','BEGINNING_BALANCE'=>'secondary'];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$t->transaction_type] ?? 'secondary' }}">{{ $t->transaction_type }}</span>
                                </td>
                                <td><small>{{ $t->item->name ?? 'N/A' }}</small></td>
                                <td><strong>{{ $t->quantity }}</strong></td>
                                <td><small>{{ $t->fromLocation->name ?? '-' }} → {{ $t->toLocation->name ?? '-' }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No recent transactions</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alerts</h6>
            </div>
            <div class="card-body">
                @forelse($lowStockItems as $item)
                <div class="alert alert-warning mb-2 p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->name }}</strong><br>
                            <small>Stock: {{ $item->getCurrentStock(auth()->user()->location_id ?? 1) }} {{ $item->unit }} (Min: {{ $item->min_stock_level }})</small>
                        </div>
                        <span class="badge bg-danger">Low</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <p class="mb-0">All items well stocked!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-3 mb-3">
        <a href="{{ route('transactions.create') }}" class="text-decoration-none">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-plus-circle fa-2x mb-2"></i>
                    <h6 class="mb-0">New Transaction</h6>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('items.index') }}" class="text-decoration-none">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-boxes fa-2x mb-2"></i>
                    <h6 class="mb-0">Manage Items</h6>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('reports.stock-balance') }}" class="text-decoration-none">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-balance-scale fa-2x mb-2"></i>
                    <h6 class="mb-0">Stock Balance</h6>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-3">
        <a href="{{ route('reports.weekly-stock-status') }}" class="text-decoration-none">
            <div class="card bg-warning text-dark shadow-sm h-100">
                <div class="card-body text-center py-3">
                    <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                    <h6 class="mb-0">Weekly Stock Status</h6>
                </div>
            </div>
        </a>
    </div>
</div>

@push('scripts')
<script>
function updateTime() {
    var now = new Date();
    var eatTime = new Date(now.toLocaleString('en-US', { timeZone: 'Africa/Addis_Ababa' }));
    var dd = String(eatTime.getDate()).padStart(2, '0');
    var mm = String(eatTime.getMonth() + 1).padStart(2, '0');
    var yyyy = eatTime.getFullYear();
    var hh = String(eatTime.getHours()).padStart(2, '0');
    var min = String(eatTime.getMinutes()).padStart(2, '0');
    var ss = String(eatTime.getSeconds()).padStart(2, '0');
    document.getElementById('currentTime').innerHTML = dd + '/' + mm + '/' + yyyy + ' ' + hh + ':' + min + ':' + ss;
}
setInterval(updateTime, 1000);
</script>
@endpush
@endsection
