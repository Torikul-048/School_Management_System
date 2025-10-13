@extends('layouts.app')

@section('title', 'Performance Analytics')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">Performance Analytics</h2>
            <p class="text-muted">Student academic performance and exam results analysis</p>
        </div>
        <div class="col-md-4 text-right">
            <form method="GET" action="{{ route('analytics.performance') }}" class="form-inline justify-content-end">
                <label for="exam_id" class="mr-2">Exam:</label>
                <select name="exam_id" id="exam_id" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="">Select Exam</option>
                    @foreach($exams ?? [] as $exam)
                    <option value="{{ $exam->id }}" {{ $examId == $exam->id ? 'selected' : '' }}>
                        {{ $exam->name }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if($examId)
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ number_format($stats['avg_marks'] ?? 0, 2) }}%</h3>
                    <p class="mb-0">Average Score</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ number_format($stats['pass_percentage'] ?? 0, 2) }}%</h3>
                    <p class="mb-0">Pass Percentage</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $stats['total_students'] ?? 0 }}</h3>
                    <p class="mb-0">Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $stats['failed_students'] ?? 0 }}</h3>
                    <p class="mb-0">Failed Students</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performance by Class</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceByClassChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Grade Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="gradeDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 10 Performers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Total Marks</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['top_performers'] ?? [] as $index => $performer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $performer->student_name }}</td>
                                    <td>{{ $performer->class_name }}</td>
                                    <td>{{ $performer->total_marks }}</td>
                                    <td>{{ number_format($performer->percentage, 2) }}%</td>
                                    <td><span class="badge badge-success">{{ $performer->grade }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Please select an exam to view performance analytics
    </div>
    @endif

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
    @if($examId)
    document.addEventListener('DOMContentLoaded', function() {
        loadChart('performance-by-class', 'performanceByClassChart');
        loadChart('grade-distribution', 'gradeDistributionChart');
    });

    function loadChart(type, canvasId) {
        fetch(`{{ route('analytics.chart-data', ['type' => '__TYPE__', 'exam_id' => $examId]) }}`.replace('__TYPE__', type))
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
                        responsive: true
                    }
                });
            });
    }
    @endif
</script>
@endpush
