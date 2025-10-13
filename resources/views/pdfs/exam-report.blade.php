<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exam Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #FF5722; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Exam Report</h1>
        <h3>{{ $exam->name ?? 'Exam' }}</h3>
        <p>{{ now()->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr><th>Student</th><th>Class</th><th>Total Marks</th><th>Percentage</th><th>Grade</th></tr>
        </thead>
        <tbody>
            @forelse($studentResults ?? [] as $result)
            <tr>
                <td>{{ $result->student_name }}</td>
                <td>{{ $result->class_name }}</td>
                <td>{{ $result->total_marks }}</td>
                <td>{{ number_format($result->percentage, 2) }}%</td>
                <td><strong>{{ $result->grade }}</strong></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center;">No results</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
