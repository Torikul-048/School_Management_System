@extends('layouts.admin')

@section('title', 'Exams & Marks')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Exams & Marks</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View your exam schedule and results</p>
    </div>

    @foreach($exams as $exam)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $exam->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $exam->start_date->format('M d, Y') }} - {{ $exam->end_date->format('M d, Y') }}
                    </p>
                </div>
                <span class="px-3 py-1 text-xs rounded-full {{ $exam->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($exam->status) }}
                </span>
            </div>

            @if($exam->schedules && $exam->schedules->count() > 0)
                <h3 class="font-medium text-gray-800 dark:text-white mb-3">Exam Schedule:</h3>
                <div class="space-y-2 mb-4">
                    @foreach($exam->schedules as $schedule)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $schedule->subject->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $schedule->exam_date->format('M d, Y') }} | {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                </p>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $schedule->room_number }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
