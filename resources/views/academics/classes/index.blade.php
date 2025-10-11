@extends('layouts.admin')

@section('title', 'Classes & Sections')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Classes & Sections</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage classes, grades and sections</p>
        </div>
        <a href="{{ route('classes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Class
        </a>
    </div>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $class)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                <!-- Class Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h3 class="text-2xl font-bold text-white">{{ $class->name }}</h3>
                    <p class="text-blue-100 text-sm mt-1">Grade: {{ $class->grade_level }}</p>
                </div>

                <!-- Class Info -->
                <div class="p-6 space-y-4">
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $class->sections->count() }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Sections</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg text-center">
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $class->students_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Students</p>
                        </div>
                    </div>

                    <!-- Capacity -->
                    @if($class->capacity)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Capacity:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $class->students_count ?? 0 }}/{{ $class->capacity }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min((($class->students_count ?? 0) / $class->capacity) * 100, 100) }}%"></div>
                    </div>
                    @endif

                    <!-- Class Teacher -->
                    @if($class->class_teacher_id)
                    <div class="flex items-center space-x-2 text-sm">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-400">Class Teacher:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $class->classTeacher->first_name ?? 'Not Assigned' }}</span>
                    </div>
                    @endif

                    <!-- Sections List -->
                    @if($class->sections->count() > 0)
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Sections:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($class->sections as $section)
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold rounded-full">
                                    {{ $section->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex space-x-2 pt-4">
                        <a href="{{ route('classes.show', $class) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center text-sm font-medium transition-colors">
                            View Details
                        </a>
                        <a href="{{ route('classes.edit', $class) }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center text-sm font-medium transition-colors">
                            Edit
                        </a>
                    </div>

                    <!-- Manage Sections Button -->
                    <a href="{{ route('classes.manage-sections', $class) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-center text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Manage Sections
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">No Classes Found</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Get started by creating your first class.</p>
                    <div class="mt-6">
                        <a href="{{ route('classes.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add New Class
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($classes->hasPages())
        <div class="mt-8">
            {{ $classes->links() }}
        </div>
    @endif
    </div>
@endsection
