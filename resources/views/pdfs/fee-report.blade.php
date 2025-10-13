<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Collection Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #4CAF50; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { background: #e8f5e9; padding: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fee Collection Report</h1>
        <p>{{ now()->format('d M Y H:i') }}</p>
    </div>

    @if(isset($summary))
    <div class="summary">
        <strong>Total Collected:</strong> ৳{{ number_format($summary['total_collected'] ?? 0) }} &nbsp;
        <strong>Pending:</strong> ৳{{ number_format($summary['total_pending'] ?? 0) }} &nbsp;
        <strong>Transactions:</strong> {{ $summary['total_transactions'] ?? 0 }}
    </div>
    @endif

    <table>
        <thead>
            <tr><th>Date</th><th>Student</th><th>Class</th><th>Amount</th><th>Method</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($collections ?? [] as $fee)
            <tr>
                <td>{{ $fee->payment_date }}</td>
                <td>{{ $fee->student->first_name ?? 'N/A' }} {{ $fee->student->last_name ?? '' }}</td>
                <td>{{ $fee->student->class->name ?? 'N/A' }}</td>
                <td>৳{{ number_format($fee->paid_amount, 2) }}</td>
                <td>{{ $fee->payment_method }}</td>
                <td>{{ ucfirst($fee->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center;">No records</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
