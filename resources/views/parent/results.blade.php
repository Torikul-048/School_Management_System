@extends('layouts.admin')

@section('title', 'Exam Results')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Exam Results & Report Cards</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $child->user->name ?? 'Student' }} - Class {{ $child->class->name ?? '' }}</p>

        @forelse($exams as $exam)
        <div class="mb-8 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $exam->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $exam->start_date->format('d M, Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Marks Obtained</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Marks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Percentage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Grade</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @php
                            $totalMarks = 0;
                            $totalObtained = 0;
                        @endphp
                        @foreach($exam->marks as $mark)
                            @php
                                $totalMarks += $mark->total_marks;
                                $totalObtained += $mark->marks_obtained;
                                $percentage = $mark->total_marks > 0 ? round(($mark->marks_obtained / $mark->total_marks) * 100, 2) : 0;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $mark->subject->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $mark->marks_obtained }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $mark->total_marks }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $percentage }}%</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $mark->grade === 'A+' || $mark->grade === 'A' ? 'bg-green-100 text-green-800' : 
                                           ($mark->grade === 'B' || $mark->grade === 'C' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $mark->grade }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50 dark:bg-gray-700 font-bold">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">TOTAL</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $totalObtained }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $totalMarks }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $totalMarks > 0 ? round(($totalObtained / $totalMarks) * 100, 2) : 0 }}%
                            </td>
                            <td class="px-6 py-4"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <p class="text-gray-500">No exam results available yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
