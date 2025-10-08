@extends('layouts.app')

@section('title', 'Edit Attendance')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('attendance.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Attendance</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update attendance record</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Student Info Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Student Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->student->first_name }} {{ $attendance->student->last_name }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Admission Number</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->student->admission_number }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $attendance->class->name }} - {{ $attendance->class->section }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Attendance Details</h3>
                    
                    <!-- Status -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Status <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="relative flex flex-col items-center p-4 cursor-pointer border-2 rounded-lg transition {{ old('status', $attendance->status) == 'present' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400' }}">
                                <input type="radio" name="status" value="present" {{ old('status', $attendance->status) == 'present' ? 'checked' : '' }} class="sr-only peer" required>
                                <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Present</span>
                            </label>

                            <label class="relative flex flex-col items-center p-4 cursor-pointer border-2 rounded-lg transition {{ old('status', $attendance->status) == 'absent' ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400' }}">
                                <input type="radio" name="status" value="absent" {{ old('status', $attendance->status) == 'absent' ? 'checked' : '' }} class="sr-only peer" required>
                                <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Absent</span>
                            </label>

                            <label class="relative flex flex-col items-center p-4 cursor-pointer border-2 rounded-lg transition {{ old('status', $attendance->status) == 'late' ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400' }}">
                                <input type="radio" name="status" value="late" {{ old('status', $attendance->status) == 'late' ? 'checked' : '' }} class="sr-only peer" required>
                                <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Late</span>
                            </label>

                            <label class="relative flex flex-col items-center p-4 cursor-pointer border-2 rounded-lg transition {{ old('status', $attendance->status) == 'excused' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400' }}">
                                <input type="radio" name="status" value="excused" {{ old('status', $attendance->status) == 'excused' ? 'checked' : '' }} class="sr-only peer" required>
                                <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Excused</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="4" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('remarks') border-red-500 @enderror" placeholder="Enter any additional remarks or notes...">{{ old('remarks', $attendance->remarks) }}</textarea>
                        @error('remarks')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('attendance.index') }}" class="px-6 py-3 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-700 focus:outline-none transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Update Attendance
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
