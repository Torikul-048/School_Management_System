@extends('layouts.admin')

@section('title', 'Attendance')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Attendance Management</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View and manage student attendance</p>
        </div>
        <a href="{{ route('teacher.attendance.take') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            Take Attendance
        </a>
    </div>

    <!-- Class Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($classes as $class)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-blue-500 transition-colors cursor-pointer">
                <h3 class="font-semibold text-gray-800 dark:text-white">{{ $class->name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Students: {{ \App\Models\Student::where('class_id', $class->id)->count() }}
                </p>
                <a href="{{ route('teacher.attendance.report') }}?class_id={{ $class->id }}" 
                   class="text-sm text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    View Report →
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Attendance (Last 7 Days)</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentAttendance as $attendance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                            {{ $attendance->student->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                            {{ $attendance->class->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($attendance->status === 'present') bg-green-100 text-green-800
                                @elseif($attendance->status === 'absent') bg-red-100 text-red-800
                                @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            No attendance records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
