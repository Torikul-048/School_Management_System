@extends('layouts.admin')

@section('title', 'Library Books')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Library Books</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View your issued books</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Book Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Issue Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Fine</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($issuedBooks as $issue)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-white">{{ $issue->book->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-white">{{ $issue->issue_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-white">{{ $issue->due_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $issue->status == 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($issue->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-white">
                            {{ $issue->fine_amount > 0 ? '৳' . number_format($issue->fine_amount, 2) : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No books issued</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
