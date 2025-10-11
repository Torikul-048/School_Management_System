@extends('layouts.admin')

@section('title', 'Book Issue Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Book Issue Details</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Issue #{{ $bookIssue->issue_number }}</p>
            </div>
            <a href="{{ route('book-issues.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                Back to Issues
            </a>
        </div>

        <!-- Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Status</h3>
                    @if($bookIssue->status == 'issued')
                        @if($bookIssue->isOverdue())
                            <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full">Overdue</span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">Issued</span>
                        @endif
                    @else
                        <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">Returned</span>
                    @endif
                </div>
                @if($bookIssue->status == 'issued')
                    <button onclick="document.getElementById('returnModal').classList.remove('hidden')" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Return Book
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Book Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Book Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Title</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->book->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">ISBN</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->book->isbn }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Author</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->book->author }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Borrower Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Borrower Information</h3>
                <dl class="space-y-3">
                    @if($bookIssue->student)
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Type</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">Student</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Name</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->student->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Roll Number</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->student->roll_number }}</dd>
                        </div>
                    @else
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Type</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">Teacher</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Name</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->teacher->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Employee ID</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->teacher->employee_id }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Issue Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Issue Details</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Issue Date</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->issue_date->format('d M, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Due Date</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->due_date->format('d M, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Issued By</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->issuer->name }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Return Details -->
            @if($bookIssue->status == 'returned')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Return Details</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Return Date</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->return_date->format('d M, Y') }}</dd>
                    </div>
                    @if($bookIssue->fine_amount > 0)
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Fine Amount</dt>
                        <dd class="text-sm font-medium text-red-600 mt-1">৳{{ number_format($bookIssue->fine_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Fine Status</dt>
                        <dd class="text-sm font-medium mt-1">
                            @if($bookIssue->fine_paid)
                                <span class="text-green-600">Paid</span>
                            @else
                                <span class="text-red-600">Unpaid</span>
                            @endif
                        </dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm text-gray-600 dark:text-gray-400">Returned To</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $bookIssue->receiver->name ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>

        <!-- Remarks -->
        @if($bookIssue->remarks)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Remarks</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $bookIssue->remarks }}</p>
        </div>
        @endif

        <!-- Fine Alert -->
        @if($bookIssue->isOverdue() && $bookIssue->status == 'issued')
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mt-6">
            <div class="flex">
                <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-red-800 dark:text-red-400">Overdue Book</h4>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                        This book is {{ now()->diffInDays($bookIssue->due_date) }} days overdue. 
                        Estimated fine: ৳{{ number_format($bookIssue->fine_amount ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Return Book Modal -->
@if($bookIssue->status == 'issued')
<div id="returnModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Return Book</h3>
            <form action="{{ route('book-issues.return', $bookIssue) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Return Date *</label>
                    <input type="date" name="return_date" value="{{ date('Y-m-d') }}" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks</label>
                    <textarea name="remarks" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Return Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
