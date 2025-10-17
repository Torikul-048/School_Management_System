<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Notice') }}
            </h2>
            <a href="{{ route('notices.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('notices.update', $notice) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Notice Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title', $notice->title) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="8"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('content') border-red-500 @enderror"
                                      required>{{ old('content', $notice->content) }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Notice Type -->
                            <div>
                                <label for="notice_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Notice Type <span class="text-red-500">*</span>
                                </label>
                                <select name="notice_type" 
                                        id="notice_type"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notice_type') border-red-500 @enderror"
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="general" {{ old('notice_type', $notice->notice_type) === 'general' ? 'selected' : '' }}>General</option>
                                    <option value="urgent" {{ old('notice_type', $notice->notice_type) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    <option value="academic" {{ old('notice_type', $notice->notice_type) === 'academic' ? 'selected' : '' }}>Academic</option>
                                    <option value="exam" {{ old('notice_type', $notice->notice_type) === 'exam' ? 'selected' : '' }}>Exam</option>
                                    <option value="fee" {{ old('notice_type', $notice->notice_type) === 'fee' ? 'selected' : '' }}>Fee</option>
                                    <option value="holiday" {{ old('notice_type', $notice->notice_type) === 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    <option value="event" {{ old('notice_type', $notice->notice_type) === 'event' ? 'selected' : '' }}>Event</option>
                                    <option value="other" {{ old('notice_type', $notice->notice_type) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('notice_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <select name="priority" 
                                        id="priority"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('priority') border-red-500 @enderror"
                                        required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority', $notice->priority) === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="normal" {{ old('priority', $notice->priority) === 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="high" {{ old('priority', $notice->priority) === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority', $notice->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Publish Date -->
                            <div>
                                <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Publish Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="publish_date" 
                                       id="publish_date" 
                                       value="{{ old('publish_date', $notice->publish_date?->format('Y-m-d')) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('publish_date') border-red-500 @enderror"
                                       required>
                                @error('publish_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Expiry Date -->
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Expiry Date (Optional)
                                </label>
                                <input type="date" 
                                       name="expiry_date" 
                                       id="expiry_date" 
                                       value="{{ old('expiry_date', $notice->expiry_date?->format('Y-m-d')) }}"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('expiry_date') border-red-500 @enderror">
                                @error('expiry_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Leave blank for no expiry</p>
                            </div>
                        </div>

                        <!-- Target Audience -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Target Audience <span class="text-red-500">*</span>
                            </label>
                            @php
                                $targetRoles = old('target_audience.roles', $notice->target_audience['roles'] ?? []);
                            @endphp
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="target_audience[roles][]" value="Admin" 
                                           {{ in_array('Admin', $targetRoles) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Admin</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="target_audience[roles][]" value="Teacher" 
                                           {{ in_array('Teacher', $targetRoles) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Teachers</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="target_audience[roles][]" value="Student" 
                                           {{ in_array('Student', $targetRoles) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Students</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="target_audience[roles][]" value="Parent" 
                                           {{ in_array('Parent', $targetRoles) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Parents</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="target_audience[roles][]" value="Accountant" 
                                           {{ in_array('Accountant', $targetRoles) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Accountant</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="target_audience[roles][]" value="Librarian" 
                                           {{ in_array('Librarian', $targetRoles) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Librarian</span>
                                </label>
                            </div>
                            @error('target_audience')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Attachment -->
                        @if($notice->attachment)
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Attachment:</p>
                                <div class="flex items-center justify-between">
                                    <a href="{{ Storage::url($notice->attachment) }}" 
                                       target="_blank"
                                       class="text-indigo-600 hover:text-indigo-800 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ basename($notice->attachment) }}
                                    </a>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Upload a new file to replace this attachment</p>
                            </div>
                        @endif

                        <!-- Attachment -->
                        <div class="mb-6">
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                                Attachment {{ $notice->attachment ? '(Replace)' : '(Optional)' }}
                            </label>
                            <input type="file" 
                                   name="attachment" 
                                   id="attachment"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('attachment') border-red-500 @enderror">
                            @error('attachment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Max size: 10MB. Allowed: PDF, DOC, DOCX, JPG, PNG</p>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror"
                                    required>
                                <option value="draft" {{ old('status', $notice->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $notice->status) === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="expired" {{ old('status', $notice->status) === 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="archived" {{ old('status', $notice->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notification Options -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Notification Options</h3>
                            <div class="space-y-3">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="send_email" 
                                           value="1"
                                           {{ old('send_email', $notice->send_email) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Send Email Notification on Update</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="send_sms" 
                                           value="1"
                                           {{ old('send_sms', $notice->send_sms) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Send SMS Notification on Update</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           name="is_pinned" 
                                           value="1"
                                           {{ old('is_pinned', $notice->is_pinned) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Pin this notice at the top</span>
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('notices.index') }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-lg transition duration-150 ease-in-out">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition duration-150 ease-in-out">
                                Update Notice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
