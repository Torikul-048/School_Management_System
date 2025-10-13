<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student ID Card</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 20px; }
        .id-card { border: 2px solid #333; padding: 20px; width: 350px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .photo { width: 100px; height: 120px; border: 1px solid #ddd; margin: 0 auto 15px; display: block; }
        .info { margin: 10px 0; }
        .label { font-weight: bold; display: inline-block; width: 120px; }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="header">
            <h2>{{ config('app.name', 'School Management System') }}</h2>
            <p><strong>STUDENT ID CARD</strong></p>
        </div>

        @if($student->photo)
        <img src="{{ public_path('storage/' . $student->photo) }}" class="photo" />
        @else
        <div class="photo" style="background: #f0f0f0; text-align: center; line-height: 120px;">No Photo</div>
        @endif

        <div class="info">
            <span class="label">Name:</span>
            <span>{{ $student->first_name }} {{ $student->last_name }}</span>
        </div>

        <div class="info">
            <span class="label">Admission No:</span>
            <span>{{ $student->admission_number }}</span>
        </div>

        <div class="info">
            <span class="label">Class:</span>
            <span>{{ $student->class->name ?? 'N/A' }}</span>
        </div>

        <div class="info">
            <span class="label">Roll No:</span>
            <span>{{ $student->roll_number }}</span>
        </div>

        <div class="info">
            <span class="label">DOB:</span>
            <span>{{ $student->date_of_birth }}</span>
        </div>

        <div class="info">
            <span class="label">Blood Group:</span>
            <span>{{ $student->blood_group ?? 'N/A' }}</span>
        </div>

        <div class="info">
            <span class="label">Contact:</span>
            <span>{{ $student->phone }}</span>
        </div>
    </div>
</body>
</html>
