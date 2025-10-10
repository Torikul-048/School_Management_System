<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Teacher Profile') }}
            </h2>
            <div class="flex gap-2">
                @can('edit teachers')
                <a href="{{ route('teachers.edit', $teacher) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                @endcan
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            @if($teacher->photo)
                                <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->full_name }}" class="h-32 w-32 rounded-full object-cover border-4 border-indigo-500">
                            @else
                                <div class="h-32 w-32 rounded-full bg-indigo-500 flex items-center justify-center text-white text-4xl font-bold border-4 border-indigo-600">
                                    {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $teacher->full_name }}</h3>
                            <p class="text-lg text-gray-600 dark:text-gray-400">{{ $teacher->designation }}</p>
                            <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Employee ID</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $teacher->employee_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Department</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $teacher->department ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    @if($teacher->status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
                                    @elseif($teacher->status == 'on_leave')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">On Leave</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Inactive</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Experience</p>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $teacher->experience_years }} Years</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Days</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $attendanceStats['total'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Present</div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $attendanceStats['present'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Absent</div>
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $attendanceStats['absent'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">On Leave</div>
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $attendanceStats['on_leave'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Personal Information</h4>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Email:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->email }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Phone:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->phone }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Date of Birth:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->date_of_birth->format('M d, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Gender:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($teacher->gender) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Blood Group:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->blood_group ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Nationality:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->nationality ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Professional Information</h4>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Qualification:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->qualification }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Specialization:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->specialization ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Joining Date:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->joining_date->format('M d, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Salary:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">${{ number_format($teacher->salary, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600 dark:text-gray-400">Bank Account:</dt>
                                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->bank_account_number ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Subjects Teaching -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Subjects Teaching</h4>
                        @if($teacher->subjects->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach($teacher->subjects as $subject)
                                    <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full text-sm">
                                        {{ $subject->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No subjects assigned yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Workload Summary -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Workload Summary</h4>
                        @if($workload->isNotEmpty())
                            <dl class="space-y-2">
                                @foreach($workload as $subjectId => $assignments)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600 dark:text-gray-400">{{ $assignments->first()->subject->name }}:</dt>
                                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $assignments->count() }} Class(es)</dd>
                                    </div>
                                @endforeach
                            </dl>
                            <div class="mt-4">
                                <a href="{{ route('teachers.workload', $teacher) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">View Detailed Workload →</a>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No assignments yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Contact Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Address</p>
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->address }}</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $teacher->city }}, {{ $teacher->state }} {{ $teacher->zip_code }}</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $teacher->country }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Emergency Contact</p>
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $teacher->emergency_contact_name ?? 'N/A' }}</p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $teacher->emergency_contact_phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Quick Actions</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('teachers.attendance', $teacher) }}" class="flex items-center justify-center px-4 py-3 bg-blue-100 dark:bg-blue-900 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="text-blue-800 dark:text-blue-200 font-medium">Attendance</span>
                        </a>
                        <a href="{{ route('teachers.workload', $teacher) }}" class="flex items-center justify-center px-4 py-3 bg-purple-100 dark:bg-purple-900 rounded-lg hover:bg-purple-200 dark:hover:bg-purple-800 transition">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="text-purple-800 dark:text-purple-200 font-medium">Workload</span>
                        </a>
                        <a href="{{ route('payroll.salary-history', $teacher) }}" class="flex items-center justify-center px-4 py-3 bg-green-100 dark:bg-green-900 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-green-800 dark:text-green-200 font-medium">Salary History</span>
                        </a>
                        <a href="{{ route('teachers.id-card', $teacher) }}" class="flex items-center justify-center px-4 py-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg hover:bg-yellow-200 dark:hover:bg-yellow-800 transition">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            <span class="text-yellow-800 dark:text-yellow-200 font-medium">ID Card</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
