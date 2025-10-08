@extends('layouts.app')

@section('title', 'Subject Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('subjects.index') }}" class="hover:text-blue-600">Subjects</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 dark:text-gray-200">{{ $subject->name }}</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $subject->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Subject Code: {{ $subject->code }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('subjects.edit', $subject) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit</span>
            </a>
            <a href="{{ route('subjects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Basic Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Subject Name</label>
                            <p class="text-lg text-gray-900 dark:text-white font-semibold">{{ $subject->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Subject Code</label>
                            <p class="text-lg text-gray-900 dark:text-white font-semibold">{{ $subject->code }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Class</label>
                            <p class="text-lg text-gray-900 dark:text-white font-semibold">{{ $subject->class->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Subject Type</label>
                            <p class="text-lg">
                                @if($subject->type == 'core')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">Core Subject</span>
                                @elseif($subject->type == 'elective')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Elective</span>
                                @else
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded-full">Optional</span>
                                @endif
                            </p>
                        </div>
                        @if($subject->teacher)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Assigned Teacher</label>
                            <p class="text-lg text-gray-900 dark:text-white font-semibold">
                                {{ $subject->teacher->first_name }} {{ $subject->teacher->last_name }}
                            </p>
                        </div>
                        @endif
                        @if($subject->credits)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Credits</label>
                            <p class="text-lg text-gray-900 dark:text-white font-semibold">{{ $subject->credits }}</p>
                        </div>
                        @endif
                    </div>

                    @if($subject->description)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</label>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $subject->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Marks Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Marks Configuration</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center p-6 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Pass Marks</p>
                            <p class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $subject->pass_marks }}</p>
                        </div>
                        <div class="text-center p-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Full Marks</p>
                            <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $subject->full_marks }}</p>
                        </div>
                    </div>
                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Students need to score at least <span class="font-bold text-gray-900 dark:text-white">{{ $subject->pass_marks }}</span> out of 
                            <span class="font-bold text-gray-900 dark:text-white">{{ $subject->full_marks }}</span> marks 
                            ({{ number_format(($subject->pass_marks / $subject->full_marks) * 100, 1) }}%) to pass this subject.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Quick Stats</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Total Students</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $subject->class->students_count ?? 0 }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Total Sections</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $subject->class->sections_count ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('subjects.edit', $subject) }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Edit Subject</span>
                    </a>
                    <a href="{{ route('timetable.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">View Timetable</span>
                    </a>
                    <form action="{{ route('subjects.destroy', $subject) }}" method="POST" 
                        onsubmit="return confirm('Are you sure you want to delete this subject? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors w-full text-left">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span class="text-red-600">Delete Subject</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Meta Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Metadata</h2>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Created:</span>
                        <span class="text-gray-900 dark:text-white ml-2">{{ $subject->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Last Updated:</span>
                        <span class="text-gray-900 dark:text-white ml-2">{{ $subject->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
