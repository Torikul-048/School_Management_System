<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Leave History') }}
            </h2>
            <a href="{{ route('teacher-leaves.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Back to Leaves
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('teacher-leaves.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @can('manage-leaves')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teacher</label>
                        <select name="teacher_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endcan
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
                        <select name="year" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">All Years</option>
                            @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type</label>
                        <select name="leave_type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">All Types</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ request('leave_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Leave History Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    @can('manage-leaves')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Teacher</th>
                                    @endcan
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Leave Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Days</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($leaves as $leave)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $leave->created_at->format('M d, Y') }}
                                    </td>
                                    @can('manage-leaves')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="font-medium">{{ $leave->teacher->full_name }}</div>
                                        <div class="text-gray-500 text-xs">{{ $leave->teacher->employee_id }}</div>
                                    </td>
                                    @endcan
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $leave->leaveType->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ \Carbon\Carbon::parse($leave->from_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->to_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                        {{ $leave->total_days }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($leave->status == 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                        @elseif($leave->status == 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ Str::limit($leave->reason, 40) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->can('manage-leaves') ? 7 : 6 }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No leave history found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $leaves->links() }}
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Leaves Taken</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $summary['total_leaves'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Days</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $summary['total_days'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Approved</div>
                    <div class="text-2xl font-bold text-green-600">{{ $summary['approved'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Rejected</div>
                    <div class="text-2xl font-bold text-red-600">{{ $summary['rejected'] }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
