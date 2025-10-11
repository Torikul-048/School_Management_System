@extends('layouts.admin')

@section('title', 'Attendance Reports')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('attendance.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Attendance Reports</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Generate and view attendance reports</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Report Type Selector -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6" x-data="{ reportType: '{{ request('type', 'daily') }}' }">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <button @click="reportType = 'daily'" :class="reportType === 'daily' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-6 py-3 rounded-lg font-semibold text-sm uppercase tracking-widest hover:opacity-90 transition">
                        Daily Report
                    </button>
                    <button @click="reportType = 'monthly'" :class="reportType === 'monthly' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-6 py-3 rounded-lg font-semibold text-sm uppercase tracking-widest hover:opacity-90 transition">
                        Monthly Report
                    </button>
                    <button @click="reportType = 'yearly'" :class="reportType === 'yearly' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-6 py-3 rounded-lg font-semibold text-sm uppercase tracking-widest hover:opacity-90 transition">
                        Yearly Report
                    </button>
                    <button @click="reportType = 'student'" :class="reportType === 'student' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'" class="px-6 py-3 rounded-lg font-semibold text-sm uppercase tracking-widest hover:opacity-90 transition">
                        Student Report
                    </button>
                </div>

                <!-- Daily Report Form -->
                <form method="GET" action="{{ route('attendance.reports.daily') }}" x-show="reportType === 'daily'" x-cloak>
                    <input type="hidden" name="report_type" value="daily">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="daily_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="date" id="daily_date" value="{{ request('date', date('Y-m-d')) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="daily_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class <span class="text-red-500">*</span></label>
                            <select name="class_id" id="daily_class" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" @change="loadSections($event.target.value, 'daily_section')">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="daily_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section <span class="text-red-500">*</span></label>
                            <select name="section_id" id="daily_section" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Monthly Report Form -->
                <form method="GET" action="{{ route('attendance.reports.monthly') }}" x-show="reportType === 'monthly'" x-cloak>
                    <input type="hidden" name="report_type" value="monthly">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="monthly_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month <span class="text-red-500">*</span></label>
                            <input type="month" name="month" id="monthly_month" value="{{ request('month', date('Y-m')) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="monthly_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class <span class="text-red-500">*</span></label>
                            <select name="class_id" id="monthly_class" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" @change="loadSections($event.target.value, 'monthly_section')">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="monthly_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section <span class="text-red-500">*</span></label>
                            <select name="section_id" id="monthly_section" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Yearly Report Form -->
                <form method="GET" action="{{ route('attendance.reports.yearly') }}" x-show="reportType === 'yearly'" x-cloak>
                    <input type="hidden" name="report_type" value="yearly">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="yearly_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Academic Year <span class="text-red-500">*</span></label>
                            <select name="academic_year_id" id="yearly_year" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Year</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="yearly_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class <span class="text-red-500">*</span></label>
                            <select name="class_id" id="yearly_class" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" @change="loadSections($event.target.value, 'yearly_section')">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="yearly_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section <span class="text-red-500">*</span></label>
                            <select name="section_id" id="yearly_section" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Student Report Form -->
                <form method="GET" action="{{ route('attendance.reports.student') }}" x-show="reportType === 'student'" x-cloak>
                    <input type="hidden" name="report_type" value="student">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label for="student_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class <span class="text-red-500">*</span></label>
                            <select name="class_id" id="student_class" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" @change="loadSections($event.target.value, 'student_section')">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="student_section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section <span class="text-red-500">*</span></label>
                            <select name="section_id" id="student_section" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500" @change="loadStudents($('#student_class').val(), $event.target.value)">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student <span class="text-red-500">*</span></label>
                            <select name="student_id" id="student_id" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Student</option>
                                @if($students->isNotEmpty())
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', date('Y-m-01')) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', date('Y-m-d')) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                Generate Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Results -->
        @if($reportData && is_array($reportData) && isset($reportData['title']))
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reportData['title'] }}</h2>
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Report
                    </button>
                </div>

                <!-- Summary Statistics -->
                @if(isset($reportData['summary']) && count($reportData['summary']) > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    @foreach($reportData['summary'] as $key => $value)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $key) }}</div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                @if(isset($reportData['columns']))
                                    @foreach($reportData['columns'] as $column)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $column }}</th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if(isset($reportData['data']) && count($reportData['data']) > 0)
                                @foreach($reportData['data'] as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    @foreach($row as $cell)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {!! $cell !!}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="{{ isset($reportData['columns']) ? count($reportData['columns']) : 1 }}" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No data available for the selected criteria.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-area, #printable-area * {
            visibility: visible;
        }
        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
    [x-cloak] { display: none !important; }
</style>

<script>
// Load sections based on selected class
function loadSections(classId, targetSelectId) {
    const sectionSelect = document.getElementById(targetSelectId);
    sectionSelect.innerHTML = '<option value="">Select Section</option>';
    
    if (!classId) return;
    
    // Find class sections from the classes data
    const classes = @json($classes);
    const selectedClass = classes.find(c => c.id == classId);
    
    if (selectedClass && selectedClass.sections) {
        selectedClass.sections.forEach(section => {
            const option = document.createElement('option');
            option.value = section.id;
            option.textContent = section.name;
            if ('{{ request("section_id") }}' == section.id) {
                option.selected = true;
            }
            sectionSelect.appendChild(option);
        });
    }
}

// Load students based on class and section
function loadStudents(classId, sectionId) {
    if (!classId || !sectionId) return;
    
    fetch(`/api/students?class_id=${classId}&section_id=${sectionId}`)
        .then(response => response.json())
        .then(students => {
            const studentSelect = document.getElementById('student_id');
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            
            students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = `${student.first_name} ${student.last_name} (${student.admission_number})`;
                if ('{{ request("student_id") }}' == student.id) {
                    option.selected = true;
                }
                studentSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading students:', error));
}

// Initialize sections on page load if class is selected
document.addEventListener('DOMContentLoaded', function() {
    const classId = '{{ request("class_id") }}';
    if (classId) {
        ['daily_section', 'monthly_section', 'yearly_section', 'student_section'].forEach(id => {
            const select = document.getElementById(id);
            if (select) loadSections(classId, id);
        });
    }
});
</script>
@endsection
