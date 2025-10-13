@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Analytics Dashboard</h2>
            <p class="text-muted">Comprehensive analytics and insights</p>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="row">
        <!-- Student Enrollment Trend -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Student Enrollment Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Attendance Trend -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attendance Trend (Last 30 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Fee Collection -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Fee Collection</h5>
                </div>
                <div class="card-body">
                    <canvas id="feeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Income vs Expense -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Income vs Expense (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="incomeExpenseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Class Distribution -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Student Distribution by Class</h5>
                </div>
                <div class="card-body">
                    <canvas id="classDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gender Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Teacher Workload -->
        <div class="col-md-6 mb-4">
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

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detailed Analytics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('analytics.students') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-users"></i> Student Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('analytics.attendance') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-calendar-check"></i> Attendance Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('analytics.financial') }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-dollar-sign"></i> Financial Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('analytics.teachers') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-chalkboard-teacher"></i> Teacher Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('analytics.performance') }}" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-chart-line"></i> Performance Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('analytics.library') }}" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-book"></i> Library Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart colors
    const colors = {
        primary: 'rgb(54, 162, 235)',
        success: 'rgb(75, 192, 192)',
        warning: 'rgb(255, 205, 86)',
        danger: 'rgb(255, 99, 132)',
        info: 'rgb(153, 102, 255)',
        secondary: 'rgb(201, 203, 207)'
    };

    // Load all charts
    document.addEventListener('DOMContentLoaded', function() {
        loadChart('enrollment', 'enrollmentChart');
        loadChart('attendance', 'attendanceChart');
        loadChart('fee-collection', 'feeChart');
        loadChart('income-expense', 'incomeExpenseChart');
        loadChart('class-distribution', 'classDistributionChart');
        loadChart('gender-distribution', 'genderChart');
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
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: data.datasets.length > 1
                            }
                        },
                        scales: data.type === 'pie' || data.type === 'doughnut' ? {} : {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error loading chart:', error));
    }
</script>
@endpush
