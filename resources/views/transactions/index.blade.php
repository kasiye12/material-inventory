@extends('layouts.app')
@section('title', 'Transactions')
@section('page-title', 'Stock Transactions')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Transactions</h5>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionModal" onclick="resetForm()">
                    <i class="fas fa-plus me-1"></i> New Transaction
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-2">
                <label class="form-label small">From Date</label>
                <input type="date" class="form-control" id="dateFrom">
            </div>
            <div class="col-md-2">
                <label class="form-label small">To Date</label>
                <input type="date" class="form-control" id="dateTo">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Transaction Type</label>
                <select class="form-select" id="typeFilter">
                    <option value="">All Types</option>
                    @foreach($transactionTypes as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Location</label>
                <select class="form-select" id="locationFilter">
                    <option value="">All Locations</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->code }} - {{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">&nbsp;</label>
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="fas fa-sync me-1"></i> Reset
                </button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="transactionsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Trans No</th>
                        <th>Type</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Reference</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Transaction Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-plus-circle me-2"></i>New Transaction
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="transactionForm">
                <div class="modal-body">
                    <input type="hidden" id="trans_id" name="id">
                    
                    <!-- Row 1: Date and Type -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="trans_date" name="transaction_date" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Transaction Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="trans_type" name="transaction_type" onchange="updateFormFields()" required>
                                <option value="">-- Select Type --</option>
                                <option value="GRV">📥 GRV - Goods Received Voucher</option>
                                <option value="ISTRV">📥 ISTRV - Inter Store Transfer Received</option>
                                <option value="SIV">📤 SIV - Store Issue Voucher</option>
                                <option value="TRANSFER_OUT">📤 Transfer Out</option>
                                <option value="STORE_RETURN">🔄 Store Return</option>
                                <option value="BEGINNING_BALANCE">📊 Beginning Balance</option>
                            </select>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div id="typeInfo" class="alert alert-info mb-3" style="display:none;">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="typeInfoText"></span>
                    </div>

                    <!-- Item Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Item <span class="text-danger">*</span></label>
                        <select class="form-select" id="trans_item" name="item_id" style="width: 100%;" required>
                            <option value="">🔍 Search and select item...</option>
                        </select>
                    </div>

                    <!-- Location Fields -->
                    <div class="row">
                        <div class="col-md-6 mb-3" id="fromLocationDiv">
                            <label class="form-label fw-bold" id="fromLocationLabel">
                                From Location <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="trans_from" name="from_location_id">
                                <option value="">-- Select From Location --</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">📍 {{ $loc->code }} - {{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="toLocationDiv">
                            <label class="form-label fw-bold" id="toLocationLabel">
                                To Location <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="trans_to" name="to_location_id">
                                <option value="">-- Select To Location --</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">📍 {{ $loc->code }} - {{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="trans_qty" name="quantity" 
                               step="0.01" min="0.01" placeholder="Enter quantity" required>
                        <small class="text-muted" id="stockInfo"></small>
                    </div>

                    <!-- Reference Fields -->
                    <div class="row">
                        <div class="col-md-6 mb-3" id="refNumberDiv">
                            <label class="form-label" id="refNumberLabel">Reference Number</label>
                            <input type="text" class="form-control" id="trans_ref" name="reference_number" 
                                   placeholder="Enter reference number">
                        </div>
                        <div class="col-md-6 mb-3" id="docNumberDiv">
                            <label class="form-label" id="docNumberLabel">Document Number</label>
                            <input type="text" class="form-control" id="trans_doc" name="document_number" 
                                   placeholder="Enter document number">
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" id="trans_remarks" name="remarks" rows="2" 
                                  placeholder="Any additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-1"></i> Save Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for item search
    $('#trans_item').select2({
        dropdownParent: $('#transactionModal'),
        placeholder: '🔍 Search item by name or code...',
        allowClear: true,
        width: '100%',
        minimumInputLength: 1,
        ajax: {
            url: "{{ route('items.search') }}",
            dataType: 'json',
            delay: 300,
            data: function(params) { return { q: params.term }; },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return { 
                            id: item.id, 
                            text: item.code + ' - ' + item.name + ' [' + item.unit + ']',
                            unit: item.unit
                        };
                    })
                };
            }
        }
    });

    // When item selected, show unit
    $('#trans_item').on('select2:select', function(e) {
        var unit = e.params.data.unit;
        if (unit) {
            $('#stockInfo').text('Unit: ' + unit);
        }
    });

    // Transactions DataTable
    var table = $('#transactionsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('transactions.data') }}",
            data: function(d) {
                d.date_from = $('#dateFrom').val();
                d.date_to = $('#dateTo').val();
                d.transaction_type = $('#typeFilter').val();
                d.location_id = $('#locationFilter').val();
            }
        },
        columns: [
            { data: null, render: (d,t,r,m) => m.row + 1, orderable: false },
            { data: 'transaction_date' },
            { data: 'transaction_number' },
            { data: 'type_badge' },
            { data: 'item_name' },
            { data: 'quantity' },
            { data: 'item_unit' },
            { data: 'from_location' },
            { data: 'to_location' },
            { data: 'reference_number', defaultContent: '-' },
            { 
                data: null, orderable: false, searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-sm" onclick="viewTransaction(${data.id})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="editTransaction(${data.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteTransaction(${data.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[1, 'desc']],
        pageLength: 25
    });

    // Filter changes
    $('#dateFrom, #dateTo, #typeFilter, #locationFilter').change(function() {
        table.ajax.reload();
    });

    // Form submit
    $('#transactionForm').submit(function(e) {
        e.preventDefault();
        
        var id = $('#trans_id').val();
        var url = id ? '/transactions/' + id : '/transactions';
        var method = id ? 'PUT' : 'POST';
        var type = $('#trans_type').val();
        
        // Validate based on type
        if (!type) {
            Toast.fire({ icon: 'error', title: 'Please select transaction type' });
            return false;
        }
        
        if (!id) {
            // Only validate stock for new transactions
            if (['SIV', 'TRANSFER_OUT'].includes(type) && !$('#trans_from').val()) {
                Toast.fire({ icon: 'error', title: 'From Location is required for this type' });
                return false;
            }
            if (['GRV', 'ISTRV', 'STORE_RETURN', 'BEGINNING_BALANCE'].includes(type) && !$('#trans_to').val()) {
                Toast.fire({ icon: 'error', title: 'To Location is required for this type' });
                return false;
            }
        }
        
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(response) {
                Swal.close();
                $('#transactionModal').modal('hide');
                table.ajax.reload();
                Toast.fire({ icon: 'success', title: response.message });
                resetForm();
            },
            error: function(xhr) {
                Swal.close();
                Toast.fire({ icon: 'error', title: xhr.responseJSON?.message || 'Error saving transaction' });
            }
        });
    });
});

// Update form fields based on transaction type
function updateFormFields() {
    var type = $('#trans_type').val();
    var infoText = '';
    
    // Reset
    $('#fromLocationDiv').show();
    $('#toLocationDiv').show();
    $('#trans_from').prop('required', false);
    $('#trans_to').prop('required', false);
    $('#refNumberDiv').show();
    $('#docNumberDiv').show();
    
    switch(type) {
        case 'GRV':
            infoText = 'Goods Received Voucher - Items received from supplier';
            $('#fromLocationDiv').hide();
            $('#toLocationLabel').html('Receive To (Store/Location) <span class="text-danger">*</span>');
            $('#trans_to').prop('required', true);
            $('#refNumberLabel').text('GRV Pad Ref. No.');
            $('#docNumberLabel').text('Supplier Invoice No.');
            break;
            
        case 'ISTRV':
            infoText = 'Inter Store Transfer Received - Items received from another store';
            $('#fromLocationLabel').html('Received From (Source Store)');
            $('#toLocationLabel').html('Receive To (Destination) <span class="text-danger">*</span>');
            $('#trans_to').prop('required', true);
            $('#refNumberLabel').text('ISTRV No.');
            $('#docNumberLabel').text('Transfer Document No.');
            break;
            
        case 'SIV':
            infoText = 'Store Issue Voucher - Items issued to site/project';
            $('#fromLocationLabel').html('Issue From (Store) <span class="text-danger">*</span>');
            $('#trans_from').prop('required', true);
            $('#toLocationDiv').hide();
            $('#refNumberLabel').text('SIV Pad Ref. No.');
            $('#docNumberDiv').hide();
            break;
            
        case 'TRANSFER_OUT':
            infoText = 'Transfer Out - Items transferred to another location';
            $('#fromLocationLabel').html('Transfer From <span class="text-danger">*</span>');
            $('#trans_from').prop('required', true);
            $('#toLocationLabel').html('Transfer To <span class="text-danger">*</span>');
            $('#trans_to').prop('required', true);
            $('#refNumberLabel').text('Out/SIV NO');
            $('#docNumberLabel').text('Transfer Order No.');
            break;
            
        case 'STORE_RETURN':
            infoText = 'Store Return - Items returned to store';
            $('#fromLocationLabel').html('Return From (Site/Project)');
            $('#toLocationLabel').html('Return To (Store) <span class="text-danger">*</span>');
            $('#trans_to').prop('required', true);
            $('#refNumberLabel').text('Return Voucher No.');
            $('#docNumberLabel').text('Original SIV No.');
            break;
            
        case 'BEGINNING_BALANCE':
            infoText = 'Beginning Balance - Opening stock for new period';
            $('#fromLocationDiv').hide();
            $('#toLocationLabel').html('Stock Location <span class="text-danger">*</span>');
            $('#trans_to').prop('required', true);
            $('#refNumberLabel').text('Opening Ref No.');
            $('#docNumberDiv').hide();
            break;
            
        default:
            $('#typeInfo').hide();
            return;
    }
    
    $('#typeInfoText').text(infoText);
    $('#typeInfo').show();
}

// Reset form
function resetForm() {
    $('#transactionForm')[0].reset();
    $('#trans_id').val('');
    $('#trans_date').val('{{ date("Y-m-d") }}');
    $('#trans_item').val(null).trigger('change');
    $('#trans_type').val('');
    $('#typeInfo').hide();
    $('#fromLocationDiv').show();
    $('#toLocationDiv').show();
    $('#modalTitle').html('<i class="fas fa-plus-circle me-2"></i>New Transaction');
    $('#submitBtn').html('<i class="fas fa-save me-1"></i> Save Transaction');
}

// View Transaction
function viewTransaction(id) {
    $.get('/transactions/' + id, function(data) {
        Swal.fire({
            title: 'Transaction Details',
            html: `
                <table class="table table-bordered text-start">
                    <tr><th>Transaction No</th><td>${data.transaction_number}</td></tr>
                    <tr><th>Date</th><td>${data.transaction_date}</td></tr>
                    <tr><th>Type</th><td>${data.transaction_type}</td></tr>
                    <tr><th>Item</th><td>${data.item?.name || 'N/A'}</td></tr>
                    <tr><th>Quantity</th><td>${data.quantity} ${data.item?.unit || ''}</td></tr>
                    <tr><th>From</th><td>${data.from_location?.name || '-'}</td></tr>
                    <tr><th>To</th><td>${data.to_location?.name || '-'}</td></tr>
                    <tr><th>Reference</th><td>${data.reference_number || '-'}</td></tr>
                    <tr><th>Document</th><td>${data.document_number || '-'}</td></tr>
                    <tr><th>Remarks</th><td>${data.remarks || '-'}</td></tr>
                </table>
            `,
            icon: 'info',
            confirmButtonText: 'Close'
        });
    });
}

// Edit Transaction
function editTransaction(id) {
    $.get('/transactions/' + id, function(data) {
        $('#trans_id').val(data.id);
        $('#trans_date').val(data.transaction_date);
        $('#trans_type').val(data.transaction_type);
        $('#trans_qty').val(data.quantity);
        $('#trans_ref').val(data.reference_number);
        $('#trans_doc').val(data.document_number);
        $('#trans_remarks').val(data.remarks);
        $('#trans_from').val(data.from_location_id);
        $('#trans_to').val(data.to_location_id);
        
        // Set item
        if (data.item) {
            var option = new Option(data.item.code + ' - ' + data.item.name, data.item_id, true, true);
            $('#trans_item').append(option).trigger('change');
        }
        
        updateFormFields();
        $('#modalTitle').html('<i class="fas fa-edit me-2"></i>Edit Transaction');
        $('#submitBtn').html('<i class="fas fa-save me-1"></i> Update');
        $('#transactionModal').modal('show');
    });
}

// Delete Transaction
function deleteTransaction(id) {
    Swal.fire({
        title: 'Delete Transaction?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/transactions/' + id,
                type: 'DELETE',
                success: function(response) {
                    $('#transactionsTable').DataTable().ajax.reload();
                    Toast.fire({ icon: 'success', title: response.message });
                }
            });
        }
    });
}

// Reset filters
function resetFilters() {
    $('#dateFrom').val('');
    $('#dateTo').val('');
    $('#typeFilter').val('');
    $('#locationFilter').val('');
    $('#transactionsTable').DataTable().ajax.reload();
}
</script>
@endpush

<style>
.select2-container--default .select2-selection--single {
    height: 42px !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
}
.select2-dropdown { z-index: 1060 !important; }
#typeInfo { transition: all 0.3s ease; }
</style>
@endsection
