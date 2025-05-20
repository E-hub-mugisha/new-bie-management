@extends('layouts.app')

@section('title', 'Visitor Profile | Visitor Management System')

@section('content')

<div class="container my-3">

    <div class="mt-6 mb-6 px-6 py-4 bg-white shadow-md d-flex justify-content-between align-items-center">
        <h1 class="mb-0">{{ $visitor->name }} Profile</h1>

        <a href="{{ route('reception.visitor.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Visitors
        </a>
    </div>

    <div class="row">
        <!-- Visitor Details -->
        <div class="col-md-12">
            <div class="px-6 py-4 bg-white shadow-md">
                <h3 class="card-title mb-3">Visitor Details</h3>
                <ul class="list-group">
                    <div class="row">
                        <li class="col-md-4 list-group-item">
                            <i class="fas fa-user"></i> <strong>Name:</strong> {{ $visitor->name }}
                        </li>
                        <li class="col-md-4 list-group-item">
                            <i class="fas fa-envelope"></i> <strong>Email:</strong> {{ $visitor->email }}
                        </li>
                        <li class="col-md-4 list-group-item">
                            <i class="fas fa-phone"></i> <strong>Phone:</strong> {{ $visitor->phone }}
                        </li>
                        <li class="col-md-4 list-group-item">
                            <i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> {{ $visitor->address }}
                        </li>
                        <li class="col-md-4 list-group-item">
                            <i class="fas fa-id-card"></i> <strong>Identification Number:</strong> {{ $visitor->identification_number }}
                        </li>
                        <li class="col-md-4 list-group-item">
                            <i class="fas fa-passport"></i> <strong>Identification Type:</strong> {{ $visitor->identification_type }}
                        </li>
                        
                    </div>
                    <img src="{{ asset('qr_codes/' . $visitor->visitor_number . '.png') }}" alt="Visitor QR Code">
                </ul>
            </div>
        </div>

        <!-- Visitor History -->
        <div class="col-md-12">
            <div class="px-6 py-4 bg-white shadow-md">
                <h3 class="card-title mb-3">Visit History</h3>
                @if ($history->isEmpty())
                <div class="alert alert-info">
                    No visit history found for this visitor.
                </div>
                @else
                <table class="table dataTable table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Visit Date</th>
                            <th>Purpose</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $record)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($record->visit_date)->format('M d, Y') }}</td>
                            <td>{{ $record->purpose }}</td>
                            <td>
                                @if ($record->status == 'Completed')
                                <span class="badge bg-success">Completed</span>
                                @elseif ($record->status == 'Pending')
                                <span class="badge bg-warning">Pending</span>
                                @else
                                <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>



@endsection