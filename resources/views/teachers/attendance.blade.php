<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Teacher Attendance - ') }} {{ $teacher->full_name }}
            </h2>
            <a href="{{ route('teachers.show', $teacher) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Present</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['present'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Absent</div>
                    <div class="text-2xl font-bold text-red-600">{{ $stats['absent'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Late</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['late'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">On Leave</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['on_leave'] }}</div>
                </div>
            </div>

            <!-- Month Filter -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('teachers.attendance', $teacher) }}" class="flex items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Month</label>
                        <input type="month" name="month" value="{{ $month }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Attendance Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Day</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Remarks</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Marked By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ \Carbon\Carbon::parse($attendance->date)->format('l') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->status == 'present')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Present</span>
                                        @elseif($attendance->status == 'absent')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Absent</span>
                                        @elseif($attendance->status == 'late')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Late</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">On Leave</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $attendance->remarks ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $attendance->marker->name ?? 'System' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No attendance records found for this month.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
