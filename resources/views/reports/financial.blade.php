@extends('layouts.app')

@section('title', 'Financial Report')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Financial Report</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Generate</button>
                        <button type="button" class="btn btn-success" onclick="exportPDF()">PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($summary))
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3>৳{{ number_format($summary['total_income'] ?? 0) }}</h3>
                    <p>Total Income</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h3>৳{{ number_format($summary['total_expenses'] ?? 0) }}</h3>
                    <p>Total Expenses</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3>৳{{ number_format($summary['net_profit'] ?? 0) }}</h3>
                    <p>Net Profit</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h5>Income Breakdown</h5></div>
                <div class="card-body">
                    <table class="table">
                        @foreach($income ?? [] as $inc)
                        <tr><td>{{ $inc->date }}</td><td>৳{{ number_format($inc->amount, 2) }}</td></tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h5>Expense Breakdown</h5></div>
                <div class="card-body">
                    <table class="table">
                        @foreach($expenses ?? [] as $exp)
                        <tr><td>{{ $exp->category }}</td><td>৳{{ number_format($exp->amount, 2) }}</td></tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection

@push('scripts')
<script>
function exportPDF() {
    window.location.href = "{{ route('reports.financial') }}?" + new URLSearchParams(new FormData(document.querySelector('form'))).toString() + '&format=pdf';
}
</script>
@endpush
