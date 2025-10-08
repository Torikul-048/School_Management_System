@extends('layouts.app')

@section('title', 'Timetable Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900 dark:text-gray-200">Timetable</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Timetable Management</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage class schedules and periods</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('timetable.auto-generate') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center space-x-2 shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span>Auto Generate</span>
            </a>
            <a href="{{ route('timetable.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center space-x-2 shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Period</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('timetable.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Class Filter -->
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Class
                </label>
                <select name="class_id" id="class_id" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    onchange="this.form.submit()">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Section Filter -->
            <div>
                <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Section
                </label>
                <select name="section_id" id="section_id" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    onchange="this.form.submit()">
                    <option value="">All Sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Day Filter -->
            <div>
                <label for="day_of_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Day
                </label>
                <select name="day_of_week" id="day_of_week" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                    onchange="this.form.submit()">
                    <option value="">All Days</option>
                    <option value="Monday" {{ request('day_of_week') == 'Monday' ? 'selected' : '' }}>Monday</option>
                    <option value="Tuesday" {{ request('day_of_week') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="Wednesday" {{ request('day_of_week') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="Thursday" {{ request('day_of_week') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                    <option value="Friday" {{ request('day_of_week') == 'Friday' ? 'selected' : '' }}>Friday</option>
                    <option value="Saturday" {{ request('day_of_week') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end">
                <a href="{{ route('timetable.index') }}" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center">
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Weekly Timetable View -->
    @if(request('class_id') && request('section_id'))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">
                    Weekly Timetable - {{ $classes->find(request('class_id'))->name }} 
                    ({{ $sections->find(request('section_id'))->name }})
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Time
                            </th>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ $day }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($timetables->groupBy('start_time') as $time => $periods)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($time)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($periods->first()->end_time)->format('h:i A') }}
                                </td>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    @php
                                        $period = $periods->where('day_of_week', $day)->first();
                                    @endphp
                                    <td class="px-6 py-4 text-center">
                                        @if($period)
                                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $period->subject->name }}
                                                </p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ $period->teacher->first_name }} {{ $period->teacher->last_name }}
                                                </p>
                                                <div class="flex justify-center space-x-2 mt-2">
                                                    <a href="{{ route('timetable.edit', $period) }}" class="text-blue-600 hover:text-blue-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('timetable.destroy', $period) }}" method="POST" 
                                                        onsubmit="return confirm('Delete this period?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Selection Prompt -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">Select Class and Section</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Please select a class and section to view the timetable.</p>
        </div>
    @endif
</div>
@endsection
