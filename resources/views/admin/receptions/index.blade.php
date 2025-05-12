@extends('layouts.app')

@section('title', 'Receptionists | Admin')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-6 mb-6 px-6 py-4 bg-white shadow-md">
        <h2 class="mb-0">Receptionists List</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReceptionistModal">
            <i class="fas fa-user-plus"></i> Add Receptionist
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Receptionist Table -->
    <div class="table-responsive mt-6 mb-6 px-6 py-4 bg-white shadow-md">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receptions as $receptionist)
                <tr>
                    <td>{{ $receptionist->name }}</td>
                    <td>{{ $receptionist->email }}</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="#" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $receptionist->id }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Receptionist Modal -->
<div class="modal fade" id="addReceptionistModal" tabindex="-1" aria-labelledby="addReceptionistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addReceptionistModalLabel">Add New Receptionist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addReceptionistForm" action="{{ route('admin.add.reception')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <input type="hidden" name="role" value="reception">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Add Receptionist
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal for Each Receptionist -->
@foreach ($receptions as $receptionist)
<div class="modal fade" id="deleteModal{{ $receptionist->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $receptionist->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel{{ $receptionist->id }}">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete {{ $receptionist->name }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.reception.delete', $receptionist->id ) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- JavaScript Enhancements -->
<script>
    document.getElementById('addReceptionistForm').addEventListener('submit', function() {
        let submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;
    });
</script>

@endsection
