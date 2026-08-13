@extends('layouts.app')
@section('title', 'Items')
@section('page-title', 'Items Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Items List</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="resetForm()">
            <i class="fas fa-plus me-1"></i> Add New Item
        </button>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Current Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Item Modal -->
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Add Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="itemForm">
                <div class="modal-body">
                    <input type="hidden" id="item_id" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Item Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Item Code *</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Category *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Unit *</label>
                            <select class="form-select" id="unit" name="unit" required>
                                <option value="">Select Unit</option>
                                <option>Pcs</option><option>Bag</option><option>Kg</option>
                                <option>Qtl</option><option>m3</option><option>Mtr</option>
                                <option>Ltr</option><option>Set</option><option>Berga</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Unit Price (ETB)</label>
                            <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Min Stock</label>
                            <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Max Stock</label>
                            <input type="number" class="form-control" id="max_stock_level" name="max_stock_level" step="0.01" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Price Update Modal -->
<div class="modal fade" id="priceModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title">
                    <i class="fas fa-tag me-2"></i>Update Price
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="priceForm">
                <div class="modal-body">
                    <input type="hidden" id="price_item_id" name="id">
                    <div class="text-center mb-3">
                        <h6 id="price_item_name" class="mb-0"></h6>
                        <small class="text-muted" id="price_item_code"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Price</label>
                        <input type="text" class="form-control" id="current_price" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Price (ETB) *</label>
                        <input type="number" class="form-control form-control-lg" id="new_price" 
                               step="0.01" min="0.01" required placeholder="Enter new price">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-save me-1"></i> Update Price
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#itemsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('items.data') }}",
            data: function(d) { d.category_id = $('#categoryFilter').val(); }
        },
        columns: [
            { data: null, render: (d,t,r,m) => m.row + 1, orderable: false },
            { data: 'code' },
            { data: 'name' },
            { data: 'category_name' },
            { data: 'unit' },
            { 
                data: 'unit_price',
                render: function(data) {
                    if (data && data > 0) {
                        return '<span class="fw-bold">ETB ' + parseFloat(data).toFixed(2) + '</span>';
                    }
                    return '<span class="text-muted">Not set</span>';
                }
            },
            { data: 'current_stock' },
            { data: 'status' },
            { 
                data: null, orderable: false, searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-warning btn-sm" onclick="showPriceModal(${data.id})" title="Update Price">
                                <i class="fas fa-tag"></i> Price
                            </button>
                            <button class="btn btn-info btn-sm" onclick="editItem(${data.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteItem(${data.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[1, 'asc']],
        pageLength: 25
    });

    $('#categoryFilter').change(function() { table.ajax.reload(); });

    // Item form submit
    $('#itemForm').submit(function(e) {
        e.preventDefault();
        var id = $('#item_id').val();
        var url = id ? '/items/' + id : '/items';
        var method = id ? 'PUT' : 'POST';
        
        $.ajax({
            url: url, type: method, data: $(this).serialize(),
            success: function(r) {
                $('#itemModal').modal('hide');
                table.ajax.reload();
                Toast.fire({ icon: 'success', title: r.message });
            },
            error: function(xhr) {
                Toast.fire({ icon: 'error', title: xhr.responseJSON?.message || 'Error' });
            }
        });
    });

    // Price form submit
    $('#priceForm').submit(function(e) {
        e.preventDefault();
        var id = $('#price_item_id').val();
        var newPrice = $('#new_price').val();
        
        $.ajax({
            url: '/items/' + id + '/update-price',
            type: 'POST',
            data: { unit_price: newPrice },
            success: function(r) {
                $('#priceModal').modal('hide');
                table.ajax.reload();
                Toast.fire({ icon: 'success', title: 'Price updated successfully!' });
            },
            error: function(xhr) {
                Toast.fire({ icon: 'error', title: 'Failed to update price' });
            }
        });
    });
});

// Show price update modal
function showPriceModal(id) {
    $.get('/items/' + id, function(response) {
        var item = response.item;
        $('#price_item_id').val(item.id);
        $('#price_item_name').text(item.name);
        $('#price_item_code').text(item.code);
        $('#current_price').val(item.unit_price ? 'ETB ' + parseFloat(item.unit_price).toFixed(2) : 'Not set');
        $('#new_price').val(item.unit_price || '');
        $('#priceModal').modal('show');
    });
}

function resetForm() {
    $('#itemForm')[0].reset();
    $('#item_id').val('');
    $('#modalTitle').text('Add Item');
}

function editItem(id) {
    $.get('/items/' + id, function(response) {
        var item = response.item;
        $('#item_id').val(item.id);
        $('#name').val(item.name);
        $('#code').val(item.code);
        $('#category_id').val(item.category_id);
        $('#unit').val(item.unit);
        $('#unit_price').val(item.unit_price);
        $('#min_stock_level').val(item.min_stock_level);
        $('#max_stock_level').val(item.max_stock_level);
        $('#modalTitle').text('Edit Item');
        $('#itemModal').modal('show');
    });
}

function deleteItem(id) {
    Swal.fire({
        title: 'Delete?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33'
    }).then((r) => {
        if (r.isConfirmed) {
            $.ajax({ url: '/items/' + id, type: 'DELETE',
                success: function() { $('#itemsTable').DataTable().ajax.reload(); }
            });
        }
    });
}
</script>
@endpush
@endsection
