@extends('layouts.app')

@section('title', 'Auto Generate Timetable')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('timetable.index') }}" class="hover:text-blue-600">Timetable</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 dark:text-gray-200">Auto Generate</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Auto Generate Timetable</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Automatically create timetable with conflict detection</p>
        </div>
        <a href="{{ route('timetable.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back</span>
        </a>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">How Auto Generation Works</h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Automatically schedules all subjects for selected class and section</li>
                        <li>Distributes periods evenly across working days</li>
                        <li>Prevents teacher conflicts (same teacher, multiple classes at same time)</li>
                        <li>Prevents class conflicts (same class, multiple subjects at same time)</li>
                        <li>Configurable working hours and period duration</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Generation Configuration</h2>
        </div>
        
        <div class="p-6">
            <form action="{{ route('timetable.auto-generate.store') }}" method="POST" id="generateForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Class Selection -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Class <span class="text-red-500">*</span>
                        </label>
                        <select name="class_id" id="class_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required onchange="loadSections(this.value)">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section Selection -->
                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Section
                        </label>
                        <select name="section_id" id="section_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Section (Optional)</option>
                        </select>
                        @error('section_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Academic Year -->
                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Academic Year <span class="text-red-500">*</span>
                        </label>
                        <select name="academic_year_id" id="academic_year_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                            <option value="">Select Academic Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $year->is_current ? 'selected' : '' }}>
                                    {{ $year->year_name }} {{ $year->is_current ? '(Current)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Periods Per Day -->
                    <div>
                        <label for="periods_per_day" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Periods Per Day <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="periods_per_day" id="periods_per_day" value="6" min="4" max="10"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                        @error('periods_per_day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Number of class periods each day</p>
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            School Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" value="08:00"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Period Duration -->
                    <div>
                        <label for="period_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Period Duration (minutes) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="period_duration" id="period_duration" value="45" min="30" max="90"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                            required>
                        @error('period_duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Duration of each class period</p>
                    </div>

                    <!-- Break After Period -->
                    <div>
                        <label for="break_after_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Break After Period #
                        </label>
                        <input type="number" name="break_after_period" id="break_after_period" value="3" min="0" max="10"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('break_after_period')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Add break after this period (e.g., 3 = break after 3rd period)</p>
                    </div>

                    <!-- Break Duration -->
                    <div>
                        <label for="break_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Break Duration (minutes)
                        </label>
                        <input type="number" name="break_duration" id="break_duration" value="15" min="5" max="30"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('break_duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Length of break time</p>
                    </div>
                </div>

                <!-- Warning -->
                <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Warning</h3>
                            <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-400">
                                Generating a new timetable will <strong>delete all existing periods</strong> for the selected class and section. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('timetable.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        Cancel
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2"
                        onclick="return confirm('This will delete existing timetable for the selected class/section. Continue?')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Generate Timetable</span>
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
