@extends('layouts.admin')

@section('title', 'My Marks')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Marks</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View your exam results</p>
    </div>

    @forelse($marks as $examId => $examMarks)
        @php
            $exam = $exams[$examId];
            $totalObtained = $examMarks->sum('marks_obtained');
            $totalMax = $examMarks->sum('total_marks');
            $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
        @endphp
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $exam->name }}</h2>
                <div class="text-right">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Percentage</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $percentage }}%</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Subject</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300">Marks Obtained</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300">Total Marks</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300">Percentage</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300">Grade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($examMarks as $mark)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">{{ $mark->subject->name }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-white">{{ $mark->marks_obtained }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-white">{{ $mark->total_marks }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-white">
                                    {{ round(($mark->marks_obtained / $mark->total_marks) * 100, 2) }}%
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800">{{ $mark->grade }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800 dark:text-white">Total</td>
                            <td class="px-4 py-3 text-sm font-semibold text-center text-gray-800 dark:text-white">{{ $totalObtained }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-center text-gray-800 dark:text-white">{{ $totalMax }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-center text-gray-800 dark:text-white">{{ $percentage }}%</td>
                            <td class="px-4 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('student.report-card.download', $examId) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Report Card
                </a>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
            <p class="text-gray-600 dark:text-gray-400">No marks available yet</p>
        </div>
    @endforelse
</div>
@endsection
