<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .receipt-no { text-align: right; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; margin-top: 20px; }
        .info-table td { padding: 8px; border-bottom: 1px solid #ddd; }
        .amount-table { border-collapse: collapse; margin-top: 30px; }
        .amount-table th, .amount-table td { border: 1px solid #000; padding: 10px; }
        .amount-table th { background-color: #f0f0f0; }
        .total-row { background-color: #e8f5e9; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h3>FEE RECEIPT</h3>
    </div>

    <div class="receipt-no">
        Receipt No: {{ $feeCollection->receipt_number ?? 'N/A' }}<br>
        Date: {{ $feeCollection->payment_date ?? now()->format('d M Y') }}
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 40%;"><strong>Received From:</strong></td>
            <td>{{ $feeCollection->student->first_name ?? 'N/A' }} {{ $feeCollection->student->last_name ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Admission No:</strong></td>
            <td>{{ $feeCollection->student->admission_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Class:</strong></td>
            <td>{{ $feeCollection->student->class->name ?? 'N/A' }} - {{ $feeCollection->student->section->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Fee Type:</strong></td>
            <td>{{ $feeCollection->feeType->name ?? 'Fee Collection' }}</td>
        </tr>
        <tr>
            <td><strong>Period:</strong></td>
            <td>{{ $feeCollection->month ?? 'N/A' }} {{ $feeCollection->year ?? '' }}</td>
        </tr>
    </table>

    <table class="amount-table">
        <thead>
            <tr>
                <th style="width: 60%;">Description</th>
                <th style="text-align: right;">Amount (৳)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $feeCollection->feeType->name ?? 'Fee Payment' }}</td>
                <td style="text-align: right;">{{ number_format($feeCollection->amount ?? 0, 2) }}</td>
            </tr>
            @if(isset($feeCollection->discount) && $feeCollection->discount > 0)
            <tr>
                <td>Discount</td>
                <td style="text-align: right;">-{{ number_format($feeCollection->discount, 2) }}</td>
            </tr>
            @endif
            @if(isset($feeCollection->fine) && $feeCollection->fine > 0)
            <tr>
                <td>Late Fine</td>
                <td style="text-align: right;">{{ number_format($feeCollection->fine, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td><strong>Total Amount Paid</strong></td>
                <td style="text-align: right;"><strong>৳{{ number_format($feeCollection->paid_amount ?? 0, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table style="border: none; margin-top: 20px;">
        <tr style="border: none;">
            <td style="border: none;"><strong>Payment Method:</strong> {{ ucfirst($feeCollection->payment_method ?? 'N/A') }}</td>
        </tr>
        @if($feeCollection->transaction_id)
        <tr style="border: none;">
            <td style="border: none;"><strong>Transaction ID:</strong> {{ $feeCollection->transaction_id }}</td>
        </tr>
        @endif
        @if($feeCollection->remarks)
        <tr style="border: none;">
            <td style="border: none;"><strong>Remarks:</strong> {{ $feeCollection->remarks }}</td>
        </tr>
        @endif
    </table>

    <div style="margin-top: 50px; overflow: auto;">
        <div style="float: left;">
            <p style="margin: 0;"><em>This is a computer-generated receipt</em></p>
        </div>
        <div style="float: right; text-align: center;">
            <div style="border-top: 1px solid #000; padding-top: 5px; width: 200px;">
                Authorized Signature
            </div>
        </div>
    </div>
</body>
</html>
