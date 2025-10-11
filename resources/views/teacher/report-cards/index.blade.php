@extends('layouts.admin')

@section('title', 'Report Cards')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Report Cards</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Generate student report cards</p>
        </div>
        <a href="{{ route('teacher.report-cards.generate') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            Generate Report Card
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($exams as $exam)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">{{ $exam->name }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $exam->exam_type }}</p>
            <a href="{{ route('teacher.report-cards.generate') }}?exam_id={{ $exam->id }}" 
               class="text-blue-600 hover:text-blue-700 font-medium">
                View Report Cards →
            </a>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 text-gray-500">
            No exams available for report card generation
        </div>
        @endforelse
    </div>
</div>
@endsection
