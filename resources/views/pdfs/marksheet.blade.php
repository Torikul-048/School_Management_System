<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Marksheet</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 10px; text-align: center; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .total-row { background-color: #e8f5e9; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>MARKSHEET</h2>
        <h3>{{ $exam->name ?? 'Examination' }}</h3>
    </div>

    <table style="border: none; margin-bottom: 20px;">
        <tr style="border: none;">
            <td style="border: none; text-align: left;"><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</td>
            <td style="border: none; text-align: right;"><strong>Admission No:</strong> {{ $student->admission_number }}</td>
        </tr>
        <tr style="border: none;">
            <td style="border: none; text-align: left;"><strong>Class:</strong> {{ $student->class->name ?? 'N/A' }} - {{ $student->section->name ?? 'N/A' }}</td>
            <td style="border: none; text-align: right;"><strong>Roll No:</strong> {{ $student->roll_number }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Max Marks</th>
                <th>Marks Obtained</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($marks ?? [] as $mark)
            <tr>
                <td style="text-align: left;">{{ $mark->subject->name ?? 'N/A' }}</td>
                <td>{{ $mark->total_marks }}</td>
                <td>{{ $mark->obtained_marks }}</td>
                <td><strong>{{ $mark->grade }}</strong></td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td style="text-align: right;"><strong>TOTAL</strong></td>
                <td>{{ collect($marks)->sum('total_marks') }}</td>
                <td>{{ collect($marks)->sum('obtained_marks') }}</td>
                <td>-</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Percentage</strong></td>
                <td><strong>{{ number_format((collect($marks)->sum('obtained_marks') / collect($marks)->sum('total_marks')) * 100, 2) }}%</strong></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Overall Grade</strong></td>
                <td><strong>{{ $marks[0]->overall_grade ?? 'N/A' }}</strong></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Result</strong></td>
                <td><strong>{{ $marks[0]->result ?? 'PASS' }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; overflow: auto;">
        <div style="float: left; width: 30%; text-align: center;">
            <div style="border-top: 1px solid #000; padding-top: 5px;">Class Teacher</div>
        </div>
        <div style="float: right; width: 30%; text-align: center;">
            <div style="border-top: 1px solid #000; padding-top: 5px;">Principal</div>
        </div>
    </div>
</body>
</html>
