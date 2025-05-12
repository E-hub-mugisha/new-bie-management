@extends('layouts.app')
@section('title','Admin | Dashboard')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Admin Dashboard</h1>

            <!-- Dashboard Overview -->
            <div class="row">
                <div class="col-md-4">
                    <div class="px-6 py-4 bg-white shadow-md">
                        <h2 class="mb-3">Total Visitors</h2>
                        <h3>{{ $totalVisitors }}</h3>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="px-6 py-4 bg-white shadow-md">
                        <h2 class="mb-3">Total Check-ins</h2>
                        <h3>{{ $totalCheckins }}</h3>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="px-6 py-4 bg-white shadow-md">
                        <h2 class="mb-3">Total Visits</h2>
                        <h3>{{ $totalVisits }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Visitor History -->
        <div class="col-md-12 mt-6 mb-6 px-6 py-4 bg-white shadow-md">
            <h2>Recent Visitor History</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Purpose</th>
                        <th>Check-in Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentHistory as $history)
                    <tr>
                        <td>{{ $history->id }}</td>
                        <td>{{ $history->visitor->name }}</td>
                        <td>{{ $history->purpose }}</td>
                        <td>{{ $history->check_in }}</td>
                        <td>{{ $history->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection