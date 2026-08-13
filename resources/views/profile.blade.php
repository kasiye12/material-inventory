@extends('layouts.app')
@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Profile Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Name</th>
                        <td>{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ auth()->user()->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            <span class="badge bg-primary">
                                {{ ucwords(str_replace('_', ' ', auth()->user()->roles->first()->name ?? 'User')) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Default Location</th>
                        <td>{{ auth()->user()->location->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Assigned Projects</h6>
            </div>
            <div class="card-body">
                @if(auth()->user()->isHighLevelRole())
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        You have access to all projects.
                    </div>
                @else
                    @php
                        $projects = auth()->user()->assignedProjects;
                    @endphp
                    @if($projects->count() > 0)
                        <ul class="list-group">
                            @foreach($projects as $project)
                            <li class="list-group-item">
                                <i class="fas fa-building me-2 text-primary"></i>
                                <strong>{{ $project->code }}</strong> - {{ $project->name }}
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No projects assigned. Contact administrator.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Information</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="text-center p-3 border rounded">
                    <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                    <h6 class="mb-1">Current Time</h6>
                    <p class="mb-0" id="profileTime">{{ \Carbon\Carbon::now('Africa/Addis_Ababa')->format('d/m/Y H:i:s') }}</p>
                    <small class="text-muted">EAT (UTC+3)</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="text-center p-3 border rounded">
                    <i class="fas fa-boxes fa-2x text-success mb-2"></i>
                    <h6 class="mb-1">Total Items</h6>
                    <p class="mb-0">{{ App\Models\Item::where('is_active', true)->count() }}</p>
                    <small class="text-muted">Active items in system</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="text-center p-3 border rounded">
                    <i class="fas fa-map-marker-alt fa-2x text-info mb-2"></i>
                    <h6 class="mb-1">Total Locations</h6>
                    <p class="mb-0">{{ App\Models\Location::where('is_active', true)->count() }}</p>
                    <small class="text-muted">Active projects and stores</small>
                </div>
            </div>
        </div>
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
    document.getElementById('profileTime').innerHTML = dd + '/' + mm + '/' + yyyy + ' ' + hh + ':' + min + ':' + ss;
}
setInterval(updateTime, 1000);
</script>
@endpush
@endsection
