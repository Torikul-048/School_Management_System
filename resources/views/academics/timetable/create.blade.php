@extends('layouts.app')

@section('title', 'Add Period')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('timetable.index') }}" class="hover:text-blue-600">Timetable</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 dark:text-gray-200">Add Period</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Period</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Schedule a new class period</p>
        </div>
        <a href="{{ route('timetable.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <form action="{{ route('timetable.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Class -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Class <span class="text-red-500">*</span>
                        </label>
                        <select name="class_id" id="class_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required onchange="loadSections(this.value)">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section -->
                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Section <span class="text-red-500">*</span>
                        </label>
                        <select name="section_id" id="section_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="">Select Section</option>
                        </select>
                        @error('section_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <select name="subject_id" id="subject_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teacher -->
                    <div>
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Teacher <span class="text-red-500">*</span>
                        </label>
                        <select name="teacher_id" id="teacher_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Day of Week -->
                    <div>
                        <label for="day_of_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Day <span class="text-red-500">*</span>
                        </label>
                        <select name="day_of_week" id="day_of_week"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="">Select Day</option>
                            <option value="Monday" {{ old('day_of_week') == 'Monday' ? 'selected' : '' }}>Monday</option>
                            <option value="Tuesday" {{ old('day_of_week') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="Wednesday" {{ old('day_of_week') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="Thursday" {{ old('day_of_week') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                            <option value="Friday" {{ old('day_of_week') == 'Friday' ? 'selected' : '' }}>Friday</option>
                            <option value="Saturday" {{ old('day_of_week') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                        </select>
                        @error('day_of_week')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Room Number -->
                    <div>
                        <label for="room_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Room Number
                        </label>
                        <input type="text" name="room_number" id="room_number" value="{{ old('room_number') }}" placeholder="e.g., Room 101"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('room_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('timetable.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Add Period
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function loadSections(classId) {
    const sectionSelect = document.getElementById('section_id');
    sectionSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (!classId) {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        return;
    }
    
    fetch(`/api/classes/${classId}/sections`)
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            data.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                sectionSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading sections:', error);
            sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
        });
}
</script>
@endpush
@endsection
