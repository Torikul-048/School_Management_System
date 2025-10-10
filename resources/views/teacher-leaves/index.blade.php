<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Teacher Leave Management') }}
            </h2>
            <a href="{{ route('teacher-leaves.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                Apply Leave
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Requests</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Pending</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Approved</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Rejected</div>
                    <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('teacher-leaves.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Teacher name" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Leave Type</label>
                        <select name="leave_type" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">All Types</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Leave Requests Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Teacher</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Leave Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">From - To</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Days</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($leaves as $leave)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $leave->teacher->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $leave->teacher->employee_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $leave->leaveType->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ \Carbon\Carbon::parse($leave->from_date)->format('M d, Y') }}<br>
                                        {{ \Carbon\Carbon::parse($leave->to_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $leave->total_days }} days
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('teacher-leaves.show', $leave) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">View</a>
                                        @if($leave->status == 'pending')
                                        <form method="POST" action="{{ route('teacher-leaves.approve', $leave) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('teacher-leaves.reject', $leave) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No leave requests found.
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
        </div>
    </div>
</x-app-layout>
