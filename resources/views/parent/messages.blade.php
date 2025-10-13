@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Messages</h1>

        <!-- Send Message Form -->
        <div class="mb-8 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Send New Message</h2>
            <form action="{{ route('parent.messages.send') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recipient</label>
                        <select name="receiver_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                            <option value="">Select Recipient</option>
                            <optgroup label="Teachers">
                                @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} (Teacher)</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Administrators">
                                @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }} (Admin)</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                        <input type="text" name="subject" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message</label>
                        <textarea name="message" rows="4" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Send Message
                    </button>
                </div>
            </form>
        </div>

        <!-- Messages List -->
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Message History</h2>
        <div class="space-y-4">
            @forelse($messages as $message)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $message->status === 'unread' && $message->receiver_id === auth()->id() ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $message->subject }}</h3>
                            @if($message->status === 'unread' && $message->receiver_id === auth()->id())
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-600 text-white rounded">New</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ $message->sender_id === auth()->id() ? 'To: ' . $message->receiver->name : 'From: ' . $message->sender->name }}
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 mt-2">{{ $message->message }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">No messages found.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
