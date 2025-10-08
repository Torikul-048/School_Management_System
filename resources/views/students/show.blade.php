@extends('layouts.admin')

@section('title', 'Student Profile - ' . $student->full_name)

@section('content')
    <!-- Header with Actions -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
            <a href="{{ route('students.index') }}" class="hover:text-blue-600">Students</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span>{{ $student->full_name }}</span>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                @if($student->photo)
                    <img src="{{ Storage::url($student->photo) }}" alt="{{ $student->full_name }}" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
                @else
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                        <span class="text-2xl font-bold text-white">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $student->full_name }}</h1>
                    <p class="text-gray-600">{{ $student->admission_number }} • Roll No: {{ $student->roll_number }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        @if($student->status === 'active')
                            <x-badge type="success">Active</x-badge>
                        @else
                            <x-badge type="default">{{ ucfirst($student->status) }}</x-badge>
                        @endif
                        <span class="text-sm text-gray-500">{{ $student->class->name ?? 'N/A' }} - {{ $student->section->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('students.id-card', $student) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                    </svg>
                    ID Card
                </a>
                <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <x-card>
                <x-slot name="title">Personal Information</x-slot>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Full Name</p>
                        <p class="font-medium text-gray-900">{{ $student->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date of Birth</p>
                        <p class="font-medium text-gray-900">{{ $student->date_of_birth->format('M d, Y') }} ({{ $student->date_of_birth->age }} years)</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="font-medium text-gray-900">{{ ucfirst($student->gender) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Blood Group</p>
                        <p class="font-medium text-gray-900">{{ $student->blood_group ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Religion</p>
                        <p class="font-medium text-gray-900">{{ $student->religion ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="font-medium text-gray-900">{{ $student->category ?? 'N/A' }}</p>
                    </div>
                </div>
            </x-card>

            <!-- Contact Information -->
            <x-card>
                <x-slot name="title">Contact Information</x-slot>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Email Address</p>
                        <p class="font-medium text-gray-900">{{ $student->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="font-medium text-gray-900">{{ $student->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium text-gray-900">{{ $student->address }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $student->city }}, {{ $student->state }} {{ $student->postal_code }}</p>
                    </div>
                </div>
            </x-card>

            <!-- Guardian Information -->
            <x-card>
                <x-slot name="title">Guardian/Parent Information</x-slot>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Guardian Name</p>
                        <p class="font-medium text-gray-900">{{ $student->guardian_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Relation</p>
                        <p class="font-medium text-gray-900">{{ $student->guardian_relation }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="font-medium text-gray-900">{{ $student->guardian_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $student->guardian_email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Occupation</p>
                        <p class="font-medium text-gray-900">{{ $student->guardian_occupation ?? 'N/A' }}</p>
                    </div>
                </div>
            </x-card>

            <!-- Academic Records -->
            <x-card>
                <x-slot name="title">Recent Academic Performance</x-slot>
                
                @if($student->grades->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marks</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($student->grades->take(10) as $grade)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $grade->exam->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $grade->subject->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $grade->obtained_marks }}/{{ $grade->total_marks }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <x-badge type="info">{{ $grade->grade }}</x-badge>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($grade->result === 'pass')
                                                <x-badge type="success">Pass</x-badge>
                                            @else
                                                <x-badge type="danger">Fail</x-badge>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">No academic records found</p>
                @endif
            </x-card>
        </div>

        <!-- Right Column - Stats & Quick Info -->
        <div class="space-y-6">
            <!-- Academic Information -->
            <x-card>
                <x-slot name="title">Academic Details</x-slot>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Admission Number</p>
                        <p class="font-medium text-gray-900">{{ $student->admission_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Roll Number</p>
                        <p class="font-medium text-gray-900">{{ $student->roll_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Class</p>
                        <p class="font-medium text-gray-900">{{ $student->class->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Section</p>
                        <p class="font-medium text-gray-900">{{ $student->section->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Academic Year</p>
                        <p class="font-medium text-gray-900">{{ $student->academicYear->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Admission Date</p>
                        <p class="font-medium text-gray-900">{{ $student->admission_date->format('M d, Y') }}</p>
                    </div>
                    @if($student->previous_school)
                        <div>
                            <p class="text-sm text-gray-500">Previous School</p>
                            <p class="font-medium text-gray-900">{{ $student->previous_school }}</p>
                        </div>
                    @endif
                </div>
            </x-card>

            <!-- Quick Stats -->
            <x-card>
                <x-slot name="title">Statistics</x-slot>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Attendance</span>
                        </div>
                        <span class="font-bold text-blue-600">{{ $student->attendances->where('status', 'present')->count() }}/{{ $student->attendances->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Exams Passed</span>
                        </div>
                        <span class="font-bold text-green-600">{{ $student->grades->where('result', 'pass')->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            <span class="text-sm text-gray-700">Books Issued</span>
                        </div>
                        <span class="font-bold text-purple-600">{{ $student->bookIssues->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Fee Status</span>
                        </div>
                        <span class="font-bold text-yellow-600">{{ $student->feeInvoices->where('status', 'paid')->count() }}/{{ $student->feeInvoices->count() }}</span>
                    </div>
                </div>
            </x-card>

            <!-- Documents -->
            <x-card>
                <x-slot name="title">Documents</x-slot>
                
                <div class="space-y-2">
                    @if($student->birth_certificate)
                        <a href="{{ Storage::url($student->birth_certificate) }}" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Birth Certificate</span>
                        </a>
                    @endif

                    @if($student->transfer_certificate)
                        <a href="{{ Storage::url($student->transfer_certificate) }}" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-gray-700">Transfer Certificate</span>
                        </a>
                    @endif

                    @if(!$student->birth_certificate && !$student->transfer_certificate)
                        <p class="text-center text-gray-500 py-4 text-sm">No documents uploaded</p>
                    @endif
                </div>
            </x-card>

            <!-- Quick Actions -->
            <x-card>
                <x-slot name="title">Quick Actions</x-slot>
                
                <div class="space-y-2">
                    <form action="{{ route('students.promote', $student) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="button" class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 text-left">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            <span class="text-sm text-gray-700">Promote to Next Class</span>
                        </button>
                    </form>

                    <form action="{{ route('students.transfer', $student) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this student as transferred?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 text-left">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span class="text-sm text-gray-700">Transfer Student</span>
                        </button>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
@endsection
