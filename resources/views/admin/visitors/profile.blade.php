@extends('layouts.app')

@section('title', 'Visitor Profile | Admin')

@section('content')

<div class="container">
    <div class="row">

        <div class="col-md-12 mt-6 mb-6 px-6 py-4 bg-white shadow-md">
            <h1>Visitor Profile: {{ $visitor->name }}</h1>

            <div class="mb-4 text-center">
                <a type="button" href="{{ route('admin.visitors.index') }}" class="float-end btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Visitors List
                </a>
            </div>
        </div>

        <!-- Visitor Details -->
        <div class="col-md-5">

            <div class="mb-4 mt-6 mb-6 px-6 py-4 bg-white shadow-md">
                <h3>Visitor Details</h3>
                <div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="fas fa-user"></i> <strong>Name:</strong> {{ $visitor->name }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-envelope"></i> <strong>Email:</strong> {{ $visitor->email }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-phone"></i> <strong>Phone:</strong> {{ $visitor->phone }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-map-marker-alt"></i> <strong>Address:</strong> {{ $visitor->address }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-id-card"></i> <strong>Identification Number:</strong> {{ $visitor->identification_number }}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-passport"></i> <strong>Identification Type:</strong> {{ $visitor->identification_type }}
                        </li>
                    </ul>

                    @if ($visitor->qr_code)
                    <div class="mt-4 text-center">
                        <h5 class="mb-4">Visitor QR Code</h5>
                        <img src="{{ $visitor->qr_code }}" alt="Visitor QR Code" width="200" height="200" class="mx-auto d-block">
                        <p class="mt-2"><small>{{ $visitor->visitor_number }}</small></p>
                        <a href="{{ $visitor->qr_code }}" class="btn btn-outline-primary btn-sm mt-2" download>
                            <i class="fas fa-download"></i> Download QR Code
                        </a>
                    </div>
                    @else
                    <div class="mt-4 text-center">
                        <h5 class="mb-4 text-danger">QR Code Not Generated</h5>
                        <form action="{{ route('admin.visitors.generateQR', $visitor->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-qrcode"></i> Generate QR Code
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Visitor History -->
        <div class="col-md-7">
            <div class="mt-6 mb-6 px-6 py-4 bg-white shadow-md ">
                <h3>Visit History</h3>
                <div>
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
</div>

@endsection