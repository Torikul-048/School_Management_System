@extends('layouts.admin')

@section('title', 'Assignments')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Assignments</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Manage student assignments</p>
        </div>
        <a href="{{ route('teacher.assignments.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            Create Assignment
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assignments ?? [] as $assignment)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $assignment->title }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $assignment->subject->name }} - {{ $assignment->class->name }}</p>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</span>
                <a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">View →</a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-500">No assignments created yet</div>
        @endforelse
    </div>
</div>
@endsection
