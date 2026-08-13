@extends('layouts.app')
@section('title', 'Help')
@section('page-title', 'System Help & Guide')

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Transaction Types</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr><th>Type</th><th>Description</th><th>Location Required</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><strong>GRV</strong></td><td>Goods Received from supplier</td><td>To Location</td></tr>
                        <tr><td><strong>ISTRV</strong></td><td>Inter Store Transfer Received</td><td>To Location</td></tr>
                        <tr><td><strong>SIV</strong></td><td>Store Issue to site</td><td>From Location</td></tr>
                        <tr><td><strong>TRANSFER_OUT</strong></td><td>Transfer to another location</td><td>Both</td></tr>
                        <tr><td><strong>STORE_RETURN</strong></td><td>Items returned to store</td><td>To Location</td></tr>
                        <tr><td><strong>BEGINNING_BALANCE</strong></td><td>Opening stock</td><td>To Location</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Reports Guide</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr><th>Report</th><th>Purpose</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><strong>Delivery Report</strong></td><td>Daily material deliveries</td></tr>
                        <tr><td><strong>Stock Ledger</strong></td><td>Complete material ledger</td></tr>
                        <tr><td><strong>Stock Balance</strong></td><td>Current stock levels</td></tr>
                        <tr><td><strong>Weekly Transfer</strong></td><td>Project to project transfers</td></tr>
                        <tr><td><strong>Weekly Stock Status</strong></td><td>HO/Main Store to projects</td></tr>
                        <tr><td><strong>Monthly Report</strong></td><td>Monthly summary</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Quick Tips</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded">
                    <h6><i class="fas fa-tag text-warning me-2"></i>Update Item Price</h6>
                    <p class="small mb-0">Go to Items → Click yellow Price button → Enter new price → Update</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded">
                    <h6><i class="fas fa-search text-primary me-2"></i>Search Projects</h6>
                    <p class="small mb-0">Use search in dropdown to find projects by code or name</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded">
                    <h6><i class="fas fa-print text-success me-2"></i>Print Reports</h6>
                    <p class="small mb-0">Generate report first, then click Print for clean output with logo</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
