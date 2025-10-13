<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #009688; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { background: #e0f2f1; padding: 15px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Financial Report</h1>
        <p>{{ now()->format('d M Y') }}</p>
    </div>

    @if(isset($summary))
    <div class="summary">
        <strong>Total Income:</strong> ৳{{ number_format($summary['total_income'] ?? 0) }} &nbsp;
        <strong>Total Expenses:</strong> ৳{{ number_format($summary['total_expenses'] ?? 0) }} &nbsp;
        <strong>Net Profit:</strong> ৳{{ number_format($summary['net_profit'] ?? 0) }}
    </div>
    @endif

    <h3>Income</h3>
    <table>
        <thead><tr><th>Date</th><th>Description</th><th>Amount</th></tr></thead>
        <tbody>
            @forelse($income ?? [] as $inc)
            <tr><td>{{ $inc->date }}</td><td>{{ $inc->description }}</td><td>৳{{ number_format($inc->amount, 2) }}</td></tr>
            @empty
            <tr><td colspan="3" style="text-align: center;">No income records</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Expenses</h3>
    <table>
        <thead><tr><th>Date</th><th>Category</th><th>Amount</th></tr></thead>
        <tbody>
            @forelse($expenses ?? [] as $exp)
            <tr><td>{{ $exp->expense_date }}</td><td>{{ $exp->category }}</td><td>৳{{ number_format($exp->amount, 2) }}</td></tr>
            @empty
            <tr><td colspan="3" style="text-align: center;">No expense records</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
