<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notice Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('notices.edit', $notice) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('notices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header Section -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $notice->title }}</h1>
                            <div class="flex flex-wrap items-center gap-3">
                                @php
                                    $typeColors = [
                                        'general' => 'bg-gray-100 text-gray-800',
                                        'urgent' => 'bg-red-100 text-red-800',
                                        'academic' => 'bg-blue-100 text-blue-800',
                                        'exam' => 'bg-purple-100 text-purple-800',
                                        'fee' => 'bg-green-100 text-green-800',
                                        'holiday' => 'bg-pink-100 text-pink-800',
                                        'event' => 'bg-indigo-100 text-indigo-800',
                                        'other' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $priorityColors = [
                                        'low' => 'bg-gray-100 text-gray-600',
                                        'normal' => 'bg-blue-100 text-blue-600',
                                        'high' => 'bg-orange-100 text-orange-600',
                                        'urgent' => 'bg-red-100 text-red-600',
                                    ];
                                    $statusColors = [
                                        'draft' => 'bg-yellow-100 text-yellow-800',
                                        'published' => 'bg-green-100 text-green-800',
                                        'expired' => 'bg-gray-100 text-gray-800',
                                        'archived' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $typeColors[$notice->notice_type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($notice->notice_type) }}
                                </span>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $priorityColors[$notice->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($notice->priority) }} Priority
                                </span>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$notice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($notice->status) }}
                                </span>
                                @if($notice->is_pinned)
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                                        </svg>
                                        Pinned
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="p-6 space-y-6">
                    <!-- Meta Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-600">Published By:</p>
                            <p class="text-base font-medium text-gray-900">{{ $notice->creator->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Publish Date:</p>
                            <p class="text-base font-medium text-gray-900">{{ $notice->publish_date->format('F d, Y') }}</p>
                        </div>
                        @if($notice->expiry_date)
                            <div>
                                <p class="text-sm text-gray-600">Expiry Date:</p>
                                <p class="text-base font-medium text-gray-900">{{ $notice->expiry_date->format('F d, Y') }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600">Created At:</p>
                            <p class="text-base font-medium text-gray-900">{{ $notice->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Content</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">{{ $notice->content }}</p>
                        </div>
                    </div>

                    <!-- Target Audience -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Target Audience</h3>
                        <div class="flex flex-wrap gap-2">
                            @if(isset($notice->target_audience['roles']))
                                @foreach($notice->target_audience['roles'] as $role)
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-indigo-100 text-indigo-800">
                                        {{ $role }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-500">No specific audience</span>
                            @endif
                        </div>
                    </div>

                    <!-- Attachment -->
                    @if($notice->attachment)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Attachment</h3>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <a href="{{ Storage::url($notice->attachment) }}" 
                                   target="_blank"
                                   class="text-indigo-600 hover:text-indigo-800 flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium">{{ basename($notice->attachment) }}</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Notification Settings -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Notification Settings</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                @if($notice->send_email)
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Email Notifications Enabled</span>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-500">Email Notifications Disabled</span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                @if($notice->send_sms)
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">SMS Notifications Enabled</span>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-500">SMS Notifications Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Status Info -->
                    @if($notice->isExpired())
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-red-700 font-medium">This notice has expired</span>
                            </div>
                        </div>
                    @elseif($notice->isActive())
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-green-700 font-medium">This notice is currently active</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-3">
                            @if(!$notice->is_pinned)
                                <form action="{{ route('notices.pin', $notice) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                        Pin Notice
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('notices.unpin', $notice) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                        Unpin Notice
                                    </button>
                                </form>
                            @endif

                            @if($notice->status !== 'archived')
                                <form action="{{ route('notices.archive', $notice) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition"
                                            onclick="return confirm('Are you sure you want to archive this notice?')">
                                        Archive Notice
                                    </button>
                                </form>
                            @endif
                        </div>

                        <form action="{{ route('notices.destroy', $notice) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this notice? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                Delete Notice
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
