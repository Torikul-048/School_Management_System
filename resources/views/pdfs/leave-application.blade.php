<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Application</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin: 20px 0; }
        .signature-section { margin-top: 50px; overflow: auto; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h3>LEAVE APPLICATION</h3>
    </div>

    <div class="content">
        <p>Date: {{ $leave->created_at->format('d M Y') }}</p>
        <p>To,<br>The Principal<br>{{ config('app.name') }}</p>
        <p>Subject: Application for Leave</p>
        <p>Sir/Madam,</p>
        <p style="text-align: justify; text-indent: 50px;">
            I, {{ $leave->student->first_name ?? $leave->teacher->first_name ?? 'N/A' }} 
            {{ $leave->student->last_name ?? $leave->teacher->last_name ?? '' }}, 
            would like to request leave from {{ $leave->start_date }} to {{ $leave->end_date }} 
            for a total of {{ $leave->total_days }} day(s).
        </p>
        <p><strong>Reason:</strong> {{ $leave->reason }}</p>
        <p>I request you to kindly grant me leave for the above-mentioned period.</p>
        <p>Thanking you.</p>
    </div>

    <div class="signature-section">
        <div style="float: left; width: 40%;">
            <p><strong>Applicant Details:</strong></p>
            @if($leave->student)
            <p>Name: {{ $leave->student->first_name }} {{ $leave->student->last_name }}<br>
            Class: {{ $leave->student->class->name ?? 'N/A' }}<br>
            Roll No: {{ $leave->student->roll_number }}</p>
            @else
            <p>Name: {{ $leave->teacher->first_name ?? 'N/A' }} {{ $leave->teacher->last_name ?? '' }}<br>
            Employee ID: {{ $leave->teacher->employee_id ?? 'N/A' }}<br>
            Designation: {{ $leave->teacher->designation ?? 'N/A' }}</p>
            @endif
        </div>
        <div style="float: right; width: 40%; text-align: center;">
            <div style="border-top: 1px solid #000; padding-top: 5px; margin-top: 50px;">
                Signature
            </div>
        </div>
    </div>

    <div style="clear: both; margin-top: 50px; padding-top: 20px; border-top: 1px dashed #000;">
        <p><strong>For Office Use Only:</strong></p>
        <p>Status: {{ ucfirst($leave->status) }}</p>
        @if($leave->remarks)
        <p>Remarks: {{ $leave->remarks }}</p>
        @endif
        <div style="margin-top: 30px; text-align: right;">
            <div style="border-top: 1px solid #000; display: inline-block; padding-top: 5px; width: 200px;">
                Approved By
            </div>
        </div>
    </div>
</body>
</html>
