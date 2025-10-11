@extends('layouts.admin')

@section('title', 'Take Attendance')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Take Attendance</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Mark student attendance for today</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <form action="{{ route('teacher.attendance.store') }}" method="POST">
            @csrf

            <!-- Class & Date Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Class *</label>
                    <select name="class_id" id="class_id" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Choose a class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} - {{ $class->section->name ?? '' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date *</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <!-- Students List -->
            <div id="students-list" class="hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 mb-4 pb-2">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Students</h3>
                </div>

                <div class="space-y-3">
                    @if(isset($students) && count($students) > 0)
                        @foreach($students as $student)
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-750">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 font-semibold">
                                    {{ substr($student->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">{{ $student->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">ID: {{ $student->admission_number }}</p>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="present" 
                                           class="mr-2" checked>
                                    <span class="text-green-600 dark:text-green-400 font-medium">Present</span>
                                </label>
                                <label class="flex items-center cursor-pointer ml-4">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="absent" 
                                           class="mr-2">
                                    <span class="text-red-600 dark:text-red-400 font-medium">Absent</span>
                                </label>
                                <label class="flex items-center cursor-pointer ml-4">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="late" 
                                           class="mr-2">
                                    <span class="text-yellow-600 dark:text-yellow-400 font-medium">Late</span>
                                </label>
                                <label class="flex items-center cursor-pointer ml-4">
                                    <input type="radio" name="attendance[{{ $student->id }}]" value="excused" 
                                           class="mr-2">
                                    <span class="text-blue-600 dark:text-blue-400 font-medium">Excused</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Select a class to view students</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex gap-3">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                    Save Attendance
                </button>
                <a href="{{ route('teacher.attendance.index') }}" 
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const studentsList = document.getElementById('students-list');
    
    @if(isset($students) && count($students) > 0)
        studentsList.classList.remove('hidden');
    @endif
    
    classSelect.addEventListener('change', function() {
        if (this.value) {
            // Reload page with class_id parameter to fetch students
            window.location.href = '{{ route("teacher.attendance.take") }}?class_id=' + this.value;
        }
    });
});
</script>
@endpush
@endsection
