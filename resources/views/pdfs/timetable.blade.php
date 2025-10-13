<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Timetable</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; padding: 20px; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #4CAF50; color: white; font-weight: bold; }
        .time-col { background-color: #f0f0f0; font-weight: bold; }
        .break { background-color: #ffeb3b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>CLASS TIMETABLE</h2>
        <p><strong>Class:</strong> {{ $class->name ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
            </tr>
        </thead>
        <tbody>
            @php
            $timeSlots = [
                '08:00 - 09:00',
                '09:00 - 10:00',
                '10:00 - 11:00',
                '11:00 - 11:30',
                '11:30 - 12:30',
                '12:30 - 01:30',
                '01:30 - 02:00',
                '02:00 - 03:00',
                '03:00 - 04:00'
            ];
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            @endphp

            @foreach($timeSlots as $index => $time)
            <tr>
                <td class="time-col">{{ $time }}</td>
                @foreach($days as $day)
                @if($time == '11:00 - 11:30' || $time == '01:30 - 02:00')
                <td class="break">{{ $time == '11:00 - 11:30' ? 'BREAK' : 'LUNCH' }}</td>
                @else
                @php
                $schedule = collect($schedules ?? [])->first(function($s) use ($day, $time) {
                    return strtolower($s->day_of_week) == $day && $s->start_time == substr($time, 0, 5);
                });
                @endphp
                <td>
                    @if($schedule)
                    <strong>{{ $schedule->subject->name ?? 'N/A' }}</strong><br>
                    <small>{{ $schedule->teacher->first_name ?? '' }} {{ $schedule->teacher->last_name ?? '' }}</small><br>
                    <small>{{ $schedule->room_number ?? '' }}</small>
                    @else
                    -
                    @endif
                </td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <p><strong>Note:</strong></p>
        <ul style="font-size: 10px;">
            <li>Students must reach class 5 minutes before the scheduled time.</li>
            <li>Bring all necessary books and materials for each subject.</li>
        </ul>
    </div>
</body>
</html>
