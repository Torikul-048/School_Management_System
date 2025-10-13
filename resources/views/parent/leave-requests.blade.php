@extends('layouts.admin')

@section('title', 'Leave Requests')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Leave Requests</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $child->user->name ?? 'Student' }}</p>

        <!-- Apply for Leave Form -->
        <div class="mb-8 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Apply for Leave</h2>
            <form action="{{ route('parent.leave-requests.apply', $child->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" name="start_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" name="end_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason</label>
                        <textarea name="reason" rows="3" required placeholder="Please provide reason for leave..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Submit Leave Request
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Leave History -->
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Leave History</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Applied On</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($leaveRequests as $leave)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($leave->end_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($leave->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($leave->reason, 50) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $leave->created_at->format('d M, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No leave requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $leaveRequests->links() }}
        </div>
    </div>
</div>
@endsection
