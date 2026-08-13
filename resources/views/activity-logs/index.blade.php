@extends('layouts.app')
@section('title', 'Activity Logs')
@section('page-title', 'User Activity Logs')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>User Activity Logs</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-2">
                <label class="form-label small">From Date</label>
                <input type="date" class="form-control" id="dateFrom">
            </div>
            <div class="col-md-2">
                <label class="form-label small">To Date</label>
                <input type="date" class="form-control" id="dateTo">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Action Type</label>
                <select class="form-select" id="actionFilter">
                    <option value="">All Actions</option>
                    <option value="CREATE">CREATE</option>
                    <option value="UPDATE">UPDATE</option>
                    <option value="DELETE">DELETE</option>
                    <option value="VIEW">VIEW</option>
                    <option value="LOGIN">LOGIN</option>
                    <option value="LOGOUT">LOGOUT</option>
                    <option value="EXPORT">EXPORT</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Document Type</label>
                <select class="form-select" id="docTypeFilter">
                    <option value="">All Documents</option>
                    <option value="ITEM">Item</option>
                    <option value="TRANSACTION">Transaction</option>
                    <option value="CATEGORY">Category</option>
                    <option value="LOCATION">Location</option>
                    <option value="USER">User</option>
                    <option value="REPORT">Report</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">&nbsp;</label>
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="fas fa-sync me-1"></i> Reset
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="logsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date/Time</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Document</th>
                        <th>PC/Device</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#logsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('activity-logs.data') }}",
            data: function(d) {
                d.date_from = $('#dateFrom').val();
                d.date_to = $('#dateTo').val();
                d.action_type = $('#actionFilter').val();
                d.document_type = $('#docTypeFilter').val();
            }
        },
        columns: [
            { data: null, render: (d,t,r,m) => m.row + 1, orderable: false },
            { data: 'created_at', render: function(data) {
                return new Date(data).toLocaleString('en-GB', { timeZone: 'Africa/Addis_Ababa' });
            }},
            { data: 'user_name' },
            { data: 'user_role' },
            { data: 'action_badge' },
            { data: 'action_description' },
            { data: 'document_info' },
            { data: 'pc_info' },
            { data: 'ip_address' }
        ],
        order: [[1, 'desc']],
        pageLength: 25
    });

    $('#dateFrom, #dateTo, #actionFilter, #docTypeFilter').change(function() {
        table.ajax.reload();
    });
});

function resetFilters() {
    $('#dateFrom').val('');
    $('#dateTo').val('');
    $('#actionFilter').val('');
    $('#docTypeFilter').val('');
    $('#logsTable').DataTable().ajax.reload();
}
</script>
@endpush
@endsection
