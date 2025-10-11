@extends('layouts.admin')

@section('title', 'Enter Marks')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Enter Marks</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Select exam, class, and subject to enter marks</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <!-- Selection Form -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Exam *</label>
                    <select name="exam_id" required onchange="this.form.submit()"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select Exam</option>
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                            {{ $exam->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Class *</label>
                    <select name="class_id" required onchange="this.form.submit()"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select Class</option>
                        @foreach($assignments->unique('class_id') as $assignment)
                        <option value="{{ $assignment->class_id }}" {{ request('class_id') == $assignment->class_id ? 'selected' : '' }}>
                            {{ $assignment->class->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject *</label>
                    <select name="subject_id" required onchange="this.form.submit()"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select Subject</option>
                        @if(request('class_id'))
                            @foreach($assignments->where('class_id', request('class_id')) as $assignment)
                            <option value="{{ $assignment->subject_id }}" {{ request('subject_id') == $assignment->subject_id ? 'selected' : '' }}>
                                {{ $assignment->subject->name }}
                            </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </form>

        @if($selectedExam && $selectedClass && $selectedSubject && $students->isNotEmpty())
        <!-- Marks Entry Form -->
        <form action="{{ route('teacher.marks.store') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">

            <!-- Marks Configuration -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-750 rounded-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Marks</label>
                    <input type="number" name="total_marks" value="{{ old('total_marks', $selectedExam->total_marks ?? 100) }}" readonly
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pass Marks</label>
                    <input type="number" name="pass_marks" value="{{ old('pass_marks', $selectedExam->pass_marks ?? 40) }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Students</label>
                    <input type="text" value="{{ count($students) }}" readonly
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <!-- Students Marks Entry -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Roll No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Student Name</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Marks Obtained</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Grade</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($students as $student)
                        @php
                            $existingMark = \App\Models\Mark::where([
                                'exam_id' => $selectedExam->id,
                                'student_id' => $student->id,
                                'subject_id' => $selectedSubject->id
                            ])->first();
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $student->admission_number }}
                                <input type="hidden" name="students[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 font-semibold text-sm mr-3">
                                        {{ substr($student->user->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $student->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="number" 
                                       name="marks[{{ $student->id }}]" 
                                       value="{{ old("marks.{$student->id}", $existingMark->marks_obtained ?? '') }}"
                                       min="0" 
                                       max="{{ $selectedExam->total_marks ?? 100 }}"
                                       class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-center focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white marks-input"
                                       data-student="{{ $student->id }}"
                                       data-total="{{ $selectedExam->total_marks ?? 100 }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="grade-display px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300" 
                                      data-student="{{ $student->id }}">
                                    {{ $existingMark->grade ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs text-gray-600 dark:text-gray-400">Auto</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex gap-3">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                    Save Marks
                </button>
                <a href="{{ route('teacher.marks.index') }}" 
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium">
                    Cancel
                </a>
            </div>
        </form>
        @else
        <div class="text-center py-12 text-gray-500">
            <p>Please select exam, class, and subject to enter marks</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate grade based on marks
    const marksInputs = document.querySelectorAll('.marks-input');
    
    marksInputs.forEach(input => {
        input.addEventListener('input', function() {
            const studentId = this.getAttribute('data-student');
            const totalMarks = parseFloat(this.getAttribute('data-total'));
            const marksObtained = parseFloat(this.value) || 0;
            const percentage = (marksObtained / totalMarks) * 100;
            
            // Calculate grade
            let grade = '-';
            let gradeClass = 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300';
            
            if (percentage >= 90) {
                grade = 'A+';
                gradeClass = 'bg-green-100 text-green-800';
            } else if (percentage >= 80) {
                grade = 'A';
                gradeClass = 'bg-green-100 text-green-800';
            } else if (percentage >= 70) {
                grade = 'B+';
                gradeClass = 'bg-blue-100 text-blue-800';
            } else if (percentage >= 60) {
                grade = 'B';
                gradeClass = 'bg-blue-100 text-blue-800';
            } else if (percentage >= 50) {
                grade = 'C+';
                gradeClass = 'bg-yellow-100 text-yellow-800';
            } else if (percentage >= 40) {
                grade = 'C';
                gradeClass = 'bg-yellow-100 text-yellow-800';
            } else if (marksObtained > 0) {
                grade = 'F';
                gradeClass = 'bg-red-100 text-red-800';
            }
            
            // Update grade display
            const gradeDisplay = document.querySelector(`.grade-display[data-student="${studentId}"]`);
            if (gradeDisplay) {
                gradeDisplay.textContent = grade;
                gradeDisplay.className = `grade-display px-3 py-1 text-sm font-semibold rounded-full ${gradeClass}`;
            }
        });
        
        // Trigger calculation for pre-filled values
        if (input.value) {
            input.dispatchEvent(new Event('input'));
        }
    });
});
</script>
@endpush
@endsection
