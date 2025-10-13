@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Notifications & Announcements</h1>

        @forelse($announcements as $announcement)
        <div class="border-l-4 border-blue-500 pl-6 py-4 mb-6 bg-blue-50 dark:bg-blue-900/20 rounded-r-lg">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $announcement->title }}</h3>
                    <p class="text-gray-700 dark:text-gray-300 mt-2">{{ $announcement->content }}</p>
                    <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                        <span>Posted {{ $announcement->created_at->diffForHumans() }}</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">{{ $announcement->type ?? 'General' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-gray-500 py-12">No notifications available.</p>
        @endforelse

        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection
