@extends('layouts.app')
@section('title', 'Visit History | Visitor Management System')

@section('content')
<div class="container">
    <div class="mt-6 mb-6 px-6 py-4 bg-white shadow-md d-flex justify-content-between align-items-center">
        <h1 class="mb-0">Visit History</h1>

        <a href="{{ route('reception.visitor.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Visitors
        </a>
    </div>


    <div class="table-responsive px-6 py-4 bg-white shadow-md">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Visitor Number</th>
                    <th>Names</th>
                    <th>Purpose</th>
                    <th>Check-In Time</th>
                    <th>Check-Out Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $history)
                <tr>
                    <td>{{ $history->visitor->visitor_number}}</td>
                    <td>{{ $history->visitor->name }}</td>
                    <td>{{ $history->purpose }}</td>
                    <td>{{ $history->check_in }}</td>
                    <td>{{ $history->check_out ?? 'Not Checked Out' }}</td>
                    <td>{{ $history->status }}</td>
                    <td>
                        @if (is_null($history->check_out))
                        <!-- Button to trigger checkout confirmation modal -->
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#checkoutModal{{ $history->id }}">Check Out</button>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#checkoutModal{{ $history->id }}">Approve</button>
                        @else
                        <span class="text-muted">Checked Out</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Checkout Confirmation Modal -->
@foreach ($histories as $history)
<div class="modal fade" id="checkoutModal{{ $history->id }}" tabindex="-1" aria-labelledby="checkoutModalLabel{{ $history->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel{{ $history->id }}">Confirm Check-Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to check out this visit, {{ $history->visitor->name }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <!-- Check-Out Form -->
                <form action="{{ route('reception.visitors.checkout', $history->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PUT') <!-- Use @method('PUT') to send a PUT request -->
                    <button type="submit" class="btn btn-success">Confirm Check-Out</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach


@endsection