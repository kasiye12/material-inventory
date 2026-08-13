@extends('layouts.app')
@section('title', 'Locations')
@section('page-title', 'Locations Management')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Locations</h5>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#locationModal" onclick="resetLocationForm()">
                    <i class="fas fa-plus me-1"></i> Add Location
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="locationsTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Code</th>
                        <th width="25%">Name</th>
                        <th width="15%">Type</th>
                        <th width="15%">Contact</th>
                        <th width="15%">Phone</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="locModalTitle">
                    <i class="fas fa-map-marker-alt me-2"></i>Add Location
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="locationForm">
                <div class="modal-body">
                    <input type="hidden" id="loc_id" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="loc_name" name="name" 
                                   placeholder="e.g., Nefas Silk" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="loc_code" name="code" 
                                   placeholder="e.g., NS" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" id="loc_type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="head_office">🏢 Head Office</option>
                            <option value="project">🏗️ Project</option>
                            <option value="site">📍 Site</option>
                            <option value="store">🏪 Store</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" id="loc_address" name="address" 
                               placeholder="Full address">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="loc_contact_person" name="contact_person" 
                                   placeholder="Contact name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="loc_contact_phone" name="contact_phone" 
                                   placeholder="+251...">
                        </div>
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
    var table = $('#locationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('locations.data') }}",
        columns: [
            { data: null, render: (d,t,r,m) => m.row + m.settings._iDisplayStart + 1, orderable: false },
            { data: 'code' },
            { data: 'name' },
            { data: 'type' },
            { data: 'contact_person', defaultContent: '-' },
            { data: 'contact_phone', defaultContent: '-' },
            { 
                data: null, orderable: false, searchable: false,
                render: function(data) {
                    return `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-sm" onclick="viewLocation(${data.id})" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="editLocation(${data.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteLocation(${data.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        pageLength: 25
    });

    $('#locationForm').submit(function(e) {
        e.preventDefault();
        var id = $('#loc_id').val();
        var url = id ? '/locations/' + id : '/locations';
        var method = id ? 'PUT' : 'POST';
        
        $.ajax({
            url: url, type: method, data: $(this).serialize(),
            success: function(r) {
                $('#locationModal').modal('hide');
                table.ajax.reload();
                Toast.fire({ icon: 'success', title: r.message || 'Saved!' });
            }
        });
    });
});

function resetLocationForm() {
    $('#locationForm')[0].reset();
    $('#loc_id').val('');
    $('#locModalTitle').html('<i class="fas fa-map-marker-alt me-2"></i>Add Location');
}

function viewLocation(id) {
    $.get('/locations/' + id, function(r) {
        Swal.fire({
            title: r.name,
            html: `
                <table class="table table-bordered text-start">
                    <tr><th>Code</th><td>${r.code}</td></tr>
                    <tr><th>Type</th><td>${r.type}</td></tr>
                    <tr><th>Address</th><td>${r.address || 'N/A'}</td></tr>
                    <tr><th>Contact</th><td>${r.contact_person || 'N/A'}</td></tr>
                    <tr><th>Phone</th><td>${r.contact_phone || 'N/A'}</td></tr>
                </table>
            `,
            icon: 'info'
        });
    });
}

function editLocation(id) {
    $.get('/locations/' + id, function(r) {
        $('#loc_id').val(r.id);
        $('#loc_name').val(r.name);
        $('#loc_code').val(r.code);
        $('#loc_type').val(r.type);
        $('#loc_address').val(r.address);
        $('#loc_contact_person').val(r.contact_person);
        $('#loc_contact_phone').val(r.contact_phone);
        $('#locModalTitle').html('<i class="fas fa-edit me-2"></i>Edit Location');
        $('#locationModal').modal('show');
    });
}

function deleteLocation(id) {
    Swal.fire({
        title: 'Delete Location?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Delete'
    }).then((r) => {
        if (r.isConfirmed) {
            $.ajax({ url: '/locations/' + id, type: 'DELETE',
                success: function() {
                    $('#locationsTable').DataTable().ajax.reload();
                    Toast.fire({ icon: 'success', title: 'Deleted!' });
                }
            });
        }
    });
}
</script>
@endpush
@endsection
