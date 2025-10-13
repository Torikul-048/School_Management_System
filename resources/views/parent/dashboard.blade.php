@extends('layouts.admin')

@section('title', 'Parent Dashboard')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Parent Dashboard</h1>
    <p class="text-gray-600 dark:text-gray-400 mt-2">Monitor your children's academic progress and activities</p>
</div>

<div>

<!-- Children Overview Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @foreach($childrenData as $childId => $data)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Child Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold">{{ $data['student']->user->name ?? 'Student' }}</h2>
                            <p class="text-blue-100 mt-1">
                                Class: {{ $data['student']->class->name ?? 'N/A' }} - {{ $data['student']->section->name ?? 'N/A' }}
                            </p>
                            <p class="text-blue-100 text-sm">Roll: {{ $data['student']->roll_number }}</p>
                        </div>
                        <a href="{{ route('parent.child.profile', $data['student']->id) }}" 
                           class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                            View Profile
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="p-6">
                    <!-- Attendance This Month -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Attendance (This Month)</h3>
                            <a href="{{ route('parent.child.attendance', $data['student']->id) }}" 
                               class="text-blue-600 text-sm hover:underline">View All</a>
                        </div>
                        <div class="flex items-center space-x-2 mb-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full" style="width: {{ $data['attendance']['percentage'] }}%"></div>
                            </div>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $data['attendance']['percentage'] }}%</span>
                        </div>
                        <div class="grid grid-cols-4 gap-2 text-center text-sm">
                            <div>
                                <div class="text-gray-600 dark:text-gray-400">Total</div>
                                <div class="font-bold text-gray-900 dark:text-white">{{ $data['attendance']['total'] }}</div>
                            </div>
                            <div>
                                <div class="text-green-600">Present</div>
                                <div class="font-bold text-green-600">{{ $data['attendance']['present'] }}</div>
                            </div>
                            <div>
                                <div class="text-red-600">Absent</div>
                                <div class="font-bold text-red-600">{{ $data['attendance']['absent'] }}</div>
                            </div>
                            <div>
                                <div class="text-yellow-600">Late</div>
                                <div class="font-bold text-yellow-600">{{ $data['attendance']['late'] }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Fees -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Pending Fees</h3>
                            <a href="{{ route('parent.fees', $data['student']->id) }}" 
                               class="text-blue-600 text-sm hover:underline">Pay Now</a>
                        </div>
                        <div class="text-2xl font-bold {{ $data['pendingFees'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                            ${{ number_format($data['pendingFees'], 2) }}
                        </div>
                    </div>

                    <!-- Recent Results -->
                    @if($data['recentResults']->isNotEmpty())
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-700 dark:text-gray-300">Recent Results</h3>
                            <a href="{{ route('parent.child.results', $data['student']->id) }}" 
                               class="text-blue-600 text-sm hover:underline">View All</a>
                        </div>
                        <div class="space-y-2">
                            @foreach($data['recentResults'] as $result)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 dark:text-gray-400">{{ $result->subject->name ?? 'N/A' }}</span>
                                <span class="font-semibold">{{ $result->marks_obtained }}/{{ $result->total_marks }} ({{ $result->grade }})</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('parent.homework', $data['student']->id) }}" 
                           class="flex items-center justify-center space-x-2 bg-blue-100 text-blue-700 px-4 py-3 rounded-lg hover:bg-blue-200 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium">Homework</span>
                        </a>
                        <a href="{{ route('parent.leave-requests', $data['student']->id) }}" 
                           class="flex items-center justify-center space-x-2 bg-purple-100 text-purple-700 px-4 py-3 rounded-lg hover:bg-purple-200 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium">Leave</span>
                        </a>
                        <a href="{{ route('parent.library', $data['student']->id) }}" 
                           class="flex items-center justify-center space-x-2 bg-green-100 text-green-700 px-4 py-3 rounded-lg hover:bg-green-200 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            <span class="text-sm font-medium">Library</span>
                        </a>
                        <a href="{{ route('parent.messages') }}" 
                           class="flex items-center justify-center space-x-2 bg-yellow-100 text-yellow-700 px-4 py-3 rounded-lg hover:bg-yellow-200 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <span class="text-sm font-medium">Messages</span>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Announcements -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Recent Announcements</h2>
        @if($announcements->isNotEmpty())
            <div class="space-y-4">
                @foreach($announcements as $announcement)
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $announcement->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ Str::limit($announcement->content, 150) }}</p>
                    <p class="text-gray-500 text-xs mt-2">{{ $announcement->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('parent.notifications') }}" class="text-blue-600 hover:underline">View All Notifications →</a>
            </div>
        @else
            <p class="text-gray-500">No announcements available.</p>
        @endif
    </div>
</div>

@endsection
