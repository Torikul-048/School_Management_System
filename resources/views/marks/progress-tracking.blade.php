@extends('layouts.app')

@section('title', 'Progress Tracking')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Student Progress Tracking</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track student performance across multiple exams</p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('marks.progress-tracking') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student <span class="text-red-500">*</span></label>
                        <select name="student_id" id="student_id" required onchange="this.form.submit()" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }} ({{ $student->admission_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(request('student_id'))
                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Academic Year</label>
                        <select name="academic_year_id" id="academic_year_id" onchange="this.form.submit()" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <a href="{{ route('marks.progress-tracking') }}" class="w-full px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-700 focus:outline-none transition text-center">
                            Reset
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        @if(isset($progressData) && count($progressData) > 0)
        <!-- Student Info Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 h-20 w-20">
                        <div class="h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($selectedStudent->first_name, 0, 1) }}{{ substr($selectedStudent->last_name, 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedStudent->admission_number }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedStudent->class->name }} - {{ $selectedStudent->class->section }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            @php
                $totalExams = count($progressData);
                $totalPercentage = 0;
                $passCount = 0;
                $failCount = 0;
                foreach($progressData as $data) {
                    $totalPercentage += $data['percentage'];
                    if($data['percentage'] >= 33) $passCount++;
                    else $failCount++;
                }
                $avgPercentage = $totalExams > 0 ? $totalPercentage / $totalExams : 0;
            @endphp
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Exams</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $totalExams }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Avg Percentage</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($avgPercentage, 2) }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Passed</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $passCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Failed</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $failCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Timeline -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Performance Timeline</h3>
                <div class="space-y-6">
                    @foreach($progressData as $data)
                        <div class="relative pl-8 pb-8 border-l-2 border-gray-200 dark:border-gray-700 last:border-l-0 last:pb-0">
                            <div class="absolute left-0 top-0 -ml-2 w-4 h-4 rounded-full bg-blue-600 border-2 border-white dark:border-gray-800"></div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">{{ $data['exam']->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $data['exam']->academicYear->year }} - {{ ucfirst($data['exam']->type) }}</p>
                                    </div>
                                    <span class="text-2xl font-bold {{ $data['percentage'] >= 33 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($data['percentage'], 2) }}%
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Marks</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $data['total_marks'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Marks Obtained</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($data['obtained_marks'], 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Result</p>
                                        <p class="text-sm font-semibold {{ $data['percentage'] >= 33 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $data['percentage'] >= 33 ? 'PASS' : 'FAIL' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $data['percentage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Subject-wise Performance -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Subject-wise Analysis</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                                @foreach($progressData as $data)
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ $data['exam']->name }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $subjects = [];
                                foreach($progressData as $data) {
                                    foreach($data['marks'] as $mark) {
                                        $subjects[$mark->subject->name][] = $mark;
                                    }
                                }
                            @endphp
                            @foreach($subjects as $subjectName => $subjectMarks)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $subjectName }}
                                    </td>
                                    @foreach($progressData as $data)
                                        @php
                                            $mark = collect($subjectMarks)->first(function($m) use ($data) {
                                                return $m->exam_id == $data['exam']->id;
                                            });
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($mark)
                                                <div class="text-sm text-gray-900 dark:text-white">{{ number_format($mark->marks_obtained, 2) }}</div>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($mark->grade == 'A+' || $mark->grade == 'A') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                                    @elseif($mark->grade == 'B+' || $mark->grade == 'B') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                                    @elseif($mark->grade == 'C+' || $mark->grade == 'C') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                                    @endif">
                                                    {{ $mark->grade }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Please select a student to view their progress tracking.</p>
        </div>
        @endif
    </div>
</div>
@endsection
