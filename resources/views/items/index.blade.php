@extends('layouts.app')
@section('title', 'Items')
@section('page-title', 'Items Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Items List</h5>
        <div>
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-excel me-1"></i> Import Excel
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="resetForm()">
                <i class="fas fa-plus me-1"></i> Add New Item
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="typeFilter">
                    <option value="">All Types</option>
                    <option value="regular">📦 Regular Material</option>
                    <option value="fixed_asset">🏗️ Fixed Asset</option>
                    <option value="used_material">♻️ Used Material</option>
                    <option value="fuel">⛽ Fuel</option>
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
                        <th>Type</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Stock</th>
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
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Category *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Item Type *</label>
                            <select class="form-select" id="item_type" name="item_type" required>
                                <option value="">Select Type</option>
                                <option value="regular">📦 Regular Material</option>
                                <option value="fixed_asset">🏗️ Fixed Asset</option>
                                <option value="used_material">♻️ Used Material</option>
                                <option value="fuel">⛽ Fuel</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Unit *</label>
                            <select class="form-select" id="unit" name="unit" required>
                                <option value="">Select Unit</option>
                                <option>Pcs</option><option>Bag</option><option>Kg</option>
                                <option>Qtl</option><option>m3</option><option>Mtr</option>
                                <option>Ltr</option><option>Set</option><option>Berga</option>
                                <option>Trip</option><option>Roll</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Unit Price (ETB)</label>
                            <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Min Stock</label>
                            <input type="number" class="form-control" id="min_stock_level" name="min_stock_level" step="0.01" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Stock</label>
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

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-file-excel me-2"></i>Import Items from Excel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>📋 Excel Format Required:</strong>
                        <br><br>
                        <strong>Required Columns:</strong>
                        <br>
                        <code>code</code> - Item code (e.g., CEM-001)
                        <br>
                        <code>name</code> - Item name (e.g., PPC Cement 50kg)
                        <br>
                        <code>unit</code> - Unit (Pcs, Bag, Kg, Ltr, etc.)
                        <br><br>
                        <strong>Optional Columns:</strong>
                        <br>
                        <code>category</code> - Category name (auto-created if not exist)
                        <br>
                        <code>item_type</code> - regular, fixed_asset, used_material, fuel
                        <br>
                        <code>unit_price</code> - Price in ETB
                        <br>
                        <code>min_stock</code> - Minimum stock level
                        <br>
                        <code>max_stock</code> - Maximum stock level
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Excel File (.xlsx, .xls, .csv)</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="downloadTemplate()">
                            <i class="fas fa-download me-1"></i> Download CSV Template
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-1"></i> Import Items
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
            data: function(d) {
                d.category_id = $('#categoryFilter').val();
                d.item_type = $('#typeFilter').val();
            }
        },
        columns: [
            { data: null, render: (d,t,r,m) => m.row + 1, orderable: false },
            { data: 'code' },
            { data: 'name' },
            { data: 'category_name' },
            { 
                data: 'item_type',
                render: function(data) {
                    var labels = {
                        'regular': '<span class="badge bg-success">📦 Regular</span>',
                        'fixed_asset': '<span class="badge bg-primary">🏗️ Fixed Asset</span>',
                        'used_material': '<span class="badge bg-warning">♻️ Used</span>',
                        'fuel': '<span class="badge bg-info">⛽ Fuel</span>',
                    };
                    return labels[data] || data;
                }
            },
            { data: 'unit' },
            { 
                data: 'unit_price',
                render: function(data) {
                    return data ? 'ETB ' + parseFloat(data).toFixed(2) : '-';
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
                                <i class="fas fa-tag"></i>
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

    $('#categoryFilter, #typeFilter').change(function() { table.ajax.reload(); });

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

    // Import form
    $('#importForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        Swal.fire({
            title: 'Importing items...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        $.ajax({
            url: "{{ route('items.import') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(r) {
                Swal.close();
                $('#importModal').modal('hide');
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Import Complete!',
                    text: r.message
                });
            },
            error: function(xhr) {
                Swal.close();
                var msg = xhr.responseJSON?.message || 'Import failed';
                Swal.fire({
                    icon: 'error',
                    title: 'Import Error',
                    text: msg
                });
            }
        });
    });
});

function downloadTemplate() {
    var csv = 'code,name,category,unit,item_type,unit_price,min_stock,max_stock\n';
    csv += 'CEM-001,PPC Cement 50kg,Cement,Bag,regular,850,50,500\n';
    csv += 'RBR-001,Rebar Diameter 8mm,Re-Bar,Qtl,regular,5200,30,300\n';
    csv += 'FUEL-001,Gas Oil,Fuel & Oil,Ltr,fuel,80,100,1000\n';
    csv += 'FA-001,Total Station,Equipment,Set,fixed_asset,450000,1,5\n';
    csv += 'UM-001,Ega Sheet,Steel,Pcs,used_material,350,10,100\n';
    
    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'items_import_template.csv';
    link.click();
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
        $('#item_type').val(item.item_type);
        $('#unit').val(item.unit);
        $('#unit_price').val(item.unit_price);
        $('#min_stock_level').val(item.min_stock_level);
        $('#max_stock_level').val(item.max_stock_level);
        $('#modalTitle').text('Edit Item');
        $('#itemModal').modal('show');
    });
}

function showPriceModal(id) {
    $.get('/items/' + id, function(response) {
        Swal.fire({
            title: 'Update Price - ' + response.item.name,
            html: `
                <p>Current: ETB ${response.item.unit_price || 0}</p>
                <input type="number" id="newPrice" class="form-control" value="${response.item.unit_price || ''}" step="0.01" placeholder="New price">
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            preConfirm: () => {
                return { price: $('#newPrice').val() };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/items/' + id + '/update-price',
                    type: 'POST',
                    data: { unit_price: result.value.price },
                    success: function() {
                        $('#itemsTable').DataTable().ajax.reload();
                        Toast.fire({ icon: 'success', title: 'Price updated!' });
                    }
                });
            }
        });
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
