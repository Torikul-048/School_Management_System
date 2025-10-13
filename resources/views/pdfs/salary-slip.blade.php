<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salary Slip</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .info-table td { padding: 8px; border-bottom: 1px solid #ddd; }
        .salary-table th, .salary-table td { border: 1px solid #000; padding: 10px; }
        .salary-table th { background-color: #f0f0f0; text-align: left; }
        .total-row { background-color: #e8f5e9; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h3>SALARY SLIP</h3>
        <p>Month: {{ $salary->month ?? now()->format('F Y') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 40%;"><strong>Employee Name:</strong></td>
            <td>{{ $salary->teacher->first_name ?? 'N/A' }} {{ $salary->teacher->last_name ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Employee ID:</strong></td>
            <td>{{ $salary->teacher->employee_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Designation:</strong></td>
            <td>{{ $salary->teacher->designation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Department:</strong></td>
            <td>{{ $salary->teacher->department ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="salary-table">
        <thead>
            <tr>
                <th colspan="2" style="text-align: center;">EARNINGS</th>
                <th colspan="2" style="text-align: center;">DEDUCTIONS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td style="text-align: right;">৳{{ number_format($salary->basic_salary ?? 0, 2) }}</td>
                <td>Provident Fund</td>
                <td style="text-align: right;">৳{{ number_format($salary->provident_fund ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>HRA</td>
                <td style="text-align: right;">৳{{ number_format($salary->hra ?? 0, 2) }}</td>
                <td>Professional Tax</td>
                <td style="text-align: right;">৳{{ number_format($salary->professional_tax ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Transport Allowance</td>
                <td style="text-align: right;">৳{{ number_format($salary->transport_allowance ?? 0, 2) }}</td>
                <td>Income Tax</td>
                <td style="text-align: right;">৳{{ number_format($salary->income_tax ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Medical Allowance</td>
                <td style="text-align: right;">৳{{ number_format($salary->medical_allowance ?? 0, 2) }}</td>
                <td>Other Deductions</td>
                <td style="text-align: right;">৳{{ number_format($salary->other_deductions ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Other Allowance</td>
                <td style="text-align: right;">৳{{ number_format($salary->other_allowance ?? 0, 2) }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr class="total-row">
                <td>GROSS SALARY</td>
                <td style="text-align: right;">৳{{ number_format($salary->gross_salary ?? 0, 2) }}</td>
                <td>TOTAL DEDUCTIONS</td>
                <td style="text-align: right;">৳{{ number_format($salary->total_deductions ?? 0, 2) }}</td>
            </tr>
            <tr style="background-color: #fff3cd; font-weight: bold; font-size: 14px;">
                <td colspan="3">NET SALARY</td>
                <td style="text-align: right;">৳{{ number_format($salary->net_salary ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; overflow: auto;">
        <div style="float: left;">
            <p><em>This is a computer-generated slip</em></p>
        </div>
        <div style="float: right; text-align: center;">
            <div style="border-top: 1px solid #000; padding-top: 5px; width: 200px;">
                Authorized Signature
            </div>
        </div>
    </div>
</body>
</html>
