@extends('layouts.admin')

@section('title', 'Contact Message Details')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contact Message Details</h1>
            <p class="text-gray-600 mt-1">View message from {{ $contact->name }}</p>
        </div>
        <a href="{{ route('contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
                    <!-- Status and Actions -->
                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            @if($contact->status === 'new')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    New Message
                                </span>
                            @elseif($contact->status === 'read')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Read
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Replied
                                </span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            @if($contact->status !== 'replied')
                                <form action="{{ route('contacts.mark-replied', $contact) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Mark as Replied
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <div class="text-lg font-semibold">{{ $contact->name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="text-lg">
                                <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $contact->email }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <div class="text-lg">
                                <a href="tel:{{ $contact->phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $contact->phone }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <div class="text-lg capitalize">{{ str_replace('_', ' ', $contact->subject) }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Received At</label>
                            <div class="text-lg">{{ $contact->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="border-t pt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Message</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $contact->message }}</p>
                        </div>
                    </div>

                    <!-- Quick Reply Section -->
                    <div class="border-t mt-6 pt-6">
                        <h3 class="text-lg font-semibold mb-4">Quick Reply</h3>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-700 mb-2">
                                <strong>To:</strong> {{ $contact->email }}
                            </p>
                            <p class="text-sm text-gray-600">
                                You can reply to this message directly from your email client by clicking the email address above.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
@endsection
