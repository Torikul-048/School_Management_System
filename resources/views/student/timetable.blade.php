@extends('layouts.admin')

@section('title', 'My Timetable')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Timetable</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View your weekly class schedule</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Time
                        </th>
                        @foreach($days as $day)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                {{ $day }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $timeSlots = [
                            '08:00 - 09:00',
                            '09:00 - 10:00',
                            '10:00 - 11:00',
                            '11:00 - 12:00',
                            '12:00 - 13:00',
                            '13:00 - 14:00',
                            '14:00 - 15:00',
                            '15:00 - 16:00',
                        ];
                    @endphp
                    @foreach($timeSlots as $slot)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $slot }}
                            </td>
                            @foreach($days as $day)
                                @php
                                    $schedule = $schedules->get($day, collect())->first(function($s) use ($slot) {
                                        $time = substr($slot, 0, 5);
                                        return substr($s->start_time, 0, 5) == $time;
                                    });
                                @endphp
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    @if($schedule)
                                        <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-2 rounded">
                                            <p class="font-semibold text-gray-800 dark:text-white">{{ $schedule->subject_name }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $schedule->first_name }} {{ $schedule->last_name }}</p>
                                            @if($schedule->room_number)
                                                <p class="text-xs text-gray-500 dark:text-gray-500">Room: {{ $schedule->room_number }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
