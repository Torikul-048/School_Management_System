@extends('layouts.admin')

@section('title', 'Notice Board Management')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Notice Board Management</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage all school notices and announcements</p>
        </div>
        <a href="{{ route('notices.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Notice
        </a>
    </div>

    <div class="space-y-6">
        <!-- Pinned Notices -->
        @if($pinnedNotices->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 border-b border-yellow-200 dark:border-yellow-700">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Pinned Notices</h3>
                    </div>
                </div>
                <div class="p-6">
                    @foreach($pinnedNotices as $notice)
                        <div class="mb-4 last:mb-0 p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $notice->title }}</h4>
                                    <div class="flex items-center space-x-3 mt-2">
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
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $typeColors[$notice->notice_type] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($notice->notice_type) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$notice->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($notice->priority) }} Priority
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $notice->publish_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <a href="{{ route('notices.show', $notice) }}" 
                                       class="text-blue-600 hover:text-blue-800" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('notices.edit', $notice) }}" 
                                       class="text-green-600 hover:text-green-800" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('notices.unpin', $notice) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="Unpin">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ route('notices.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notice Type</label>
                        <select name="type" id="type" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Types</option>
                            <option value="general" {{ request('type') === 'general' ? 'selected' : '' }}>General</option>
                            <option value="urgent" {{ request('type') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            <option value="academic" {{ request('type') === 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="exam" {{ request('type') === 'exam' ? 'selected' : '' }}>Exam</option>
                            <option value="fee" {{ request('type') === 'fee' ? 'selected' : '' }}>Fee</option>
                            <option value="holiday" {{ request('type') === 'holiday' ? 'selected' : '' }}>Holiday</option>
                            <option value="event" {{ request('type') === 'event' ? 'selected' : '' }}>Event</option>
                            <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                        <select name="priority" id="priority" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Published (Active)</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Filter
                        </button>
                        <a href="{{ route('notices.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-4 rounded-lg transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notices List -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                @if($notices->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Priority
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Publish Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($notices as $notice)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($notice->is_pinned)
                                                    <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                                                    </svg>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ Str::limit($notice->title, 50) }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        By {{ $notice->creator->name ?? 'Unknown' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeColors[$notice->notice_type] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($notice->notice_type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'bg-gray-100 text-gray-600',
                                                        'normal' => 'bg-blue-100 text-blue-600',
                                                        'high' => 'bg-orange-100 text-orange-600',
                                                        'urgent' => 'bg-red-100 text-red-600',
                                                    ];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $priorityColors[$notice->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                                    {{ ucfirst($notice->priority) }}
                                                </span>
                                            </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $notice->publish_date->format('M d, Y') }}
                                        </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'draft' => 'bg-yellow-100 text-yellow-800',
                                                        'published' => 'bg-green-100 text-green-800',
                                                        'expired' => 'bg-gray-100 text-gray-800',
                                                        'archived' => 'bg-red-100 text-red-800',
                                                    ];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$notice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($notice->status) }}
                                                </span>
                                            </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <a href="{{ route('notices.show', $notice) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('notices.edit', $notice) }}" 
                                                   class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                @if(!$notice->is_pinned)
                                                    <form action="{{ route('notices.pin', $notice) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Pin Notice">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('notices.destroy', $notice) }}" method="POST" 
                                                      class="inline" onsubmit="return confirm('Are you sure you want to delete this notice?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $notices->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No notices found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new notice.</p>
                        <div class="mt-6">
                            <a href="{{ route('notices.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add New Notice
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
