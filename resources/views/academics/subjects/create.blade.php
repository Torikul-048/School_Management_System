@extends('layouts.admin')

@section('title', 'Create Subject')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('subjects.index') }}" class="mr-4">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Subject</h1>
            <p class="text-gray-600 mt-1">Add a new subject to the system</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Subject Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="e.g., MATH101"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class <span class="text-red-500">*</span></label>
                    <select name="class_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('class_id') border-red-500 @enderror">
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

                <!-- Teacher -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign Teacher</label>
                    <select name="teacher_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject Type <span class="text-red-500">*</span></label>
                    <select name="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="core" {{ old('type') == 'core' ? 'selected' : '' }}>Core (Mandatory)</option>
                        <option value="elective" {{ old('type') == 'elective' ? 'selected' : '' }}>Elective</option>
                        <option value="optional" {{ old('type') == 'optional' ? 'selected' : '' }}>Optional</option>
                    </select>
                </div>

                <!-- Credits -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Credits</label>
                    <input type="number" name="credits" value="{{ old('credits') }}" min="1" max="10"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Pass Marks -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pass Marks</label>
                    <input type="number" name="pass_marks" value="{{ old('pass_marks') }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Full Marks -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Marks</label>
                    <input type="number" name="full_marks" value="{{ old('full_marks') }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('subjects.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg">
                    Create Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
