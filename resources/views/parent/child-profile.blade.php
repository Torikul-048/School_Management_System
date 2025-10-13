@extends('layouts.admin')

@section('title', 'Child Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
            <h1 class="text-3xl font-bold">{{ $child->user->name ?? 'Student Profile' }}</h1>
            <p class="text-blue-100 mt-2">Complete academic and personal information</p>
        </div>

        <div class="p-6">
            <!-- Academic Information -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Academic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Admission Number</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->admission_number }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Roll Number</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->roll_number }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Class</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->class->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Section</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->section->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Academic Year</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->academicYear->year ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Admission Date</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->admission_date->format('d M, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Date of Birth</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->date_of_birth->format('d M, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Gender</label>
                        <p class="text-gray-900 dark:text-white font-semibold capitalize">{{ $child->gender }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Blood Group</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->blood_group ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Religion</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->religion ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Nationality</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->nationality }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Status</label>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $child->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($child->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Contact Information</h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Current Address</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->current_address ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-600 dark:text-gray-400 text-sm">Permanent Address</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $child->permanent_address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Guardian Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Father's Details</h3>
                        <div class="space-y-2">
                            <div>
                                <label class="text-gray-600 dark:text-gray-400 text-sm">Name</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $child->father_name }}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 dark:text-gray-400 text-sm">Phone</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $child->father_phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 dark:text-gray-400 text-sm">Occupation</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $child->father_occupation ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Mother's Details</h3>
                        <div class="space-y-2">
                            <div>
                                <label class="text-gray-600 dark:text-gray-400 text-sm">Name</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $child->mother_name }}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 dark:text-gray-400 text-sm">Phone</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $child->mother_phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 dark:text-gray-400 text-sm">Occupation</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $child->mother_occupation ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('parent.child.attendance', $child->id) }}" 
                       class="flex flex-col items-center justify-center bg-blue-100 text-blue-700 p-4 rounded-lg hover:bg-blue-200 transition">
                        <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">Attendance</span>
                    </a>
                    <a href="{{ route('parent.child.results', $child->id) }}" 
                       class="flex flex-col items-center justify-center bg-green-100 text-green-700 p-4 rounded-lg hover:bg-green-200 transition">
                        <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">Results</span>
                    </a>
                    <a href="{{ route('parent.fees', $child->id) }}" 
                       class="flex flex-col items-center justify-center bg-yellow-100 text-yellow-700 p-4 rounded-lg hover:bg-yellow-200 transition">
                        <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">Fees</span>
                    </a>
                    <a href="{{ route('parent.homework', $child->id) }}" 
                       class="flex flex-col items-center justify-center bg-purple-100 text-purple-700 p-4 rounded-lg hover:bg-purple-200 transition">
                        <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium">Homework</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
