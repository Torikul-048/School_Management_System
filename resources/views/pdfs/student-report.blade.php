<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
        .filters { background: #f5f5f5; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Report</h1>
        <p>Generated on: {{ now()->format('d M Y H:i') }}</p>
    </div>

    @if(isset($filters))
    <div class="filters">
        <strong>Filters:</strong> 
        @foreach($filters as $key => $value)
            {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }} &nbsp;
        @endforeach
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Admission No</th>
                <th>Name</th>
                <th>Class</th>
                <th>Section</th>
                <th>Roll No</th>
                <th>Gender</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students ?? [] as $student)
            <tr>
                <td>{{ $student->admission_number }}</td>
                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                <td>{{ $student->class->name ?? 'N/A' }}</td>
                <td>{{ $student->section->name ?? 'N/A' }}</td>
                <td>{{ $student->roll_number }}</td>
                <td>{{ ucfirst($student->gender) }}</td>
                <td>{{ ucfirst($student->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center;">No students found</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #666;">
        <p>Total Students: {{ count($students ?? []) }}</p>
    </div>
</body>
</html>
