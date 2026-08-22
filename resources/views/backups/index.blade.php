@extends('layouts.app')
@section('title', 'Database Backup')
@section('page-title', 'Database Backup')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-database me-2"></i>Database Backup</h5>
        <button class="btn btn-primary" onclick="createBackup()">
            <i class="fas fa-sync me-1"></i> Update Backup
        </button>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Database Backup:</strong> This updates the single backup file with the latest database data. The old backup will be replaced.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Filename</th>
                        <th>Size</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $k => $backup)
                    <tr>
                        <td>{{ $k + 1 }}</td>
                        <td><strong>{{ $backup['filename'] }}</strong></td>
                        <td>{{ $backup['size'] }}</td>
                        <td>{{ $backup['date'] }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('backups.download', $backup['filename']) }}" class="btn btn-success btn-sm" title="Download">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteBackup('{{ $backup['filename'] }}')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-database fa-3x mb-3 d-block"></i>
                            No backup created yet. Click "Update Backup" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function createBackup() {
    Swal.fire({
        title: 'Updating backup...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    $.ajax({
        url: "{{ route('backups.create') }}",
        type: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(r) {
            Swal.close();
            if (r.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Backup Updated!',
                    text: r.filename + ' (' + r.size + ')'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Backup Failed',
                    text: r.message
                });
            }
        },
        error: function(xhr) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Backup failed'
            });
        }
    });
}

function deleteBackup(filename) {
    Swal.fire({
        title: 'Delete Backup?',
        text: filename,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/backups/' + filename,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    Toast.fire({ icon: 'success', title: 'Backup deleted!' });
                    setTimeout(() => location.reload(), 1000);
                },
                error: function() {
                    Toast.fire({ icon: 'error', title: 'Failed to delete' });
                }
            });
        }
    });
}
</script>
@endpush
@endsection
