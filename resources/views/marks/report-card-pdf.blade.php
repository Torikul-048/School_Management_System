<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - {{ $student->first_name }} {{ $student->last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 20px;
            color: #4b5563;
            margin-top: 10px;
        }
        .student-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 150px;
            padding: 8px 0;
            color: #374151;
        }
        .info-value {
            display: table-cell;
            padding: 8px 0;
        }
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .marks-table th {
            background-color: #2563eb;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .marks-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .marks-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .grade {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        .grade-a-plus, .grade-a { background-color: #d1fae5; color: #065f46; }
        .grade-b-plus, .grade-b { background-color: #dbeafe; color: #1e40af; }
        .grade-c-plus, .grade-c { background-color: #fef3c7; color: #92400e; }
        .grade-d { background-color: #fed7aa; color: #9a3412; }
        .grade-f { background-color: #fee2e2; color: #991b1b; }
        .summary {
            background-color: #f3f4f6;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-row {
            display: table-row;
        }
        .summary-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px 0;
            color: #374151;
        }
        .summary-value {
            display: table-cell;
            padding: 8px 0;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
        }
        .grading-scale {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .grading-scale h3 {
            margin-top: 0;
            color: #1f2937;
        }
        .grading-scale table {
            width: 100%;
            border-collapse: collapse;
        }
        .grading-scale td {
            padding: 6px 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 60px;
        }
        .signature {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 10px;
        }
        .signature-line {
            border-top: 2px solid #333;
            padding-top: 5px;
            margin-top: 50px;
            font-weight: bold;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(0, 0, 0, 0.05);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">SCHOOL REPORT CARD</div>

    <!-- Header -->
    <div class="header">
        <div class="school-name">{{ config('app.name', 'School Management System') }}</div>
        <div>School Address Line 1, City, State, ZIP</div>
        <div>Phone: (123) 456-7890 | Email: info@school.edu</div>
        <div class="report-title">ACADEMIC REPORT CARD</div>
    </div>

    <!-- Student Information -->
    <div class="student-info">
        <div class="info-row">
            <div class="info-label">Student Name:</div>
            <div class="info-value">{{ $student->first_name }} {{ $student->last_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Admission Number:</div>
            <div class="info-value">{{ $student->admission_number }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Class:</div>
            <div class="info-value">{{ $student->class->name }} - {{ $student->class->section }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Exam:</div>
            <div class="info-value">{{ $exam->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Academic Year:</div>
            <div class="info-value">{{ $exam->academicYear->year }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Report Date:</div>
            <div class="info-value">{{ date('F d, Y') }}</div>
        </div>
    </div>

    <!-- Marks Table -->
    <table class="marks-table">
        <thead>
            <tr>
                <th style="width: 40%;">Subject</th>
                <th style="width: 15%; text-align: center;">Total Marks</th>
                <th style="width: 15%; text-align: center;">Marks Obtained</th>
                <th style="width: 15%; text-align: center;">Percentage</th>
                <th style="width: 15%; text-align: center;">Grade</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalMarks = 0;
                $totalObtained = 0;
            @endphp
            @foreach($marks as $mark)
                @php
                    $subjectTotal = $mark->examSchedule->total_marks ?? 100;
                    $totalMarks += $subjectTotal;
                    $totalObtained += $mark->marks_obtained;
                    $percentage = ($mark->marks_obtained / $subjectTotal) * 100;
                @endphp
                <tr>
                    <td>{{ $mark->subject->name }}</td>
                    <td style="text-align: center;">{{ $subjectTotal }}</td>
                    <td style="text-align: center;">{{ number_format($mark->marks_obtained, 2) }}</td>
                    <td style="text-align: center;">{{ number_format($percentage, 2) }}%</td>
                    <td style="text-align: center;">
                        <span class="grade grade-{{ strtolower(str_replace('+', '-plus', $mark->grade)) }}">
                            {{ $mark->grade }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-label">Total Marks:</div>
                <div class="summary-value">{{ $totalMarks }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Marks Obtained:</div>
                <div class="summary-value">{{ number_format($totalObtained, 2) }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Overall Percentage:</div>
                <div class="summary-value">{{ number_format(($totalObtained / $totalMarks) * 100, 2) }}%</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Result:</div>
                <div class="summary-value" style="color: {{ $totalObtained >= ($totalMarks * 0.33) ? '#059669' : '#dc2626' }};">
                    {{ $totalObtained >= ($totalMarks * 0.33) ? 'PASS' : 'FAIL' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Grading Scale -->
    <div class="grading-scale">
        <h3>Grading Scale</h3>
        <table>
            <tr>
                <td><strong>A+ (90-100%)</strong></td>
                <td>Excellent</td>
                <td><strong>B+ (70-79%)</strong></td>
                <td>Good</td>
            </tr>
            <tr>
                <td><strong>A (80-89%)</strong></td>
                <td>Very Good</td>
                <td><strong>B (60-69%)</strong></td>
                <td>Above Average</td>
            </tr>
            <tr>
                <td><strong>C+ (50-59%)</strong></td>
                <td>Average</td>
                <td><strong>D (33-39%)</strong></td>
                <td>Pass</td>
            </tr>
            <tr>
                <td><strong>C (40-49%)</strong></td>
                <td>Below Average</td>
                <td><strong>F (<33%)</strong></td>
                <td>Fail</td>
            </tr>
        </table>
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature">
            <div class="signature-line">Class Teacher</div>
        </div>
        <div class="signature">
            <div class="signature-line">Principal</div>
        </div>
        <div class="signature">
            <div class="signature-line">Parent/Guardian</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p style="text-align: center; color: #6b7280; font-size: 12px;">
            This is a computer-generated report card. For any discrepancies, please contact the school office.<br>
            Generated on {{ date('F d, Y \a\t h:i A') }}
        </p>
    </div>
</body>
</html>
