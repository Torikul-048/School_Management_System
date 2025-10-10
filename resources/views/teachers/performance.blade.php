<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Performance Evaluation - ') }} {{ $teacher->full_name }}
            </h2>
            <a href="{{ route('teachers.show', $teacher) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overall Rating -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Overall Performance Rating</h3>
                    <div class="text-5xl font-bold text-indigo-600 mb-2">{{ number_format($overallRating, 1) }}</div>
                    <div class="text-gray-600 dark:text-gray-400">out of 5.0</div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Teaching Skills</div>
                    <div class="flex items-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mr-2">{{ $metrics['teaching_skills'] }}</div>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($metrics['teaching_skills'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Communication</div>
                    <div class="flex items-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mr-2">{{ $metrics['communication'] }}</div>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($metrics['communication'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Punctuality</div>
                    <div class="flex items-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mr-2">{{ $metrics['punctuality'] }}</div>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ ($metrics['punctuality'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Student Results</div>
                    <div class="flex items-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-gray-100 mr-2">{{ $metrics['student_results'] }}</div>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($metrics['student_results'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluation History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Evaluation History</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Evaluator</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Comments</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($evaluations as $evaluation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $evaluation->evaluation_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $evaluation->evaluator->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($evaluation->overall_rating >= 4.5) bg-green-100 text-green-800
                                            @elseif($evaluation->overall_rating >= 3.5) bg-blue-100 text-blue-800
                                            @elseif($evaluation->overall_rating >= 2.5) bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $evaluation->overall_rating }}/5.0
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ Str::limit($evaluation->comments, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">View Details</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No evaluation records found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
