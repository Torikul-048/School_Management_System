<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teacher Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #9C27B0; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Teacher Report</h1>
        <p>{{ now()->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr><th>Employee ID</th><th>Name</th><th>Department</th><th>Designation</th><th>Experience</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($teachers ?? [] as $teacher)
            <tr>
                <td>{{ $teacher->employee_id }}</td>
                <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                <td>{{ $teacher->department }}</td>
                <td>{{ $teacher->designation }}</td>
                <td>{{ $teacher->experience_years }} years</td>
                <td>{{ ucfirst($teacher->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center;">No teachers</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
