<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admit Card</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { padding: 8px; border: 1px solid #ddd; }
        .subjects-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .subjects-table th, .subjects-table td { border: 1px solid #000; padding: 10px; text-align: center; }
        .subjects-table th { background-color: #f0f0f0; }
        .photo { width: 100px; height: 120px; border: 1px solid #000; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>ADMIT CARD</h2>
        <h3>{{ $exam->name ?? 'Examination' }}</h3>
        <p>{{ $exam->start_date ?? '' }} to {{ $exam->end_date ?? '' }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 70%;">
                <div><strong>Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</div>
                <div><strong>Admission No:</strong> {{ $student->admission_number }}</div>
                <div><strong>Class:</strong> {{ $student->class->name ?? 'N/A' }} - Section: {{ $student->section->name ?? 'N/A' }}</div>
                <div><strong>Roll No:</strong> {{ $student->roll_number }}</div>
                <div><strong>Date of Birth:</strong> {{ $student->date_of_birth }}</div>
            </td>
            <td style="width: 30%; text-align: center; vertical-align: middle;">
                @if($student->photo)
                <img src="{{ public_path('storage/' . $student->photo) }}" class="photo" />
                @else
                <div class="photo" style="background: #f0f0f0; margin: 0 auto;">No Photo</div>
                @endif
            </td>
        </tr>
    </table>

    <h3>Exam Schedule</h3>
    <table class="subjects-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Subject</th>
                <th>Time</th>
                <th>Max Marks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exam->subjects ?? [] as $subject)
            <tr>
                <td>-</td>
                <td>{{ $subject->name }}</td>
                <td>-</td>
                <td>{{ $subject->pivot->total_marks ?? 100 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <p><strong>Instructions:</strong></p>
        <ol>
            <li>Bring this admit card to every examination.</li>
            <li>Reach the examination hall 15 minutes before the scheduled time.</li>
            <li>Mobile phones and electronic devices are strictly prohibited.</li>
        </ol>
    </div>

    <div style="margin-top: 50px; text-align: right;">
        <div style="border-top: 1px solid #000; display: inline-block; padding-top: 5px; width: 200px;">
            Principal's Signature
        </div>
    </div>
</body>
</html>
