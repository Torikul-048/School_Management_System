@extends('layouts.admin')

@section('title', 'Attendance Records')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Attendance Record</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $child->user->name ?? 'Student' }} - Class {{ $child->class->name ?? '' }}</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <div class="text-blue-600 dark:text-blue-400 text-sm font-medium">Total Days</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $summary['total'] }}</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                <div class="text-green-600 dark:text-green-400 text-sm font-medium">Present</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $summary['present'] }}</div>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                <div class="text-red-600 dark:text-red-400 text-sm font-medium">Absent</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $summary['absent'] }}</div>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                <div class="text-yellow-600 dark:text-yellow-400 text-sm font-medium">Late</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $summary['late'] }}</div>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($attendanceRecords as $record)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($record->date)->format('d M, Y (D)') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($record->status === 'present')
                                <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Present</span>
                            @elseif($record->status === 'absent')
                                <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Absent</span>
                            @elseif($record->status === 'late')
                                <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Late</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">{{ ucfirst($record->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $record->check_in_time ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $record->remarks ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No attendance records found for this period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $attendanceRecords->links() }}
        </div>
    </div>
</div>
@endsection
