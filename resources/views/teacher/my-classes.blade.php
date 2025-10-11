@extends('layouts.admin')

@section('title', 'My Classes')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Classes</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Classes assigned to you</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes as $classData)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $classData['class']->name }}</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                    {{ $classData['student_count'] }} Students
                </span>
            </div>
            
            <div class="space-y-2">
                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Subjects:</p>
                @foreach($classData['subjects'] as $subject)
                <span class="inline-block px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded">
                    {{ $subject->name }}
                </span>
                @endforeach
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('teacher.students') }}?class_id={{ $classData['class']->id }}" 
                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    View Students →
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p class="text-gray-600 dark:text-gray-400">No classes assigned yet</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
