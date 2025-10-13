@extends('layouts.app')

@section('title', 'Financial Analytics')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">Financial Analytics</h2>
            <p class="text-muted">Revenue, expenses, and financial performance analysis</p>
        </div>
        <div class="col-md-4 text-right">
            <form method="GET" action="{{ route('analytics.financial') }}" class="form-inline justify-content-end">
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control mr-2" required>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control mr-2" required>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0">৳{{ number_format($stats['total_income'] ?? 0) }}</h3>
                    <p class="mb-0">Total Income</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h3 class="mb-0">৳{{ number_format($stats['total_expenses'] ?? 0) }}</h3>
                    <p class="mb-0">Total Expenses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0">৳{{ number_format($stats['net_profit'] ?? 0) }}</h3>
                    <p class="mb-0">Net Profit</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h3 class="mb-0">৳{{ number_format($stats['pending_fees'] ?? 0) }}</h3>
                    <p class="mb-0">Pending Fees</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Fee Collection</h5>
                </div>
                <div class="card-body">
                    <canvas id="feeCollectionChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Expense Breakdown</h5>
                </div>
                <div class="card-body">
                    <canvas id="expenseBreakdownChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Income vs Expense (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="incomeExpenseChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Defaulters -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Fee Defaulters ({{ count($stats['defaulters'] ?? []) }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Total Due</th>
                                    <th>Total Paid</th>
                                    <th>Pending</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['defaulters'] ?? [] as $defaulter)
                                <tr>
                                    <td>{{ $defaulter->student_name }}</td>
                                    <td>{{ $defaulter->class_name }}</td>
                                    <td>৳{{ number_format($defaulter->total_due, 2) }}</td>
                                    <td>৳{{ number_format($defaulter->total_paid, 2) }}</td>
                                    <td class="text-danger">৳{{ number_format($defaulter->pending, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No defaulters found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
        loadChart('fee-collection', 'feeCollectionChart');
        loadChart('expense-breakdown', 'expenseBreakdownChart');
        loadChart('income-expense', 'incomeExpenseChart');
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
                        maintainAspectRatio: canvasId === 'incomeExpenseChart' ? false : true
                    }
                });
            });
    }
</script>
@endpush
