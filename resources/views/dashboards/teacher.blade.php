@extends('layouts.admin')

@section('title', 'Teacher Dashboard')

@section('content')
    <!-- Welcome Alert -->
    <x-alert type="success" title="Welcome back, {{ Auth::user()->name }}!" :dismissible="true" class="mb-5">
        You are logged in as <span class="font-semibold">Teacher</span>. Have a productive day!
    </x-alert>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card title="My Classes" value="5" icon="academic-cap" color="blue"></x-stat-card>
        <x-stat-card title="My Students" value="127" icon="users" color="green"></x-stat-card>
        <x-stat-card title="Pending Grades" value="23" icon="clipboard-check" color="yellow"></x-stat-card>
        <x-stat-card title="Attendance Rate" value="98%" icon="calendar" color="purple" trend="up" trendValue="2.5%"></x-stat-card>
    </div>

    <!-- Quick Actions -->
    <div class="mt-5">
        <x-card title="Quick Actions">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('teacher.attendance.take') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <svg class="w-10 h-10 text-blue-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Take Attendance</span>
                </a>
                <a href="{{ route('teacher.marks.enter') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    <svg class="w-10 h-10 text-green-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Enter Marks</span>
                </a>
                <a href="{{ route('teacher.students') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    <svg class="w-10 h-10 text-purple-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">View Students</span>
                </a>
                <a href="{{ route('teacher.timetable') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                    <svg class="w-10 h-10 text-yellow-600 mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">View Schedule</span>
                </a>
            </div>
        </x-card>
    </div>

    <!-- Today's Schedule -->
    <div class="mt-5">
        <x-card title="Today's Schedule">
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                            8:00
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="text-sm font-medium text-gray-900">Mathematics - Grade 10A</h4>
                        <p class="text-sm text-gray-500">Room 101 • 60 minutes</p>
                    </div>
                    <x-badge type="success">Ongoing</x-badge>
                </div>
                
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gray-400 rounded-lg flex items-center justify-center text-white font-bold">
                            10:00
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="text-sm font-medium text-gray-900">Physics - Grade 9B</h4>
                        <p class="text-sm text-gray-500">Room 203 • 60 minutes</p>
                    </div>
                    <x-badge type="default">Upcoming</x-badge>
                </div>
                
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gray-400 rounded-lg flex items-center justify-center text-white font-bold">
                            13:00
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="text-sm font-medium text-gray-900">Mathematics - Grade 11C</h4>
                        <p class="text-sm text-gray-500">Room 101 • 60 minutes</p>
                    </div>
                    <x-badge type="default">Upcoming</x-badge>
                </div>
            </div>
            <x-slot name="footer">
                <x-link href="{{ route('teacher.timetable') }}">View Full Schedule</x-link>
            </x-slot>
        </x-card>
    </div>
@endsection
