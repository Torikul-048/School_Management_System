@extends('layouts.admin')

@section('title', 'Mark Attendance')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('attendance.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Mark Attendance</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Mark daily attendance for students</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Form -->
        <form action="{{ route('attendance.store') }}" method="POST" x-data="{ selectAll: false }">
            @csrf

            <!-- Date and Class Selection -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="date" id="date" value="{{ old('date', request('date', date('Y-m-d'))) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('date') border-red-500 @enderror">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class <span class="text-red-500">*</span></label>
                            <select name="class_id" id="class_id" required onchange="this.form.submit()" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('class_id') border-red-500 @enderror">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', request('class_id')) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }} - {{ $class->section }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($students) && $students->count() > 0)
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Students ({{ $students->count() }})
                        </h3>
                        <div class="flex space-x-2">
                            <button type="button" @click="document.querySelectorAll('input[value=\\'present\\']').forEach(el => el.checked = true)" class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs font-medium rounded hover:bg-green-200 dark:hover:bg-green-800 transition">
                                Mark All Present
                            </button>
                            <button type="button" @click="document.querySelectorAll('input[value=\\'absent\\']').forEach(el => el.checked = true)" class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium rounded hover:bg-red-200 dark:hover:bg-red-800 transition">
                                Mark All Absent
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Admission No.</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($students as $student)
                                @php
                                    $existingAttendance = isset($existingAttendances) ? $existingAttendances->where('student_id', $student->id)->first() : null;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $student->first_name }} {{ $student->last_name }}
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="students[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $student->admission_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="students[{{ $student->id }}][status]" value="present" {{ $existingAttendance && $existingAttendance->status == 'present' ? 'checked' : '' }} class="form-radio h-4 w-4 text-green-600 focus:ring-green-500">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Present</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="students[{{ $student->id }}][status]" value="absent" {{ $existingAttendance && $existingAttendance->status == 'absent' ? 'checked' : '' }} class="form-radio h-4 w-4 text-red-600 focus:ring-red-500">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Absent</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="students[{{ $student->id }}][status]" value="late" {{ $existingAttendance && $existingAttendance->status == 'late' ? 'checked' : '' }} class="form-radio h-4 w-4 text-yellow-600 focus:ring-yellow-500">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Late</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="students[{{ $student->id }}][status]" value="excused" {{ $existingAttendance && $existingAttendance->status == 'excused' ? 'checked' : '' }} class="form-radio h-4 w-4 text-blue-600 focus:ring-blue-500">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Excused</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="students[{{ $student->id }}][remarks]" value="{{ $existingAttendance ? $existingAttendance->remarks : '' }}" placeholder="Optional remarks" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('attendance.index') }}" class="px-6 py-3 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-sm text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-700 focus:outline-none transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    Save Attendance
                </button>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Please select a date and class to mark attendance.</p>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
