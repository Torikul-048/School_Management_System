@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Messages</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Communicate with teachers and admin</p>
    </div>

    <div class="space-y-4">
        @forelse($messages as $message)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $message->subject }}</h3>
                    <span class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                    From: {{ $message->sender->name }}
                </p>
                <p class="text-gray-700 dark:text-gray-300">{{ Str::limit($message->message, 150) }}</p>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <p class="text-gray-600 dark:text-gray-400">No messages found</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
</div>
@endsection
