{{-- Dashboard --}}
<a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
    </svg>
    <span class="font-medium">Dashboard</span>
</a>

@role('Super Admin|Admin')
{{-- Students Management --}}
<div x-data="{ open: {{ request()->is('students*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            <span class="font-medium">Students</span>
        </div>
        <svg class="w-4 h-4 transition-transform" :class="open ? 'transform rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
        <a href="{{ route('students.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">All Students</a>
        <a href="{{ route('students.create') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Add Student</a>
        <a href="{{ route('admissions.pending') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Pending Admissions</a>
        <a href="{{ route('admissions.create') }}" target="_blank" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Admission Form</a>
    </div>
</div>

{{-- Teachers Management --}}
<div x-data="{ open: {{ request()->is('teachers*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
            </svg>
            <span class="font-medium">Teachers</span>
        </div>
        <svg class="w-4 h-4 transition-transform" :class="open ? 'transform rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">All Teachers</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Add Teacher</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Departments</a>
    </div>
</div>

{{-- Academic Management --}}
<div x-data="{ open: {{ request()->is('classes*') || request()->is('subjects*') || request()->is('timetable*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
            </svg>
            <span class="font-medium">Academic</span>
        </div>
        <svg class="w-4 h-4 transition-transform" :class="open ? 'transform rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
        <a href="{{ route('classes.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('classes*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Classes & Sections
        </a>
        <a href="{{ route('subjects.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('subjects*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Subjects
        </a>
        <a href="{{ route('timetable.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('timetable*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Timetable
        </a>
    </div>
</div>
@endrole

{{-- Attendance --}}
@role('Super Admin|Admin|Teacher')
<div x-data="{ open: {{ request()->is('attendance*') || request()->is('leave-requests*') || request()->is('my-attendance*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">Attendance</span>
        </div>
        <svg class="w-4 h-4 transition-transform" :class="open ? 'transform rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
        <a href="{{ route('attendance.create') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('attendance/create') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Mark Attendance
        </a>
        <a href="{{ route('attendance.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('attendance') && !request()->is('attendance/*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            View Attendance
        </a>
        <a href="{{ route('attendance.reports') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('attendance/reports') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Reports
        </a>
        <a href="{{ route('leave-requests.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('leave-requests*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Leave Requests
        </a>
    </div>
</div>
@endrole

{{-- Examinations --}}
@role('Super Admin|Admin|Teacher')
<div x-data="{ open: {{ request()->is('exams*') || request()->is('marks*') || request()->is('report-card*') || request()->is('progress-tracking*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">Examinations</span>
        </div>
        <svg class="w-4 h-4 transition-transform" :class="open ? 'transform rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
        <a href="{{ route('exams.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('exams') && !request()->is('exams/*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Exam Schedule
        </a>
        <a href="{{ route('marks.index') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('marks') && !request()->is('marks/*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Enter Marks
        </a>
        <a href="{{ route('marks.report-card') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('report-card*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Report Cards
        </a>
        <a href="{{ route('marks.progress-tracking') }}" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->is('progress-tracking*') ? 'bg-blue-50 dark:bg-gray-700 text-blue-600' : '' }}">
            Progress Tracking
        </a>
    </div>
</div>
@endrole

{{-- Fee Management --}}
@role('Super Admin|Admin|Accountant')
<div x-data="{ open: {{ request()->is('fees*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">Fee Management</span>
        </div>
        <svg class="w-4 h-4 transition-transform" :class="open ? 'transform rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Fee Categories</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Collect Fees</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Invoices</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Due Payments</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Reports</a>
    </div>
</div>
@endrole

{{-- Library --}}
@role('Super Admin|Admin|Librarian')
<div x-data="{ open: {{ request()->is('library*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
            </svg>
            <span class="font-medium">Library</span>
        </div>
        <svg class="w-4 h-4 transition-transform" :class="open ? 'transform rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="ml-8 mt-2 space-y-2">
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">All Books</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Add Book</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Issue Book</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Return Book</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Overdue Books</a>
    </div>
</div>
@endrole

{{-- Announcements --}}
<a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
    </svg>
    <span class="font-medium">Announcements</span>
</a>

{{-- Reports --}}
@role('Super Admin|Admin')
<a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
    </svg>
    <span class="font-medium">Reports & Analytics</span>
</a>
@endrole

{{-- Settings --}}
@role('Super Admin|Admin')
<a href="#" class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
    </svg>
    <span class="font-medium">Settings</span>
</a>
@endrole
