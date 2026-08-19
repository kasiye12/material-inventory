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
                <select class="form-select select2-search" id="locationFilter" style="width: 100%;">
                    <option value="">🔍 Search location...</option>
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

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="transactionsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Date</th><th>Trans No</th><th>Type</th>
                        <th>Item</th><th>Qty</th><th>Unit</th><th>From</th><th>To</th>
                        <th>Out No</th><th>In No</th><th>Actions</th>
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
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date *</label>
                            <input type="date" class="form-control" id="trans_date" name="transaction_date" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Transaction Type *</label>
                            <select class="form-select" id="trans_type" name="transaction_type" onchange="updateFormFields()" required>
                                <option value="">-- Select Type --</option>
                                <optgroup label="📥 Dual Vouchers (Out + In)">
                                    <option value="ISTRV">ISTRV - Inter Store Transfer (ISTV + ISTRV)</option>
                                    <option value="FARV">FARV - Fixed Asset Receiving (ISFATV + ISFATRV)</option>
                                    <option value="UMTRV">UMTRV - Used Material Transfer (UMTR + UMTRV)</option>
                                </optgroup>
                                <optgroup label="📥 Single Receiving Vouchers (IN only)">
                                    <option value="GRV">GRV - Goods Received Voucher</option>
                                    <option value="SRV">SRV - Store Return Voucher</option>
                                    <option value="TTRV">TTRV - Temporary Transfer Receiving</option>
                                    <option value="FGRV">FGRV - Finished Good Receiving</option>
                                    <option value="FRV">FRV - Fuel Receiving Voucher</option>
                                    <option value="BEGINNING_BALANCE">BEGINNING_BALANCE - Opening Stock</option>
                                </optgroup>
                                <optgroup label="📤 Issue Vouchers (OUT only)">
                                    <option value="SIV">SIV - Store Issue Voucher</option>
                                    <option value="TRANSFER_OUT">TRANSFER_OUT - Transfer Out</option>
                                    <option value="FIV">FIV - Fuel Issue Voucher</option>
                                    <option value="UMIV">UMIV - Used Material Issue Voucher</option>
                                    <option value="UMTV">UMTV - Used Material Transfer Voucher</option>
                                </optgroup>
                                <optgroup label="🔄 Return">
                                    <option value="STORE_RETURN">STORE_RETURN - Store Return</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div id="typeInfo" class="alert alert-info mb-3" style="display:none;">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="typeInfoText"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Item *</label>
                        <select class="form-select select2-item" id="trans_item" name="item_id" style="width: 100%;">
                            <option value="">🔍 Search item...</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3" id="fromLocationDiv">
                            <label class="form-label fw-bold" id="fromLocationLabel">From Location *</label>
                            <select class="form-select select2-location" id="trans_from" name="from_location_id" style="width: 100%;">
                                <option value="">🔍 Search location...</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->code }} - {{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="toLocationDiv">
                            <label class="form-label fw-bold" id="toLocationLabel">To Location *</label>
                            <select class="form-select select2-location" id="trans_to" name="to_location_id" style="width: 100%;">
                                <option value="">🔍 Search location...</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->code }} - {{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Quantity *</label>
                        <input type="number" class="form-control" id="trans_qty" name="quantity" 
                               step="0.01" min="0.01" placeholder="Enter quantity" required>
                    </div>

                    <!-- DUAL VOUCHER FIELDS for ISTRV, FARV, UMTRV -->
                    <div id="dualVoucherFields" style="display:none;">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            Enter both Transfer Out and Receiving voucher numbers
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-danger" id="outVoucherLabel">
                                    <i class="fas fa-arrow-up me-1"></i> Transfer Out No
                                </label>
                                <input type="text" class="form-control" id="voucher_out" name="voucher_out" 
                                       placeholder="Enter transfer out voucher number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success" id="inVoucherLabel">
                                    <i class="fas fa-arrow-down me-1"></i> Receiving No
                                </label>
                                <input type="text" class="form-control" id="voucher_in" name="voucher_in" 
                                       placeholder="Enter receiving voucher number">
                            </div>
                        </div>
                    </div>

                    <!-- STANDARD FIELDS for other types -->
                    <div class="row" id="standardRefFields">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" id="refNumberLabel">Reference Number</label>
                            <input type="text" class="form-control" id="trans_ref" name="reference_number" 
                                   placeholder="Enter reference number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" id="docNumberLabel">Document Number</label>
                            <input type="text" class="form-control" id="trans_doc" name="document_number" 
                                   placeholder="Enter document number">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks (Plate No / Supplier)</label>
                        <textarea class="form-control" id="trans_remarks" name="remarks" rows="2" 
                                  placeholder="Plate No, Supplier name, or other notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
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
                        return { id: item.id, text: item.code + ' - ' + item.name + ' [' + item.unit + ']' };
                    })
                };
            }
        }
    });

    $('.select2-location').select2({
        dropdownParent: $('#transactionModal'),
        placeholder: '🔍 Search location...',
        allowClear: true,
        width: '100%',
    });

    $('.select2-search').select2({
        placeholder: '🔍 Search location...',
        allowClear: true,
        width: '100%',
    });

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
            { data: 'voucher_out' },
            { data: 'voucher_in' },
            { 
                data: null, orderable: false, searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-sm" onclick="viewTransaction(${data.id})"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-warning btn-sm" onclick="editTransaction(${data.id})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm" onclick="deleteTransaction(${data.id})"><i class="fas fa-trash"></i></button>
                        </div>
                    `;
                }
            }
        ],
        order: [[1, 'desc']],
        pageLength: 25
    });

    $('#dateFrom, #dateTo, #typeFilter').change(function() { table.ajax.reload(); });
    $('#locationFilter').on('change', function() { table.ajax.reload(); });

    $('#transactionForm').submit(function(e) {
        e.preventDefault();
        var id = $('#trans_id').val();
        var url = id ? '/transactions/' + id : '/transactions';
        var method = id ? 'PUT' : 'POST';
        var type = $('#trans_type').val();
        
        // For dual voucher types, map fields
        if (['ISTRV', 'FARV', 'UMTRV'].includes(type)) {
            var outNo = $('#voucher_out').val() || '';
            var inNo = $('#voucher_in').val() || '';
            
            $('input[name="reference_number"]').val(outNo);
            $('input[name="document_number"]').val(inNo);
        }
        
        $.ajax({
            url: url, type: method, data: $(this).serialize(),
            success: function(r) {
                $('#transactionModal').modal('hide');
                table.ajax.reload();
                Toast.fire({ icon: 'success', title: r.message });
                resetForm();
            },
            error: function(xhr) {
                Toast.fire({ icon: 'error', title: xhr.responseJSON?.message || 'Error' });
            }
        });
    });
});

function updateFormFields() {
    var type = $('#trans_type').val();
    var infoText = '';
    
    $('#fromLocationDiv').show();
    $('#toLocationDiv').show();
    $('#trans_from').prop('required', false);
    $('#trans_to').prop('required', false);
    $('#dualVoucherFields').hide();
    $('#standardRefFields').show();
    
    var inTypes = ['GRV', 'SRV', 'TTRV', 'FGRV', 'FRV', 'BEGINNING_BALANCE', 'STORE_RETURN'];
    var outTypes = ['SIV', 'TRANSFER_OUT', 'FIV', 'UMIV', 'UMTV'];
    var dualTypes = ['ISTRV', 'FARV', 'UMTRV'];
    
    if (dualTypes.includes(type)) {
        // Dual voucher types - show both Out and In fields
        $('#dualVoucherFields').show();
        $('#standardRefFields').hide();
        $('#fromLocationLabel').html('Transfer From (Source)');
        $('#toLocationLabel').html('Receive To <span class="text-danger">*</span>');
        $('#trans_to').prop('required', true);
        
        // Update labels based on type
        if (type === 'ISTRV') {
            infoText = 'Inter Store Transfer - Enter both ISTV NO (Out) and ISTRV NO (Receiving)';
            $('#outVoucherLabel').html('<i class="fas fa-arrow-up me-1"></i> ISTV NO (Transfer Out)');
            $('#inVoucherLabel').html('<i class="fas fa-arrow-down me-1"></i> ISTRV NO (Receiving) *');
        } else if (type === 'FARV') {
            infoText = 'Fixed Asset Receiving - Enter both ISFATV NO (Out) and ISFATRV NO (Receiving)';
            $('#outVoucherLabel').html('<i class="fas fa-arrow-up me-1"></i> ISFATV NO (Transfer Out)');
            $('#inVoucherLabel').html('<i class="fas fa-arrow-down me-1"></i> ISFATRV NO (Receiving) *');
        } else if (type === 'UMTRV') {
            infoText = 'Used Material Transfer - Enter both UMTR NO (Out) and UMTRV NO (Receiving)';
            $('#outVoucherLabel').html('<i class="fas fa-arrow-up me-1"></i> UMTR NO (Transfer Out)');
            $('#inVoucherLabel').html('<i class="fas fa-arrow-down me-1"></i> UMTRV NO (Receiving) *');
        }
    } else if (inTypes.includes(type)) {
        infoText = 'Receiving transaction - items added to stock';
        $('#fromLocationDiv').hide();
        $('#toLocationLabel').html('Receive To <span class="text-danger">*</span>');
        $('#trans_to').prop('required', true);
    } else if (outTypes.includes(type)) {
        infoText = 'Issue transaction - items removed from stock';
        $('#toLocationDiv').hide();
        $('#fromLocationLabel').html('Issue From <span class="text-danger">*</span>');
        $('#trans_from').prop('required', true);
    } else {
        $('#typeInfo').hide();
        return;
    }
    
    $('#typeInfoText').text(infoText);
    $('#typeInfo').show();
}

function resetForm() {
    $('#transactionForm')[0].reset();
    $('#trans_id').val('');
    $('#trans_item').val(null).trigger('change');
    $('#trans_from').val(null).trigger('change');
    $('#trans_to').val(null).trigger('change');
    $('#trans_type').val('');
    $('#typeInfo').hide();
    $('#dualVoucherFields').hide();
    $('#standardRefFields').show();
    $('#modalTitle').html('<i class="fas fa-plus-circle me-2"></i>New Transaction');
}

function viewTransaction(id) {
    $.get('/transactions/' + id, function(data) {
        Swal.fire({
            title: 'Transaction Details',
            html: `
                <table class="table table-bordered text-start">
                    <tr><th>Transaction No</th><td>${data.transaction_number}</td></tr>
                    <tr><th>Date</th><td>${data.transaction_date}</td></tr>
                    <tr><th>Type</th><td>${data.transaction_type}</td></tr>
                    <tr><th>Item</th><td>${data.item?.name}</td></tr>
                    <tr><th>Quantity</th><td>${data.quantity} ${data.item?.unit}</td></tr>
                    <tr><th>From</th><td>${data.from_location?.name || '-'}</td></tr>
                    <tr><th>To</th><td>${data.to_location?.name || '-'}</td></tr>
                    <tr><th>Out Voucher No</th><td>${data.reference_number || '-'}</td></tr>
                    <tr><th>In Voucher No</th><td>${data.document_number || '-'}</td></tr>
                    <tr><th>Remark</th><td>${data.remarks || '-'}</td></tr>
                </table>
            `,
            icon: 'info'
        });
    });
}

function editTransaction(id) {
    $.get('/transactions/' + id, function(data) {
        $('#trans_id').val(data.id);
        $('#trans_date').val(data.transaction_date);
        $('#trans_type').val(data.transaction_type);
        $('#trans_qty').val(data.quantity);
        $('#trans_ref').val(data.reference_number);
        $('#trans_doc').val(data.document_number);
        $('#trans_remarks').val(data.remarks);
        
        if (data.item) {
            var option = new Option(data.item.code + ' - ' + data.item.name, data.item_id, true, true);
            $('#trans_item').append(option).trigger('change');
        }
        if (data.from_location) {
            $('#trans_from').val(data.from_location_id).trigger('change');
        }
        if (data.to_location) {
            $('#trans_to').val(data.to_location_id).trigger('change');
        }
        
        // Populate dual voucher fields
        if (['ISTRV', 'FARV', 'UMTRV'].includes(data.transaction_type)) {
            $('#voucher_out').val(data.reference_number);
            $('#voucher_in').val(data.document_number);
        }
        
        updateFormFields();
        $('#modalTitle').html('<i class="fas fa-edit me-2"></i>Edit Transaction');
        $('#transactionModal').modal('show');
    });
}

function deleteTransaction(id) {
    Swal.fire({
        title: 'Delete?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33'
    }).then((r) => {
        if (r.isConfirmed) {
            $.ajax({ url: '/transactions/' + id, type: 'DELETE',
                success: function() { $('#transactionsTable').DataTable().ajax.reload(); }
            });
        }
    });
}

function resetFilters() {
    $('#dateFrom').val('');
    $('#dateTo').val('');
    $('#typeFilter').val('');
    $('#locationFilter').val(null).trigger('change');
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
optgroup { font-weight: bold; }
</style>
@endsection
