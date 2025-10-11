@extends('layouts.admin')

@section('title', 'Marks & Grades')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Marks & Grades</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Manage student marks and grades</p>
        </div>
    </div>

    <!-- Exams List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($exams as $exam)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $exam->name }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $exam->exam_type }}</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                    @if($exam->status === 'upcoming') bg-blue-100 text-blue-800
                    @elseif($exam->status === 'ongoing') bg-yellow-100 text-yellow-800
                    @else bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst($exam->status) }}
                </span>
            </div>

            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Total Marks: {{ $exam->total_marks }}</span>
                </div>
            </div>

            <!-- My Subjects for this Exam -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">My Subjects</p>
                @php
                    $mySubjects = \App\Models\SubjectAssignment::where('teacher_id', auth()->user()->teacher->id)
                        ->with('subject')
                        ->get();
                @endphp
                <div class="space-y-2">
                    @foreach($mySubjects as $assignment)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-gray-300">{{ $assignment->subject->name }}</span>
                        <a href="{{ route('teacher.marks.enter', ['exam_id' => $exam->id, 'subject_id' => $assignment->subject_id, 'class_id' => $assignment->class_id]) }}" 
                           class="text-blue-600 hover:text-blue-700 font-medium">
                            Enter Marks →
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Exams Available</h3>
            <p class="text-gray-600 dark:text-gray-400">There are no exams scheduled at this time</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
