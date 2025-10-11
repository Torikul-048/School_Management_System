@extends('layouts.admin')

@section('title', 'Leave Request Details')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('leave-requests.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Leave Request Details</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Request #{{ $leaveRequest->id }}</p>
            </div>
        </div>
        @switch($leaveRequest->status)
            @case('pending')
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                @break
            @case('approved')
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Approved</span>
                @break
            @case('rejected')
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Rejected</span>
                @break
            @case('cancelled')
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300">Cancelled</span>
                @break
        @endswitch
    </div>

    <div class="space-y-6">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Student Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Student Information</h3>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                                {{ substr($leaveRequest->student->first_name, 0, 1) }}{{ substr($leaveRequest->student->last_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $leaveRequest->student->first_name }} {{ $leaveRequest->student->last_name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $leaveRequest->student->admission_number }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $leaveRequest->student->class->name }} - {{ $leaveRequest->student->class->section }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leave Details -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Leave Details</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Leave Period</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M, Y') }} to {{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M, Y') }}
                                    <span class="ml-2 text-gray-500 dark:text-gray-400">({{ \Carbon\Carbon::parse($leaveRequest->start_date)->diffInDays(\Carbon\Carbon::parse($leaveRequest->end_date)) + 1 }} days)</span>
                                </dd>
                            </div>
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Reason</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $leaveRequest->reason }}</dd>
                            </div>
                            @if($leaveRequest->emergency_contact)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Emergency Contact</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leaveRequest->emergency_contact }}</dd>
                            </div>
                            @endif
                            @if($leaveRequest->document_path)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supporting Document</dt>
                                <dd class="mt-1">
                                    <a href="{{ Storage::url($leaveRequest->document_path) }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        View Document
                                    </a>
                                </dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted On</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leaveRequest->created_at->format('d M, Y h:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Approval/Rejection Details -->
                @if($leaveRequest->status != 'pending' && $leaveRequest->status != 'cancelled')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            @if($leaveRequest->status == 'approved') Approval @else Rejection @endif Details
                        </h3>
                        <dl class="grid grid-cols-1 gap-4">
                            @if($leaveRequest->approved_by)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    @if($leaveRequest->status == 'approved') Approved By @else Rejected By @endif
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $leaveRequest->approvedBy->name }}</dd>
                            </div>
                            @endif
                            @if($leaveRequest->rejection_reason)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rejection Reason</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $leaveRequest->rejection_reason }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Actions</h3>
                        <div class="space-y-3">
                            @role('Super Admin|Admin|Teacher')
                                @if($leaveRequest->status == 'pending')
                                    <form action="{{ route('leave-requests.approve', $leaveRequest->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Approve Request
                                        </button>
                                    </form>
                                    <button onclick="showRejectModal()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Reject Request
                                    </button>
                                @endif
                            @endrole
                            @role('Student')
                                @if($leaveRequest->status == 'pending')
                                    <form action="{{ route('leave-requests.cancel', $leaveRequest->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?');" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancel Request
                                        </button>
                                    </form>
                                @endif
                            @endrole
                            <a href="{{ route('leave-requests.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-700 focus:outline-none transition">
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRejectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('leave-requests.reject', $leaveRequest->id) }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Leave Request</h3>
                    <div>
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Please provide a reason for rejecting this request..."></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Reject Request
                    </button>
                    <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
