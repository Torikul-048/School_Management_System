<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Leave Request Details') }}
            </h2>
            <a href="{{ route('teacher-leaves.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Leave Details -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $leave->teacher->full_name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $leave->teacher->employee_id }} • {{ $leave->teacher->designation }}</p>
                    </div>
                    <div>
                        @if($leave->status == 'approved')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        @elseif($leave->status == 'rejected')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                        @else
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Leave Type</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $leave->leaveType->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Days</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $leave->total_days }} days</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">From Date</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($leave->from_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">To Date</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($leave->to_date)->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Reason</label>
                    <p class="mt-2 text-gray-900 dark:text-gray-100">{{ $leave->reason }}</p>
                </div>

                @if($leave->status != 'pending')
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $leave->status == 'approved' ? 'Approved' : 'Rejected' }} By</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $leave->approver->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $leave->status == 'approved' ? 'Approved' : 'Rejected' }} At</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $leave->status == 'approved' ? $leave->approved_at?->format('M d, Y h:i A') : $leave->rejected_at?->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>
                    @if($leave->remarks)
                    <div class="mt-4">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Remarks</label>
                        <p class="mt-2 text-gray-900 dark:text-gray-100">{{ $leave->remarks }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Actions for Pending Requests -->
                @if($leave->status == 'pending')
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex gap-4">
                    <form method="POST" action="{{ route('teacher-leaves.approve', $leave) }}" class="flex-1">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Approval Remarks (Optional)</label>
                            <textarea name="remarks" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" placeholder="Any remarks..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold">
                            Approve Leave
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('teacher-leaves.reject', $leave) }}" class="flex-1">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Remarks (Optional)</label>
                            <textarea name="remarks" rows="2" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" placeholder="Reason for rejection..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold">
                            Reject Leave
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
