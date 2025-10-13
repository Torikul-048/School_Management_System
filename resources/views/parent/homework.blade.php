@extends('layouts.admin')

@section('title', 'Homework & Assignments')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Homework & Assignments</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $child->user->name ?? 'Student' }} - Class {{ $child->class->name ?? '' }}</p>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Assigned Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($assignments as $assignment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $assignment->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $assignment->subject_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($assignment->assigned_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($assignment->due_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $isPastDue = \Carbon\Carbon::parse($assignment->due_date)->isPast();
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $isPastDue ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $isPastDue ? 'Overdue' : 'Active' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No assignments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($assignments, 'links'))
        <div class="mt-6">
            {{ $assignments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
