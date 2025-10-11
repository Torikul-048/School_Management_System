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
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Time</th>
                        @foreach($days as $day)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $timeSlots = [
                            '08:00:00' => '08:00 - 09:00',
                            '09:00:00' => '09:00 - 10:00',
                            '10:00:00' => '10:00 - 11:00',
                            '11:00:00' => '11:00 - 12:00',
                            '12:00:00' => '12:00 - 13:00',
                            '13:00:00' => '13:00 - 14:00',
                            '14:00:00' => '14:00 - 15:00',
                            '15:00:00' => '15:00 - 16:00',
                        ];
                    @endphp
                    @forelse($timeSlots as $time => $label)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $label }}
                        </td>
                        @foreach($days as $day)
                        <td class="px-6 py-4 text-sm">
                            @php
                                $daySchedules = $schedules->get($day, collect());
                                $slot = $daySchedules->where('start_time', $time)->first();
                            @endphp
                            @if($slot)
                                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded">
                                    <p class="font-semibold text-blue-800 dark:text-blue-200">{{ $slot->subject_name }}</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-300">{{ $slot->class_name }}</p>
                                    @if($slot->room_number)
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $slot->room_number }}</p>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($days) + 1 }}" class="px-6 py-12 text-center text-gray-500">
                            No timetable assigned yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
