<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #2196F3; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { background: #f0f8ff; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .summary-item { display: inline-block; margin-right: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Attendance Report</h1>
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
        @if(isset($filters))
        <p>Period: {{ $filters['start_date'] ?? '' }} to {{ $filters['end_date'] ?? '' }}</p>
        @endif
    </div>

    @if(isset($summary))
    <div class="summary">
        <div class="summary-item"><strong>Total Present:</strong> {{ $summary['total_present'] ?? 0 }}</div>
        <div class="summary-item"><strong>Total Absent:</strong> {{ $summary['total_absent'] ?? 0 }}</div>
        <div class="summary-item"><strong>Total Late:</strong> {{ $summary['total_late'] ?? 0 }}</div>
        <div class="summary-item"><strong>Avg Attendance:</strong> {{ number_format($summary['avg_percentage'] ?? 0, 2) }}%</div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Late</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances ?? [] as $att)
            <tr>
                <td>{{ $att->date }}</td>
                <td>{{ $att->class->name ?? 'N/A' }}</td>
                <td>{{ $att->subject->name ?? 'N/A' }}</td>
                <td>{{ $att->present_count }}</td>
                <td>{{ $att->absent_count }}</td>
                <td>{{ $att->late_count }}</td>
                <td>{{ number_format($att->attendance_percentage, 2) }}%</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center;">No attendance records</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
