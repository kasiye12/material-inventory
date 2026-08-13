@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'User & Role Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Users</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetForm()">
            <i class="fas fa-plus me-1"></i> Add User
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Assigned Projects</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Add User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm">
                <div class="modal-body">
                    <input type="hidden" id="user_id" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Min 6 characters" required>
                            <small class="text-muted">Leave blank when editing to keep current</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="+251...">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Role *</label>
                            <select class="form-select" id="role" name="role" onchange="toggleProjectAssignment()" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucwords(str_replace('_', ' ', $role->name)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Default Location</label>
                            <select class="form-select" id="location_id" name="location_id">
                                <option value="">Select Location</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->code }} - {{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Project Assignment -->
                    <div id="projectAssignment" style="display:none;" class="mb-3">
                        <label class="form-label fw-bold">Assign Projects</label>
                        <div class="card">
                            <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                                @foreach($locations as $loc)
                                @if($loc->type != 'head_office')
                                <div class="form-check">
                                    <input class="form-check-input project-checkbox" type="checkbox" 
                                           name="assigned_projects[]" value="{{ $loc->id }}" id="project{{ $loc->id }}">
                                    <label class="form-check-label" for="project{{ $loc->id }}">
                                        {{ $loc->code }} - {{ $loc->name }}
                                    </label>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="userDetails"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.data') }}",
        columns: [
            { data: null, render: (d,t,r,m) => m.row + 1, orderable: false },
            { data: 'name' },
            { data: 'email' },
            { data: 'phone', defaultContent: '-' },
            { data: 'role' },
            { data: 'assigned_projects' },
            { data: 'status' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        pageLength: 25
    });

    $('#userForm').submit(function(e) {
        e.preventDefault();
        var id = $('#user_id').val();
        var url = id ? '/users/' + id : '/users';
        var method = id ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(response) {
                $('#userModal').modal('hide');
                table.ajax.reload();
                Toast.fire({ icon: 'success', title: response.message });
            },
            error: function(xhr) {
                var msg = xhr.responseJSON?.message || 'Error saving user';
                Toast.fire({ icon: 'error', title: msg });
            }
        });
    });
});

function toggleProjectAssignment() {
    var role = $('#role').val();
    var highLevelRoles = ['admin', 'gm', 'manager', 'checker', 'head_office', 'master_data'];
    
    if (highLevelRoles.includes(role)) {
        $('#projectAssignment').hide();
        $('.project-checkbox').prop('checked', false);
    } else {
        $('#projectAssignment').show();
    }
}

function resetForm() {
    $('#userForm')[0].reset();
    $('#user_id').val('');
    $('#password').prop('required', true);
    $('#projectAssignment').hide();
    $('.project-checkbox').prop('checked', false);
    $('#modalTitle').text('Add User');
    $('#submitBtn').text('Save User');
}

function viewUser(id) {
    $.get('/users/' + id, function(user) {
        var html = `
            <table class="table table-bordered">
                <tr><th width="40%">Name</th><td>${user.name}</td></tr>
                <tr><th>Email</th><td>${user.email}</td></tr>
                <tr><th>Phone</th><td>${user.phone || '-'}</td></tr>
                <tr><th>Role</th><td>${user.roles?.[0]?.name || '-'}</td></tr>
                <tr><th>Location</th><td>${user.location?.name || '-'}</td></tr>
                <tr><th>Assigned Projects</th><td>${user.assigned_projects?.map(p => p.name).join(', ') || 'All Projects'}</td></tr>
                <tr><th>Status</th><td>${user.is_active ? 'Active' : 'Inactive'}</td></tr>
            </table>
        `;
        $('#userDetails').html(html);
        $('#viewUserModal').modal('show');
    });
}

function editUser(id) {
    $.get('/users/' + id, function(user) {
        $('#user_id').val(user.id);
        $('#name').val(user.name);
        $('#email').val(user.email);
        $('#phone').val(user.phone);
        $('#role').val(user.roles?.[0]?.name || '');
        $('#location_id').val(user.location_id);
        $('#password').prop('required', false);
        $('#password').val('');
        $('#is_active').prop('checked', user.is_active);
        
        $('.project-checkbox').prop('checked', false);
        if (user.assigned_projects) {
            user.assigned_projects.forEach(function(project) {
                $('#project' + project.id).prop('checked', true);
            });
        }
        
        toggleProjectAssignment();
        $('#modalTitle').text('Edit User');
        $('#submitBtn').text('Update User');
        $('#userModal').modal('show');
    });
}

function deleteUser(id) {
    Swal.fire({
        title: 'Delete User?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/users/' + id,
                type: 'DELETE',
                success: function(response) {
                    $('#usersTable').DataTable().ajax.reload();
                    Toast.fire({ icon: 'success', title: response.message });
                }
            });
        }
    });
}
</script>
@endpush
@endsection
