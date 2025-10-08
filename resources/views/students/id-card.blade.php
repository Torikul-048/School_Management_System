<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card - {{ $student->full_name }}</title>
    <style>
        @page {
            size: 3.375in 2.125in;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .id-card {
            width: 3.375in;
            height: 2.125in;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            position: relative;
        }
        .id-card-header {
            background: rgba(255,255,255,0.95);
            padding: 10px;
            text-align: center;
        }
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 2px;
        }
        .id-text {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .id-body {
            display: flex;
            padding: 15px;
            gap: 12px;
            align-items: flex-start;
        }
        .photo-section {
            flex-shrink: 0;
        }
        .student-photo {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 3px solid white;
            background: white;
        }
        .info-section {
            flex: 1;
            color: white;
        }
        .student-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .info-row {
            display: flex;
            margin-bottom: 4px;
            font-size: 10px;
        }
        .info-label {
            font-weight: 600;
            min-width: 50px;
        }
        .info-value {
            flex: 1;
        }
        .id-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.1);
            padding: 5px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8px;
            color: white;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .id-card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="id-card-header">
            <div class="school-name">SchoolERP System</div>
            <div class="id-text">Student Identity Card</div>
        </div>
        
        <div class="id-body">
            <div class="photo-section">
                @if($student->photo)
                    <img src="{{ public_path('storage/' . $student->photo) }}" alt="{{ $student->full_name }}" class="student-photo">
                @else
                    <div class="student-photo" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 24px; font-weight: bold;">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                @endif
            </div>
            
            <div class="info-section">
                <div class="student-name">{{ $student->full_name }}</div>
                
                <div class="info-row">
                    <span class="info-label">Adm No:</span>
                    <span class="info-value">{{ $student->admission_number }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span class="info-value">{{ $student->class->name ?? 'N/A' }} - {{ $student->section->name ?? 'N/A' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Roll No:</span>
                    <span class="info-value">{{ $student->roll_number }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">DOB:</span>
                    <span class="info-value">{{ $student->date_of_birth->format('d M Y') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Blood:</span>
                    <span class="info-value">{{ $student->blood_group ?? 'N/A' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Contact:</span>
                    <span class="info-value">{{ $student->phone }}</span>
                </div>
            </div>
        </div>
        
        <div class="id-footer">
            <div>Valid: {{ $student->academicYear->name ?? date('Y') }}</div>
            <div>{{ $student->address }}</div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
