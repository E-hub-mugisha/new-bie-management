@extends('layouts.app')
@section('title', 'Visitors | New Bie Management System')

@section('content')
<div class="container">

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <div class="mt-6 mb-6 px-6 py-4 bg-white shadow-md d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Visitors List</h1>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visitorNewModal">
            <i class="fas fa-user-plus"></i> Add New Visitor
        </button>
    </div>


    <div class="table-responsive px-6 py-4 bg-white shadow-md">
    <!-- Visitors List Table -->
        <table class="table dataTable table-striped">
            <thead>
                <tr>
                    <th>Visitor Number</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visitors as $visitor)
                <tr>
                    <td>{{ $visitor->visitor_number }}</td>
                    <td>{{ $visitor->name }}</td>
                    <td>{{ $visitor->email }}</td>
                    <td>{{ $visitor->phone }}</td>
                    <td>{{ $visitor->address }}</td>
                    <td>
                        <a href="{{ route('visitor.profile', $visitor->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-user-circle"></i> View Profile
                        </a>
                        <a href="{{ route('visitor.history.show', $visitor->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-history"></i> View History
                        </a>

                        <!-- Add New Visit Button -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVisitModal{{ $visitor->id }}">
                            <i class="fas fa-calendar-plus"></i> Add New Visit
                        </button>
                    </td>
                </tr>

                <!-- Modal for Adding a New Visit -->
                <div class="modal fade" id="addVisitModal{{ $visitor->id }}" tabindex="-1" aria-labelledby="addVisitModalLabel{{ $visitor->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Visit</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('reception.visitors.newStore', $visitor->id ) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="identification_number" value="{{ $visitor->identification_number }}">
                                    <div class="mb-3">
                                        <label for="purpose" class="form-label">Purpose of Visit</label>
                                        <input type="text" id="purpose" name="purpose" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Log Visit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkout Confirmation Modal -->
                <div class="modal fade" id="checkoutModal{{ $visitor->id }}" tabindex="-1" aria-labelledby="checkoutModalLabel{{ $visitor->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Check-Out</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to check out {{ $visitor->name }}?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('reception.visitors.checkout', $visitor->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check-circle"></i> Confirm Check-Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modal for Adding a New Visitor -->
<div class="modal fade" id="visitorNewModal" tabindex="-1" aria-labelledby="visitorNewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Visitor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reception.visitors.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="identification_number" class="form-label">Identification Number</label>
                        <input type="text" id="identification_number" name="identification_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="identification_type" class="form-label">Identification Type</label>
                        <input type="text" id="identification_type" name="identification_type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="purpose">Purpose</label>
                        <input type="text" id="purpose" name="purpose" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Check In</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.dataTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "responsive": true
        });
    });
</script>
@endpush