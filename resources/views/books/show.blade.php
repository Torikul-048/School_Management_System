@extends('layouts.admin')

@section('title', 'Book Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $book->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Book Details and Issue History</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('books.edit', $book) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Edit Book
                </a>
                <a href="{{ route('books.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                    Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Book Image and Status -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                    <div class="aspect-[3/4] bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-4">
                        @if($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        @if($book->status == 'available')
                            <span class="inline-block px-4 py-2 text-sm font-medium bg-green-100 text-green-800 rounded-full">Available</span>
                        @else
                            <span class="inline-block px-4 py-2 text-sm font-medium bg-red-100 text-red-800 rounded-full">Unavailable</span>
                        @endif
                    </div>

                    <!-- Availability Info -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Available Copies</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $book->available_copies }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Copies</span>
                            <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $book->total_copies }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($book->pdf_file)
                <a href="{{ route('books.download-pdf', $book) }}" class="block w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-center rounded-lg transition-colors mb-3">
                    Download PDF
                </a>
                @endif
                
                @if($book->isAvailable())
                <a href="{{ route('book-issues.create', ['book_id' => $book->id]) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg transition-colors">
                    Issue This Book
                </a>
                @endif
            </div>

            <!-- Book Details -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Book Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">ISBN</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->isbn }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Barcode</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->barcode }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Author</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->author }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Publisher</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->publisher ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Publication Year</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->publication_year ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Category</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->category->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Language</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->language ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Price</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->price ? '৳' . number_format($book->price, 2) : 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Rack Location</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->rack_location ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400 mb-1">Added On</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $book->created_at->format('d M, Y') }}</dd>
                        </div>
                    </div>

                    @if($book->description)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <dt class="text-sm text-gray-600 dark:text-gray-400 mb-2">Description</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $book->description }}</dd>
                    </div>
                    @endif
                </div>

                <!-- Issue History -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Issue History</h3>
                    
                    @if($book->issues->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Issue #</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Borrower</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Issue Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Due Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($book->issues->take(10) as $issue)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $issue->issue_number }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                {{ $issue->student ? $issue->student->name : $issue->teacher->name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $issue->issue_date->format('d M, Y') }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $issue->due_date->format('d M, Y') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if($issue->status == 'issued')
                                                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Issued</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Returned</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No issue history available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
