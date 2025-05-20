@extends('layouts.app')
@section('title','Admin | Dashboard')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Admin Dashboard</h1>

            <!-- Filter Dropdown -->
            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-3">
                <label for="filter">Filter:</label>
                <select name="filter" id="filter" onchange="this.form.submit()" class="form-select w-auto d-inline-block ms-2">
                    <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>This Week</option>
                </select>
            </form>

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
            <table class="table dataTable">
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

        <!-- Charts -->
        <div class="card mt-4">
            <canvas id="visitorChart"></canvas>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <canvas id="purposePieChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const visitorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Visitor Check-ins',
                data: {!! json_encode($data) !!},
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { title: { display: true, text: 'Visits' }, beginAtZero: true }
            }
        }
    });

    const purposeCtx = document.getElementById('purposePieChart').getContext('2d');
    new Chart(purposeCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($purposeCounts->keys()) !!},
            datasets: [{
                label: 'Purposes',
                data: {!! json_encode($purposeCounts->values()) !!},
                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF']
            }]
        }
    });

    const statusCtx = document.getElementById('statusPieChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($statusCounts->keys()) !!},
            datasets: [{
                label: 'Statuses',
                data: {!! json_encode($statusCounts->values()) !!},
                backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8']
            }]
        }
    });
</script>

@endsection
