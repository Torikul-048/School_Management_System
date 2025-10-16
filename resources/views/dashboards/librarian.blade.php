@extends('layouts.admin')

@section('title', 'Librarian Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Library Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage books, issues, and library operations</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('book-issues.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Issue Book
            </a>
            <a href="{{ route('books.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Book
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Books -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Books</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalBooks ?? 0 }}</h3>
                </div>
                <div class="bg-blue-400 bg-opacity-30 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('books.index') }}" class="text-sm hover:underline flex items-center">
                    View All Books
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Issued Books -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Issued Books</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $issuedBooks ?? 0 }}</h3>
                </div>
                <div class="bg-green-400 bg-opacity-30 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('book-issues.index') }}?status=issued" class="text-sm hover:underline flex items-center">
                    View Issued Books
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Overdue Books -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm">Overdue Books</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $overdueBooks ?? 0 }}</h3>
                </div>
                <div class="bg-red-400 bg-opacity-30 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('book-issues.overdue') }}" class="text-sm hover:underline flex items-center">
                    View Overdue Books
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Pending Fines -->
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Pending Fines</p>
                    <h3 class="text-3xl font-bold mt-2">${{ number_format($pendingFines ?? 0, 2) }}</h3>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm">Total Collected: ${{ number_format($totalFineCollected ?? 0, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('book-issues.create') }}" class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Issue New Book</span>
                </a>
                <a href="{{ route('books.create') }}" class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Add New Book</span>
                </a>
                <a href="{{ route('library.categories.create') }}" class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Add Category</span>
                </a>
                <a href="{{ route('books.search') }}" class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Search Books</span>
                </a>
                <a href="{{ route('library.reports') }}" class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Generate Reports</span>
                </a>
                <a href="{{ route('books.digital-library') }}" class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-5 h-5 text-pink-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Digital Library</span>
                </a>
            </div>
        </div>

        <!-- Category Statistics -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Categories</h3>
                <a href="{{ route('library.categories') }}" class="text-blue-600 hover:underline text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($categoryStats ?? [] as $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $category->books_count }} books
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No categories found</p>
                @endforelse
            </div>
            @if(($categoryStats ?? collect())->count() > 0)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Total Categories</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $totalCategories ?? 0 }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Issues -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Issues</h3>
                <a href="{{ route('book-issues.index') }}" class="text-blue-600 hover:underline text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentIssues ?? [] as $issue)
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $issue->book->title ?? 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $issue->student ? $issue->student->first_name . ' ' . $issue->student->last_name : ($issue->teacher->first_name . ' ' . $issue->teacher->last_name) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $issue->issue_date->format('M d, Y') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $issue->status === 'issued' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($issue->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No recent issues</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Popular Books & Available Books -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Popular Books -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Most Borrowed Books</h3>
                <a href="{{ route('books.index') }}" class="text-blue-600 hover:underline text-sm">View All</a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($popularBooks ?? [] as $book)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->title }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $book->author }}</p>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $book->issues_count }} issues
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Available Books Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Library Statistics</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <span class="text-gray-700 dark:text-gray-300">Available Books</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $availableBooks ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                        <span class="text-gray-700 dark:text-gray-300">Currently Issued</span>
                        <span class="text-2xl font-bold text-green-600">{{ $issuedBooks ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-red-50 dark:bg-red-900 rounded-lg">
                        <span class="text-gray-700 dark:text-gray-300">Overdue</span>
                        <span class="text-2xl font-bold text-red-600">{{ $overdueBooks ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                        <span class="text-gray-700 dark:text-gray-300">Total Categories</span>
                        <span class="text-2xl font-bold text-purple-600">{{ $totalCategories ?? 0 }}</span>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('library.statistics') }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        View Detailed Statistics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
