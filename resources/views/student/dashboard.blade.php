@extends('layouts.admin')

@section('title', 'Student Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Welcome, {{ $student->first_name }}!</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Here's your academic overview</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Attendance Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Attendance</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $attendancePercentage }}%</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                @if($todayAttendance)
                    <span class="text-xs px-2 py-1 rounded-full {{ $todayAttendance->status == 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        Today: {{ ucfirst($todayAttendance->status) }}
                    </span>
                @else
                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                        No record today
                    </span>
                @endif
            </div>
        </div>

        <!-- Pending Fees Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending Fees</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">৳{{ number_format($pendingFees, 2) }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('student.fees') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View Details →</a>
            </div>
        </div>

        <!-- Library Books Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Issued Books</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $issuedBooks }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('student.library') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View Library →</a>
            </div>
        </div>

        <!-- Upcoming Exams Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Upcoming Exams</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $upcomingExams->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('student.exams') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View Schedule →</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Exams -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Upcoming Exams</h2>
                <a href="{{ route('student.exams') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View All</a>
            </div>
            @if($upcomingExams->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingExams as $exam)
                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-medium text-gray-800 dark:text-white">{{ $exam->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $exam->start_date->format('M d, Y') }} - {{ $exam->end_date->format('M d, Y') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">No upcoming exams</p>
            @endif
        </div>

        <!-- Recent Announcements -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Announcements</h2>
                <a href="{{ route('student.announcements') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View All</a>
            </div>
            @if($announcements->count() > 0)
                <div class="space-y-3">
                    @foreach($announcements as $announcement)
                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-medium text-gray-800 dark:text-white">{{ $announcement->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                {{ Str::limit($announcement->content, 100) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                {{ $announcement->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">No announcements</p>
            @endif
        </div>
    </div>
</div>
@endsection
