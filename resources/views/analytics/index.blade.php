@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Analytics Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Comprehensive analytics and insights</p>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Student Enrollment Trend -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Student Enrollment Trend</h3>
            </div>
            <div class="p-6">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>

        <!-- Attendance Trend -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Attendance Trend (Last 30 Days)</h3>
            </div>
            <div class="p-6">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Fee Collection -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Monthly Fee Collection</h3>
            </div>
            <div class="p-6">
                <canvas id="feeChart"></canvas>
            </div>
        </div>

        <!-- Income vs Expense -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Income vs Expense (Last 12 Months)</h3>
            </div>
            <div class="p-6">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Class Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Student Distribution by Class</h3>
            </div>
            <div class="p-6">
                <canvas id="classDistributionChart"></canvas>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Gender Distribution</h3>
            </div>
            <div class="p-6">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

        <!-- Teacher Workload -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Teacher Workload (Top 10)</h3>
            </div>
            <div class="p-6">
                <canvas id="workloadChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Detailed Analytics</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <a href="{{ route('analytics.students') }}" class="flex items-center justify-center px-4 py-3 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Students</span>
                </a>
                <a href="{{ route('analytics.attendance') }}" class="flex items-center justify-center px-4 py-3 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-700 dark:text-green-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="text-sm font-medium">Attendance</span>
                </a>
                <a href="{{ route('analytics.financial') }}" class="flex items-center justify-center px-4 py-3 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:hover:bg-yellow-800 text-yellow-700 dark:text-yellow-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Financial</span>
                </a>
                <a href="{{ route('analytics.teachers') }}" class="flex items-center justify-center px-4 py-3 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-700 dark:text-purple-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-medium">Teachers</span>
                </a>
                <a href="{{ route('analytics.performance') }}" class="flex items-center justify-center px-4 py-3 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    <span class="text-sm font-medium">Performance</span>
                </a>
                <a href="{{ route('analytics.library') }}" class="flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="text-sm font-medium">Library</span>
                </a>
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
