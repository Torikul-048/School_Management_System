@extends('layouts.admin')

@section('title', 'Create Notice')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create New Notice</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Add a new notice to the notice board</p>
        </div>
        <a href="{{ route('notices.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Notices
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <form action="{{ route('notices.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notice Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Content <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" 
                              id="content" 
                              rows="8"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('content') border-red-500 @enderror"
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Notice Type -->
                <div>
                    <label for="notice_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notice Type <span class="text-red-500">*</span>
                    </label>
                    <select name="notice_type" 
                            id="notice_type"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('notice_type') border-red-500 @enderror"
                            required>
                        <option value="">Select Type</option>
                        <option value="general" {{ old('notice_type') === 'general' ? 'selected' : '' }}>General</option>
                        <option value="urgent" {{ old('notice_type') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="academic" {{ old('notice_type') === 'academic' ? 'selected' : '' }}>Academic</option>
                        <option value="exam" {{ old('notice_type') === 'exam' ? 'selected' : '' }}>Exam</option>
                        <option value="fee" {{ old('notice_type') === 'fee' ? 'selected' : '' }}>Fee</option>
                        <option value="holiday" {{ old('notice_type') === 'holiday' ? 'selected' : '' }}>Holiday</option>
                        <option value="event" {{ old('notice_type') === 'event' ? 'selected' : '' }}>Event</option>
                        <option value="other" {{ old('notice_type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('notice_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" 
                            id="priority"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('priority') border-red-500 @enderror"
                            required>
                        <option value="">Select Priority</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="normal" {{ old('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Publish Date -->
                <div>
                    <label for="publish_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Publish Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="publish_date" 
                           id="publish_date" 
                           value="{{ old('publish_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('publish_date') border-red-500 @enderror"
                           required>
                    @error('publish_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expiry Date -->
                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Expiry Date (Optional)
                    </label>
                    <input type="date" 
                           name="expiry_date" 
                           id="expiry_date" 
                           value="{{ old('expiry_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('expiry_date') border-red-500 @enderror">
                    @error('expiry_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank for no expiry</p>
                </div>
            </div>

            <!-- Target Audience -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Target Audience <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="target_audience[roles][]" value="Admin" 
                               {{ in_array('Admin', old('target_audience.roles', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Admin</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="target_audience[roles][]" value="Teacher" 
                               {{ in_array('Teacher', old('target_audience.roles', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Teachers</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="target_audience[roles][]" value="Student" 
                               {{ in_array('Student', old('target_audience.roles', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Students</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="target_audience[roles][]" value="Parent" 
                               {{ in_array('Parent', old('target_audience.roles', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Parents</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="target_audience[roles][]" value="Accountant" 
                               {{ in_array('Accountant', old('target_audience.roles', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Accountant</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="target_audience[roles][]" value="Librarian" 
                               {{ in_array('Librarian', old('target_audience.roles', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Librarian</span>
                    </label>
                </div>
                @error('target_audience')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attachment -->
            <div class="mt-6">
                <label for="attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Attachment (Optional)
                </label>
                <input type="file" 
                       name="attachment" 
                       id="attachment"
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-blue-400 @error('attachment') border-red-500 @enderror">
                @error('attachment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max size: 10MB. Allowed: PDF, DOC, DOCX, JPG, PNG</p>
            </div>

            <!-- Status -->
            <div class="mt-6">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" 
                        id="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('status') border-red-500 @enderror"
                        required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notification Options -->
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Notification Options</h3>
                <div class="space-y-3">
                    <label class="inline-flex items-center">
                        <input type="checkbox" 
                               name="send_email" 
                               value="1"
                               {{ old('send_email') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send Email Notification</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" 
                               name="send_sms" 
                               value="1"
                               {{ old('send_sms') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send SMS Notification</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" 
                               name="is_pinned" 
                               value="1"
                               {{ old('is_pinned') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pin this notice at the top</span>
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 mt-6">
                <a href="{{ route('notices.index') }}" 
                   class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Create Notice
                </button>
            </div>
        </form>
    </div>
@endsection
