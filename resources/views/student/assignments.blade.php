@extends('layouts.admin')

@section('title', 'Assignments')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Assignments</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View and download your assignments</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($assignments as $assignment)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $assignment->title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $assignment->description }}</p>
                
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p>Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</p>
                    </div>
                    @if($assignment->file_path)
                        <a href="{{ route('student.assignments.download', $assignment->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                            Download
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <p class="text-gray-600 dark:text-gray-400">No assignments available</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $assignments->links() }}
    </div>
</div>
@endsection
