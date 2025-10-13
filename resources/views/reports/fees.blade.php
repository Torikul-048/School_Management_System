@extends('layouts.app')

@section('title', 'Fee Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Fee Collection Report</h2>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="partial">Partial</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Generate</button>
                                <button type="button" class="btn btn-success" onclick="exportPDF()">PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($data))
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3>৳{{ number_format($summary['total_collected'] ?? 0) }}</h3>
                    <p>Total Collected</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h3>৳{{ number_format($summary['total_pending'] ?? 0) }}</h3>
                    <p>Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3>{{ $summary['total_transactions'] ?? 0 }}</h3>
                    <p>Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3>৳{{ number_format($summary['avg_transaction'] ?? 0) }}</h3>
                    <p>Avg Transaction</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $fee)
                            <tr>
                                <td>{{ $fee->payment_date }}</td>
                                <td>{{ $fee->student->first_name ?? 'N/A' }} {{ $fee->student->last_name ?? '' }}</td>
                                <td>{{ $fee->student->class->name ?? 'N/A' }}</td>
                                <td>৳{{ number_format($fee->paid_amount, 2) }}</td>
                                <td>{{ $fee->payment_method }}</td>
                                <td><span class="badge badge-{{ $fee->status == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($fee->status) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">No records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-3">
        <div class="col-md-12">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function exportPDF() {
        window.location.href = "{{ route('reports.fees') }}?" + new URLSearchParams(new FormData(document.querySelector('form'))).toString() + '&format=pdf';
    }
</script>
@endpush
