<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MIMS') - TNT Construction & Trading</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root { --primary: #2563eb; --sidebar-width: 250px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f1f5f9; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-width); background: linear-gradient(135deg, #1e293b, #334155); color: #fff; z-index: 1000; overflow-y: auto; }
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header h4 { font-weight: 700; margin: 0; }
        .sidebar-header small { color: #fbbf24; }
        .nav-menu { padding: 15px; }
        .nav-link { display: flex; align-items: center; padding: 10px 15px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; margin-bottom: 3px; transition: all 0.3s; font-size: 13px; }
        .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .nav-link.active { background: var(--primary); color: #fff; }
        .nav-link i { width: 20px; margin-right: 10px; }
        .nav-section { color: #fbbf24; font-size: 10px; text-transform: uppercase; padding: 10px 15px 5px; letter-spacing: 1px; font-weight: bold; }
        .main-content { margin-left: var(--sidebar-width); padding: 20px; min-height: 100vh; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .user-info { background: rgba(255,255,255,0.05); padding: 10px 15px; margin: 10px; border-radius: 8px; font-size: 11px; }
        .user-info .role-badge { color: #fbbf24; font-weight: bold; }
        @media (max-width: 768px) { .sidebar { width: 0; } .main-content { margin-left: 0; } }
        @media print {
            .sidebar, #sidebar, .dropdown, .d-flex.justify-content-between.align-items-center.mb-4, .breadcrumb { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
        }
    </style>
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>MIMS</h4>
            <small>Material & Inventory</small>
        </div>
        
        <div class="user-info">
            <i class="fas fa-user-circle me-1"></i>
            <strong>{{ auth()->user()->name }}</strong><br>
            <span class="role-badge">
                <i class="fas fa-shield-alt me-1"></i>
                {{ ucwords(str_replace('_', ' ', auth()->user()->roles->first()->name ?? 'User')) }}
            </span>
        </div>
        
        <nav class="nav-menu">
            <!-- Dashboard - All users -->
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            
            <!-- Master Data - Only Admin, Head Office, Master Data -->
            @if(auth()->user()->can('manage items') || auth()->user()->can('manage categories') || auth()->user()->can('manage locations'))
            <div class="nav-section">Master Data</div>
            @if(auth()->user()->can('manage items'))
            <a href="{{ route('items.index') }}" class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Items
            </a>
            @endif
            @if(auth()->user()->can('manage categories'))
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Categories
            </a>
            @endif
            @if(auth()->user()->can('manage locations'))
            <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt"></i> Locations
            </a>
            @endif
            @endif
            
            <!-- Transactions - Only Admin, Head Office, Storekeeper, Site Engineer -->
            @if(auth()->user()->can('create transactions') || auth()->user()->can('view transactions') || auth()->user()->can('view own transactions'))
            <div class="nav-section">Transactions</div>
            <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> Transactions
            </a>
            @endif
            
            <!-- Reports - All users with any report permission -->
            @if(auth()->user()->can('view all reports') || auth()->user()->can('view own reports'))
            <div class="nav-section">Reports</div>
            <a href="{{ route('reports.delivery') }}" class="nav-link {{ request()->routeIs('reports.delivery') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Delivery Report
            </a>
            <a href="{{ route('reports.quarry-delivery') }}" class="nav-link {{ request()->routeIs('reports.quarry-delivery') ? 'active' : '' }}">
                <i class="fas fa-mountain"></i> Quarry Delivery
            </a>
            <a href="{{ route('reports.stock-ledger') }}" class="nav-link {{ request()->routeIs('reports.stock-ledger') ? 'active' : '' }}">
                <i class="fas fa-book"></i> Stock Ledger
            </a>
            <a href="{{ route('reports.stock-balance') }}" class="nav-link {{ request()->routeIs('reports.stock-balance') ? 'active' : '' }}">
                <i class="fas fa-balance-scale"></i> Stock Balance
            </a>
            <a href="{{ route('reports.weekly-transfer') }}" class="nav-link {{ request()->routeIs('reports.weekly-transfer') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i> Weekly Transfer
            </a>
            <a href="{{ route('reports.weekly-stock-status') }}" class="nav-link {{ request()->routeIs('reports.weekly-stock-status') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Weekly Stock Status
            </a>
            <a href="{{ route('reports.weekly-report') }}" class="nav-link {{ request()->routeIs('reports.weekly-report') ? 'active' : '' }}">
                <i class="fas fa-calendar-week"></i> Weekly Report
            </a>
            <a href="{{ route('reports.monthly-report') }}" class="nav-link {{ request()->routeIs('reports.monthly-report') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Monthly Report
            </a>
            @endif
            
            <!-- Administration - Only Admin -->
            @if(auth()->user()->can('manage users') || auth()->user()->hasRole('admin'))
            <div class="nav-section">Administration</div>
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Users & Roles
            </a>
            <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Activity Logs
            </a>
            @endif
        </nav>
    </aside>
    
    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4>@yield('page-title', 'Dashboard')</h4>
                <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0">@yield('breadcrumb')</ol></nav>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-2"></i>{{ auth()->user()->name }}
                    <span class="badge bg-primary ms-1">{{ auth()->user()->roles->first()->name ?? 'User' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user-circle me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('help') }}"><i class="fas fa-question-circle me-2"></i>Help</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @yield('content')
    </main>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        @if(session('success')) Toast.fire({ icon: 'success', title: '{{ session('success') }}' }); @endif
        @if(session('error')) Toast.fire({ icon: 'error', title: '{{ session('error') }}' }); @endif
    </script>
    @stack('scripts')
</body>
</html>
