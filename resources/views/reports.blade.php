@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<div class="top-bar">
    <div>
        <h4><i class="fas fa-chart-bar me-2"></i> Professional Reports</h4>
        <nav class="breadcrumb">
            <span class="breadcrumb-item"><a href="/dashboard">Dashboard</a></span>
            <span class="breadcrumb-item active">Reports</span>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-light btn-sm" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
        <button class="btn-gradient btn-sm" onclick="exportReport()">
            <i class="fas fa-download"></i> Export CSV
        </button>
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<!-- Report Type Tabs -->
<ul class="nav nav-tabs mb-4" id="reportTabs">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#daily">
            <i class="fas fa-calendar-day me-1"></i> Daily Report
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#weekly">
            <i class="fas fa-calendar-week me-1"></i> Weekly Report
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#monthly">
            <i class="fas fa-calendar-alt me-1"></i> Monthly Report
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#custom">
            <i class="fas fa-filter me-1"></i> Custom Range
        </a>
    </li>
</ul>

<div class="tab-content">
    
    <!-- ============ DAILY REPORT ============ -->
    <div class="tab-pane fade show active" id="daily">
        @php
            $dailyDate = request('daily_date', now()->format('Y-m-d'));
            $dayPayments = \App\Models\Payment::whereDate('paid_at', $dailyDate)
                ->where('payment_status', 'completed')->get();
            $dayTickets = \App\Models\ParkingTicket::whereDate('entry_time', $dailyDate)->get();
            $dayRevenue = $dayPayments->sum('total_amount');
            $dayVAT = $dayPayments->sum('vat_amount');
            $dayTxns = $dayPayments->count();
            $dayActive = $dayTickets->where('status', 'active')->count();
            $dayExited = $dayTickets->where('status', 'exited')->count();
        @endphp
        
        <div class="card-dark mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label-dark">Select Date</label>
                    <input type="date" name="daily_date" class="form-control-dark form-control" value="{{ $dailyDate }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-gradient"><i class="fas fa-search"></i> Generate</button>
                </div>
            </form>
        </div>
        
        <!-- Daily KPI -->
        <div class="row g-4 mb-4">
            <div class="col-md-2 col-6"><div class="card-dark text-center"><div style="font-size:26px;font-weight:800;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ number_format($dayRevenue,0) }}</div><small class="text-muted">Revenue (ብር)</small></div></div>
            <div class="col-md-2 col-6"><div class="card-dark text-center"><div style="font-size:26px;font-weight:800;color:#28a745;">{{ $dayTxns }}</div><small class="text-muted">Transactions</small></div></div>
            <div class="col-md-2 col-6"><div class="card-dark text-center"><div style="font-size:26px;font-weight:800;color:#ffc107;">{{ number_format($dayVAT,0) }}</div><small class="text-muted">VAT (ብር)</small></div></div>
            <div class="col-md-2 col-6"><div class="card-dark text-center"><div style="font-size:26px;font-weight:800;color:#17a2b8;">{{ $dayTickets->count() }}</div><small class="text-muted">Total Tickets</small></div></div>
            <div class="col-md-2 col-6"><div class="card-dark text-center"><div style="font-size:26px;font-weight:800;color:#dc3545;">{{ $dayActive }}</div><small class="text-muted">Still Active</small></div></div>
            <div class="col-md-2 col-6"><div class="card-dark text-center"><div style="font-size:26px;font-weight:800;color:#f093fb;">{{ $dayExited }}</div><small class="text-muted">Exited</small></div></div>
        </div>
        
        <!-- Payment Method Breakdown -->
        <div class="card-dark mb-4">
            <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i> Revenue by Payment Method</h5>
            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead><tr><th>Method</th><th>Transactions</th><th>Revenue</th><th>Share</th></tr></thead>
                    <tbody>
                        @php $methods = ['cash'=>'💵 Cash','telebirr'=>'📱 Telebirr','cbe_birr'=>'🏦 CBE Birr','chapa'=>'💳 Chapa']; @endphp
                        @foreach($methods as $key => $label)
                        @php $mt = $dayPayments->where('payment_method', $key); $mtTotal = $mt->sum('total_amount'); $mtCount = $mt->count(); $mtShare = $dayRevenue > 0 ? ($mtTotal/$dayRevenue)*100 : 0; @endphp
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ $mtCount }}</td>
                            <td><strong>{{ number_format($mtTotal,2) }} ብር</strong></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1"><div class="progress-bar" style="width:{{ $mtShare }}%;background:{{ $loop->first?'#28a745':($loop->iteration==2?'#007bff':($loop->iteration==3?'#ffc107':'#17a2b8')) }};"></div></div>
                                    <small>{{ round($mtShare,1) }}%</small>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="fw-bold"><tr><td>Total</td><td>{{ $dayTxns }}</td><td>{{ number_format($dayRevenue,2) }} ብር</td><td>100%</td></tr></tfoot>
                </table>
            </div>
        </div>
        
        <!-- Hourly Breakdown -->
        <div class="card-dark">
            <h5 class="mb-3"><i class="fas fa-clock me-2"></i> Hourly Transaction Breakdown</h5>
            <div class="table-responsive">
                <table class="table table-dark-custom table-sm">
                    <thead><tr><th>Hour</th><th>Transactions</th><th>Revenue (ብር)</th></tr></thead>
                    <tbody>
                        @for($h = 6; $h <= 22; $h++)
                        @php $hp = $dayPayments->filter(fn($p) => $p->paid_at->format('H') == str_pad($h,2,'0',STR_PAD_LEFT)); @endphp
                        <tr>
                            <td>{{ str_pad($h,2,'0',STR_PAD_LEFT) }}:00 - {{ str_pad($h+1,2,'0',STR_PAD_LEFT) }}:00</td>
                            <td>{{ $hp->count() }}</td>
                            <td>{{ number_format($hp->sum('total_amount'),2) }}</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============ WEEKLY REPORT ============ -->
    <div class="tab-pane fade" id="weekly">
        @php
            $weekStart = request('week_start', now()->startOfWeek()->format('Y-m-d'));
            $weekEnd = \Carbon\Carbon::parse($weekStart)->addDays(6)->format('Y-m-d');
            $weekPayments = \App\Models\Payment::whereBetween('paid_at', [$weekStart.' 00:00:00', $weekEnd.' 23:59:59'])
                ->where('payment_status', 'completed')->get();
            $weekRevenue = $weekPayments->sum('total_amount');
            $weekTxns = $weekPayments->count();
            $weekVAT = $weekPayments->sum('vat_amount');
        @endphp
        
        <div class="card-dark mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label-dark">Week Starting</label>
                    <input type="date" name="week_start" class="form-control-dark form-control" value="{{ $weekStart }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-gradient">Generate</button>
                </div>
            </form>
        </div>
        
        <div class="row g-4 mb-4">
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:32px;font-weight:800;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ number_format($weekRevenue,0) }}</div><small class="text-muted">Week Revenue (ብር)</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:32px;font-weight:800;color:#28a745;">{{ $weekTxns }}</div><small class="text-muted">Transactions</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:32px;font-weight:800;color:#ffc107;">{{ number_format($weekVAT,0) }}</div><small class="text-muted">VAT (ብር)</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:32px;font-weight:800;color:#17a2b8;">{{ $weekTxns > 0 ? number_format($weekRevenue/$weekTxns,0) : 0 }}</div><small class="text-muted">Avg/Day (ብር)</small></div></div>
        </div>
        
        <!-- Day by Day -->
        <div class="card-dark">
            <h5 class="mb-3"><i class="fas fa-calendar-week me-2"></i> Day by Day Breakdown ({{ \Carbon\Carbon::parse($weekStart)->format('M d') }} - {{ \Carbon\Carbon::parse($weekEnd)->format('M d, Y') }})</h5>
            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead><tr><th>Day</th><th>Date</th><th>Transactions</th><th>Revenue</th><th>VAT</th><th>Trend</th></tr></thead>
                    <tbody>
                        @php $prevDay = 0; @endphp
                        @for($d = 0; $d < 7; $d++)
                        @php
                            $date = \Carbon\Carbon::parse($weekStart)->addDays($d);
                            $dayName = $date->format('D');
                            $dayPaymentsData = $weekPayments->filter(fn($p) => $p->paid_at->format('Y-m-d') == $date->format('Y-m-d'));
                            $dayTotal = $dayPaymentsData->sum('total_amount');
                            $trend = $prevDay > 0 ? (($dayTotal - $prevDay) / $prevDay) * 100 : 0;
                        @endphp
                        <tr>
                            <td><strong>{{ $dayName }}</strong></td>
                            <td>{{ $date->format('M d') }}</td>
                            <td>{{ $dayPaymentsData->count() }}</td>
                            <td><strong>{{ number_format($dayTotal,2) }} ብር</strong></td>
                            <td>{{ number_format($dayPaymentsData->sum('vat_amount'),2) }}</td>
                            <td>
                                @if($prevDay > 0)
                                    <span class="text-{{ $trend > 0 ? 'success' : ($trend < 0 ? 'danger' : 'muted') }}">
                                        {{ $trend > 0 ? '↑' : '↓' }} {{ round(abs($trend),1) }}%
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @php $prevDay = $dayTotal; @endphp
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============ MONTHLY REPORT ============ -->
    <div class="tab-pane fade" id="monthly">
        @php
            $month = request('month', now()->format('Y-m'));
            $monthStart = $month . '-01';
            $monthEnd = \Carbon\Carbon::parse($monthStart)->endOfMonth()->format('Y-m-d');
            $monthPayments = \App\Models\Payment::whereBetween('paid_at', [$monthStart.' 00:00:00', $monthEnd.' 23:59:59'])
                ->where('payment_status', 'completed')->get();
            $monthRevenue = $monthPayments->sum('total_amount');
            $monthTxns = $monthPayments->count();
            $monthVAT = $monthPayments->sum('vat_amount');
            $daysInMonth = \Carbon\Carbon::parse($monthStart)->daysInMonth;
        @endphp
        
        <div class="card-dark mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label-dark">Select Month</label>
                    <input type="month" name="month" class="form-control-dark form-control" value="{{ $month }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-gradient">Generate</button>
                </div>
            </form>
        </div>
        
        <!-- Monthly KPI -->
        <div class="row g-4 mb-4">
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:32px;font-weight:800;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ number_format($monthRevenue,0) }}</div><small class="text-muted">Monthly Revenue (ብር)</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:32px;font-weight:800;color:#28a745;">{{ $monthTxns }}</div><small class="text-muted">Transactions</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:32px;font-weight:800;color:#ffc107;">{{ number_format($monthVAT,0) }}</div><small class="text-muted">VAT (ብር)</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:32px;font-weight:800;color:#17a2b8;">{{ $daysInMonth > 0 ? number_format($monthRevenue/$daysInMonth,0) : 0 }}</div><small class="text-muted">Daily Avg (ብር)</small></div></div>
        </div>
        
        <!-- Payment Methods -->
        <div class="card-dark mb-4">
            <h5 class="mb-3">Revenue by Payment Method</h5>
            <div class="row g-3">
                @php $mmethods = ['cash'=>'💵 Cash','telebirr'=>'📱 Telebirr','cbe_birr'=>'🏦 CBE Birr','chapa'=>'💳 Chapa']; @endphp
                @foreach($mmethods as $key => $label)
                @php $mm = $monthPayments->where('payment_method', $key); $mmTotal = $mm->sum('total_amount'); $mmShare = $monthRevenue > 0 ? ($mmTotal/$monthRevenue)*100 : 0; @endphp
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.05);">
                        <div style="font-size:30px;">{{ explode(' ', $label)[0] }}</div>
                        <h6>{{ explode(' ', $label)[1] }}</h6>
                        <strong>{{ number_format($mmTotal,0) }} ብር</strong>
                        <div class="progress mt-2"><div class="progress-bar" style="width:{{ $mmShare }}%;background:#667eea;"></div></div>
                        <small>{{ round($mmShare,1) }}%</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Daily Breakdown for Month -->
        <div class="card-dark">
            <h5 class="mb-3">Daily Revenue for {{ \Carbon\Carbon::parse($monthStart)->format('F Y') }}</h5>
            <div class="table-responsive" style="max-height:400px;overflow-y:auto;">
                <table class="table table-dark-custom table-sm">
                    <thead><tr><th>Date</th><th>Day</th><th>Txns</th><th>Revenue</th><th>VAT</th></tr></thead>
                    <tbody>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $dateStr = $month . '-' . str_pad($d,2,'0',STR_PAD_LEFT);
                            $dp = $monthPayments->filter(fn($p) => $p->paid_at->format('Y-m-d') == $dateStr);
                        @endphp
                        <tr>
                            <td>{{ $dateStr }}</td>
                            <td>{{ \Carbon\Carbon::parse($dateStr)->format('D') }}</td>
                            <td>{{ $dp->count() }}</td>
                            <td><strong>{{ number_format($dp->sum('total_amount'),2) }} ብር</strong></td>
                            <td>{{ number_format($dp->sum('vat_amount'),2) }}</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============ CUSTOM REPORT ============ -->
    <div class="tab-pane fade" id="custom">
        @php
            $cFrom = request('c_from', now()->subDays(30)->format('Y-m-d'));
            $cTo = request('c_to', now()->format('Y-m-d'));
            $cPayments = \App\Models\Payment::whereBetween('paid_at', [$cFrom.' 00:00:00', $cTo.' 23:59:59'])
                ->where('payment_status', 'completed')->get();
            $cRevenue = $cPayments->sum('total_amount');
            $cTxns = $cPayments->count();
            $cVAT = $cPayments->sum('vat_amount');
        @endphp
        
        <div class="card-dark mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label-dark">From Date</label>
                    <input type="date" name="c_from" class="form-control-dark form-control" value="{{ $cFrom }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-dark">To Date</label>
                    <input type="date" name="c_to" class="form-control-dark form-control" value="{{ $cTo }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-gradient">Generate</button>
                </div>
            </form>
        </div>
        
        <div class="row g-4 mb-4">
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:28px;font-weight:800;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ number_format($cRevenue,0) }}</div><small class="text-muted">Revenue (ብር)</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:28px;font-weight:800;color:#28a745;">{{ $cTxns }}</div><small class="text-muted">Transactions</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:28px;font-weight:800;color:#ffc107;">{{ number_format($cVAT,0) }}</div><small class="text-muted">VAT (ብር)</small></div></div>
            <div class="col-md-3"><div class="card-dark text-center"><div style="font-size:28px;font-weight:800;color:#17a2b8;">{{ $cTxns > 0 ? number_format($cRevenue/$cTxns,0) : 0 }}</div><small class="text-muted">Avg Ticket (ብር)</small></div></div>
        </div>
        
        <div class="card-dark">
            <h5 class="mb-3">Transaction History</h5>
            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead><tr><th>Receipt</th><th>Ticket</th><th>Plate</th><th>Amount</th><th>Method</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($cPayments->take(50) as $p)
                        <tr>
                            <td>{{ $p->fiscal_receipt_number }}</td>
                            <td>{{ $p->ticket->ticket_number ?? 'N/A' }}</td>
                            <td>{{ $p->ticket->plate_number ?? 'N/A' }}</td>
                            <td><strong>{{ number_format($p->total_amount,2) }} ብር</strong></td>
                            <td>{{ ucfirst($p->payment_method) }}</td>
                            <td>{{ $p->paid_at->format('M d, H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No data for this period</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
function exportReport() {
    alert('📥 Export feature: Report data will be downloaded as CSV');
    // Generate CSV
    let csv = 'SaveParking Report\n';
    csv += 'Generated: ' + new Date().toLocaleString() + '\n\n';
    csv += 'Metric,Value\n';
    csv += 'Total Revenue,{{ number_format($dayRevenue ?? $weekRevenue ?? $monthRevenue ?? 0, 2) }} ETB\n';
    csv += 'Total Transactions,{{ $dayTxns ?? $weekTxns ?? $monthTxns ?? 0 }}\n';
    
    const blob = new Blob([csv], {type: 'text/csv'});
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'SaveParking_Report_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
}
</script>
@endsection
