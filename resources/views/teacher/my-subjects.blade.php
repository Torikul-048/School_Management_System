@extends('layouts.admin')

@section('title', 'My Subjects')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Subjects</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Subjects you are teaching</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($subjects as $subjectData)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                {{ $subjectData['subject']->name }}
            </h3>
            
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Teaching in:</p>
            <div class="space-y-2">
                @foreach($subjectData['classes'] as $class)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <span class="text-gray-800 dark:text-white font-medium">{{ $class->name }}</span>
                    <a href="{{ route('teacher.students') }}?class_id={{ $class->id }}" 
                       class="text-sm text-blue-600 hover:text-blue-700">
                        View Students
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-gray-600 dark:text-gray-400">No subjects assigned yet</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
