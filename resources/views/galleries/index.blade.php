@extends('layouts.admin')

@section('title', 'Gallery Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Gallery Management</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Manage photo galleries and albums</p>
        </div>
        <a href="{{ route('galleries.create') }}" 
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Gallery
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search galleries..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <select name="category" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ ucfirst($cat) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <select name="sort_by" 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="event_date" {{ request('sort_by') == 'event_date' ? 'selected' : '' }}>Event Date</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                    <option value="views_count" {{ request('sort_by') == 'views_count' ? 'selected' : '' }}>Views</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                    Filter
                </button>
                <a href="{{ route('galleries.index') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($galleries as $gallery)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
            <!-- Cover Image -->
            <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
                @if($gallery->cover_image)
                <img src="{{ asset('storage/' . $gallery->cover_image) }}" 
                     alt="{{ $gallery->title }}"
                     class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                @endif

                <!-- Badges -->
                <div class="absolute top-2 left-2 flex gap-2">
                    @if($gallery->is_featured)
                    <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-semibold rounded">Featured</span>
                    @endif
                    <span class="px-2 py-1 text-xs font-semibold rounded
                        @if($gallery->status == 'published') bg-green-500 text-white
                        @elseif($gallery->status == 'draft') bg-gray-500 text-white
                        @else bg-red-500 text-white
                        @endif">
                        {{ ucfirst($gallery->status) }}
                    </span>
                </div>

                <!-- Image Count -->
                <div class="absolute bottom-2 right-2 px-2 py-1 bg-black bg-opacity-75 text-white text-xs rounded">
                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $gallery->image_count }} photos
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-xs font-semibold rounded">
                        {{ ucfirst($gallery->category) }}
                    </span>
                    @if($gallery->event_date)
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $gallery->event_date->format('M d, Y') }}
                    </span>
                    @endif
                </div>

                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2 line-clamp-2">
                    {{ $gallery->title }}
                </h3>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                    {{ $gallery->description ?? 'No description available' }}
                </p>

                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            {{ $gallery->views_count }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('galleries.show', $gallery) }}" 
                           class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                            View
                        </a>
                        <a href="{{ route('galleries.edit', $gallery) }}" 
                           class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded">
                            Edit
                        </a>
                        <form action="{{ route('galleries.destroy', $gallery) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this gallery?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500 dark:text-gray-400">No galleries found. Create your first gallery!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($galleries->hasPages())
    <div class="mt-6">
        {{ $galleries->links() }}
    </div>
    @endif
</div>
@endsection
