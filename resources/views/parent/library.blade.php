@extends('layouts.admin')

@section('title', 'Library Books')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Library Books</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $child->user->name ?? 'Student' }} - Books borrowed</p>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Book Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Return Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($borrowedBooks as $issue)
                    <tr>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">{{ $issue->book->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $issue->book->author ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($issue->issue_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($issue->due_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $issue->return_date ? \Carbon\Carbon::parse($issue->return_date)->format('d M, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($issue->return_date)
                                <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Returned</span>
                            @elseif(\Carbon\Carbon::parse($issue->due_date)->isPast())
                                <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Overdue</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Borrowed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No library books found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $borrowedBooks->links() }}
        </div>
    </div>
</div>
@endsection
