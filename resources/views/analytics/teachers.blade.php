@extends('layouts.app')

@section('title', 'Teacher Analytics')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Teacher Analytics</h2>
            <p class="text-muted">Teacher performance and workload analysis</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $stats['total_teachers'] ?? 0 }}</h3>
                    <p class="mb-0">Total Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $stats['active_teachers'] ?? 0 }}</h3>
                    <p class="mb-0">Active Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ number_format($stats['avg_experience'] ?? 0, 1) }}</h3>
                    <p class="mb-0">Avg Experience (Years)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ number_format($stats['avg_attendance'] ?? 0, 2) }}%</h3>
                    <p class="mb-0">Avg Attendance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Teacher Workload (Top 10)</h5>
                </div>
                <div class="card-body">
                    <canvas id="workloadChart"></canvas>
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
        loadChart('teacher-workload', 'workloadChart');
    });

    function loadChart(type, canvasId) {
        fetch(`{{ route('analytics.chart-data', ['type' => '__TYPE__']) }}`.replace('__TYPE__', type))
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById(canvasId).getContext('2d');
                new Chart(ctx, {
                    type: data.type || 'bar',
                    data: {
                        labels: data.labels,
                        datasets: data.datasets
                    },
                    options: {
                        responsive: true,
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    }
</script>
@endpush
