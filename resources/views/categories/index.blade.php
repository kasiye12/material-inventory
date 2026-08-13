@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Categories Management')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Categories</h5>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="resetCategoryForm()">
                    <i class="fas fa-plus me-1"></i> Add Category
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="categoriesTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Code</th>
                        <th width="35%">Name</th>
                        <th width="25%">Description</th>
                        <th width="10%">Items</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="catModalTitle">
                    <i class="fas fa-tag me-2"></i>Add Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <input type="hidden" id="cat_id" name="id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="cat_name" name="name" 
                               placeholder="e.g., Cement" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="cat_code" name="code" 
                               placeholder="e.g., CEM" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="cat_description" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
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
    var table = $('#categoriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('categories.data') }}",
        columns: [
            { data: null, render: (d,t,r,m) => m.row + m.settings._iDisplayStart + 1, orderable: false },
            { data: 'code' },
            { data: 'name' },
            { data: 'description', defaultContent: '-' },
            { data: 'items_count' },
            { 
                data: null, orderable: false, searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-warning btn-sm" onclick="editCategory(${data.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteCategory(${data.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        pageLength: 25
    });

    $('#categoryForm').submit(function(e) {
        e.preventDefault();
        var id = $('#cat_id').val();
        var url = id ? '/categories/' + id : '/categories';
        var method = id ? 'PUT' : 'POST';
        
        $.ajax({
            url: url, type: method, data: $(this).serialize(),
            success: function(r) {
                $('#categoryModal').modal('hide');
                table.ajax.reload();
                Toast.fire({ icon: 'success', title: r.message || 'Saved!' });
            },
            error: function(xhr) {
                Toast.fire({ icon: 'error', title: xhr.responseJSON?.message || 'Error' });
            }
        });
    });
});

function resetCategoryForm() {
    $('#categoryForm')[0].reset();
    $('#cat_id').val('');
    $('#catModalTitle').html('<i class="fas fa-tag me-2"></i>Add Category');
}

function editCategory(id) {
    $.get('/categories/' + id, function(r) {
        $('#cat_id').val(r.id);
        $('#cat_name').val(r.name);
        $('#cat_code').val(r.code);
        $('#cat_description').val(r.description);
        $('#catModalTitle').html('<i class="fas fa-edit me-2"></i>Edit Category');
        $('#categoryModal').modal('show');
    });
}

function deleteCategory(id) {
    Swal.fire({
        title: 'Delete Category?',
        text: "This may affect related items!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Delete'
    }).then((r) => {
        if (r.isConfirmed) {
            $.ajax({ url: '/categories/' + id, type: 'DELETE',
                success: function() {
                    $('#categoriesTable').DataTable().ajax.reload();
                    Toast.fire({ icon: 'success', title: 'Deleted!' });
                }
            });
        }
    });
}
</script>
@endpush
@endsection
