<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Apply for Leave') }}
            </h2>
            <a href="{{ route('teacher-leaves.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('teacher-leaves.store') }}">
                        @csrf

                        <!-- Teacher Selection (for admin) -->
                        @can('manage-leaves')
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Teacher <span class="text-red-500">*</span></label>
                            <select name="teacher_id" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">-- Select Teacher --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->full_name }} ({{ $teacher->employee_id }})</option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endcan

                        <!-- Leave Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Leave Type <span class="text-red-500">*</span></label>
                            <select name="leave_type_id" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">-- Select Leave Type --</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->max_days }} days available)</option>
                                @endforeach
                            </select>
                            @error('leave_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date <span class="text-red-500">*</span></label>
                                <input type="date" name="from_date" value="{{ old('from_date') }}" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('from_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date <span class="text-red-500">*</span></label>
                                <input type="date" name="to_date" value="{{ old('to_date') }}" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @error('to_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason <span class="text-red-500">*</span></label>
                            <textarea name="reason" rows="4" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" placeholder="Please provide a detailed reason for your leave...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition">
                                Submit Leave Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Leave Balance Card -->
            @if($leaveBalance)
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Your Leave Balance</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($leaveBalance as $balance)
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $balance['type'] }}</div>
                        <div class="text-2xl font-bold text-indigo-600">{{ $balance['remaining'] }}/{{ $balance['total'] }}</div>
                        <div class="text-xs text-gray-500">days remaining</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
