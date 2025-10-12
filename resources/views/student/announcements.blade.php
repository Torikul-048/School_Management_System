@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">School Announcements</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Stay updated with school news</p>
    </div>

    <div class="space-y-4">
        @forelse($announcements as $announcement)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $announcement->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-3">{{ $announcement->content }}</p>
                        <p class="text-xs text-gray-500">{{ $announcement->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <p class="text-gray-600 dark:text-gray-400">No announcements available</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $announcements->links() }}
    </div>
</div>
@endsection
