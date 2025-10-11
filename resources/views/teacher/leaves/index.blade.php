@extends('layouts.admin')

@section('title', 'Leave Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Leave Management</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Apply for and manage leave requests</p>
        </div>
        <a href="{{ route('teacher.leaves.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            Apply for Leave
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Leave Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">End Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Days</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($leaves ?? [] as $leave)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $leave->leave_type }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $leave->days }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @if($leave->status === 'approved') bg-green-100 text-green-800
                            @elseif($leave->status === 'rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No leave requests yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
