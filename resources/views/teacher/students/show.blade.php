@extends('layouts.admin')

@section('title', 'Student Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Student Details</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $student->user->name }}</p>
        </div>
        <a href="{{ route('teacher.students') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
            Back to Students
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Student Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&size=128&background=3b82f6&color=fff" 
                         class="w-32 h-32 rounded-full mx-auto mb-4" alt="{{ $student->user->name }}">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $student->user->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $student->student_id }}</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                        {{ $student->class->name }}
                    </span>
                </div>
                
                <div class="mt-6 space-y-3">
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">{{ $student->user->email }}</span>
                    </div>
                    
                    @if($student->date_of_birth)
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Attendance Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Attendance Summary</h3>
                <div class="grid grid-cols-4 gap-4">
                    @php
                        $attendanceStats = $student->attendance->groupBy('status');
                        $total = $student->attendance->count();
                    @endphp
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $attendanceStats->get('present', collect())->count() }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Present</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-red-600">{{ $attendanceStats->get('absent', collect())->count() }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Absent</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-yellow-600">{{ $attendanceStats->get('late', collect())->count() }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Late</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $total > 0 ? round(($attendanceStats->get('present', collect())->count() / $total) * 100, 1) : 0 }}%
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Attendance</p>
                    </div>
                </div>
            </div>

            <!-- Marks Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Marks</h3>
                @if($student->marks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Subject</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Marks</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($student->marks->take(5) as $mark)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $mark->subject->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $mark->marks_obtained }}/{{ $mark->total_marks }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">{{ $mark->grade }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-gray-500 py-4">No marks recorded yet</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
