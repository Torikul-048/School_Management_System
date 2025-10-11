@extends('layouts.admin')

@section('title', 'Report Cards')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Student Report Cards</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">View and download student report cards</p>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('marks.report-card') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="exam_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Exam <span class="text-red-500">*</span></label>
                        <select name="exam_id" id="exam_id" required onchange="this.form.submit()" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Exam</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(request('exam_id'))
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class</label>
                        <select name="class_id" id="class_id" onchange="this.form.submit()" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }} - {{ $class->section }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Student</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name or admission number" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            Search
                        </button>
                        <a href="{{ route('marks.report-card') }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-700 focus:outline-none transition">
                            Reset
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Students Grid -->
        @if(isset($students) && $students->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($students as $student)
                @php
                    $marks = $student->marks->where('exam_id', request('exam_id'));
                    $totalMarks = $marks->sum('marks_obtained');
                    $totalPossible = $marks->sum(function($mark) {
                        return $mark->examSchedule->total_marks ?? 100;
                    });
                    $percentage = $totalPossible > 0 ? ($totalMarks / $totalPossible) * 100 : 0;
                @endphp
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <!-- Student Header -->
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->admission_number }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->class->name }} - {{ $student->class->section }}</p>
                            </div>
                        </div>

                        <!-- Performance Summary -->
                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Overall Performance</span>
                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ number_format($percentage, 2) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="mt-2 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>Subjects: {{ $marks->count() }}</span>
                                <span>Total: {{ number_format($totalMarks, 2) }}/{{ $totalPossible }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <a href="{{ route('marks.download-report-card', ['exam_id' => request('exam_id'), 'student_id' => $student->id]) }}" class="flex-1 text-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download PDF
                            </a>
                            <button onclick="viewDetails({{ $student->id }})" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($students->hasPages())
            <div class="mt-6">
                {{ $students->links() }}
            </div>
        @endif
        @elseif(request('exam_id'))
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">No students found with the selected filters.</p>
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Please select an exam to view report cards.</p>
        </div>
        @endif
    </div>
</div>
@endsection
