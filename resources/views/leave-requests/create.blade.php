@extends('layouts.app')

@section('title', 'New Leave Request')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('leave-requests.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">New Leave Request</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Submit a new leave request</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 space-y-6">
                    <!-- Leave Period -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required min="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required min="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reason for Leave <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason" id="reason" rows="5" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('reason') border-red-500 @enderror" placeholder="Please provide a detailed reason for your leave request...">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Minimum 20 characters required.</p>
                    </div>

                    <!-- Supporting Document -->
                    <div>
                        <label for="document" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Supporting Document (Optional)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-gray-400 dark:hover:border-gray-500 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="document" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="document" name="document" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" onchange="updateFileName(this)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF, PNG, JPG up to 2MB</p>
                                <p id="file-name" class="text-sm text-gray-700 dark:text-gray-300 font-medium mt-2"></p>
                            </div>
                        </div>
                        @error('document')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <label for="emergency_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Emergency Contact Number
                        </label>
                        <input type="tel" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('emergency_contact') border-red-500 @enderror" placeholder="+1234567890">
                        @error('emergency_contact')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Contact number where you can be reached during leave.</p>
                    </div>

                    <!-- Terms -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Important Information</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Leave requests must be submitted at least 2 days in advance.</li>
                                        <li>Approval is subject to review by your teacher/admin.</li>
                                        <li>You will be notified once your request is processed.</li>
                                        <li>Medical leaves may require supporting documentation.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('leave-requests.index') }}" class="px-6 py-3 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-700 focus:outline-none transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameDisplay = document.getElementById('file-name');
    if (fileName) {
        fileNameDisplay.textContent = 'Selected: ' + fileName;
    } else {
        fileNameDisplay.textContent = '';
    }
}

// Update minimum end date based on start date
document.getElementById('start_date').addEventListener('change', function() {
    document.getElementById('end_date').min = this.value;
    if (document.getElementById('end_date').value < this.value) {
        document.getElementById('end_date').value = this.value;
    }
});
</script>
@endsection
