@extends('layouts.app')

@section('title', 'Attendance Analytics')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">Attendance Analytics</h2>
            <p class="text-muted">Track and analyze attendance patterns</p>
        </div>
        <div class="col-md-4 text-right">
            <form method="GET" action="{{ route('analytics.attendance') }}" class="form-inline justify-content-end">
                <label for="days" class="mr-2">Days:</label>
                <select name="days" id="days" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="15" {{ $days == 15 ? 'selected' : '' }}>Last 15 Days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                    <option value="60" {{ $days == 60 ? 'selected' : '' }}>Last 60 Days</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 Days</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ number_format($stats['avg_attendance'] ?? 0, 2) }}%</h3>
                    <p class="mb-0">Average Attendance</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ number_format($stats['avg_absent'] ?? 0, 2) }}%</h3>
                    <p class="mb-0">Average Absent</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ number_format($stats['avg_late'] ?? 0, 2) }}%</h3>
                    <p class="mb-0">Average Late</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $stats['total_records'] ?? 0 }}</h3>
                    <p class="mb-0">Total Records</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attendance Trend (Last {{ $days }} Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceTrendChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attendance by Class</h5>
                </div>
                <div class="card-body">
                    <canvas id="classwiseChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('analytics.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Analytics
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadChart('attendance', 'attendanceTrendChart');
    });

    function loadChart(type, canvasId) {
        fetch(`{{ route('analytics.chart-data', ['type' => '__TYPE__']) }}`.replace('__TYPE__', type))
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById(canvasId).getContext('2d');
                new Chart(ctx, {
                    type: data.type || 'line',
                    data: {
                        labels: data.labels,
                        datasets: data.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            });
    }
</script>
@endpush
