<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Leave Balance') }}
            </h2>
            <a href="{{ route('teacher-leaves.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Back to Leaves
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Teacher Selection (for admin) -->
            @can('manage-leaves')
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('teacher-leaves.balance') }}">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Teacher</label>
                            <select name="teacher_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">All Teachers</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->full_name }} ({{ $teacher->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endcan

            <!-- Leave Balance Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                @foreach($leaveTypes as $type)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ $type->name }}</h3>
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-3xl font-bold text-indigo-600">{{ $type->balance['remaining'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Days Remaining</div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg text-gray-900 dark:text-gray-100">{{ $type->balance['used'] }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Used</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                            <span>Usage</span>
                            <span>{{ number_format(($type->balance['used'] / $type->max_days) * 100, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($type->balance['used'] / $type->max_days) * 100 }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total: {{ $type->max_days }} days</div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Leave Usage Details -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Leave Usage Details</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Leave Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Allocated</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Used</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pending</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Remaining</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($leaveTypes as $type)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $type->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $type->max_days }} days</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $type->balance['used'] }} days</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">{{ $type->balance['pending'] }} days</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">{{ $type->balance['remaining'] }} days</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($type->balance['remaining'] > ($type->max_days / 2))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Good</span>
                                        @elseif($type->balance['remaining'] > 0)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Low</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Exhausted</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
