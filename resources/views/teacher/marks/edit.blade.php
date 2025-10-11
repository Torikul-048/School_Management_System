@extends('layouts.admin')

@section('title', 'Edit Marks')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Marks</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">
            {{ $mark->exam->name }} - {{ $mark->subject->name }} - {{ $mark->student->user->name }}
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 max-w-2xl">
        <form action="{{ route('teacher.marks.update', $mark->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Student Info -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-750 rounded-lg">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $mark->student->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Roll No</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $mark->student->admission_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exam</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $mark->exam->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
                        <p class="text-gray-900 dark:text-white font-semibold">{{ $mark->subject->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Marks Entry -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Total Marks
                    </label>
                    <input type="text" value="{{ $mark->total_marks }}" readonly
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Marks Obtained *
                    </label>
                    <input type="number" 
                           name="marks_obtained" 
                           id="marks_obtained"
                           value="{{ old('marks_obtained', $mark->marks_obtained) }}"
                           min="0" 
                           max="{{ $mark->total_marks }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    @error('marks_obtained')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Grade (Auto-calculated)
                    </label>
                    <div id="grade_display" 
                         class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $mark->grade }}</span>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400" id="percentage_display">
                            ({{ number_format(($mark->marks_obtained / $mark->total_marks) * 100, 2) }}%)
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Remarks
                    </label>
                    <textarea name="remarks" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">{{ old('remarks', $mark->remarks) }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex gap-3">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                    Update Marks
                </button>
                <a href="{{ route('teacher.marks') }}" 
                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const marksInput = document.getElementById('marks_obtained');
    const gradeDisplay = document.getElementById('grade_display');
    const percentageDisplay = document.getElementById('percentage_display');
    const totalMarks = {{ $mark->total_marks }};
    
    marksInput.addEventListener('input', function() {
        const marksObtained = parseFloat(this.value) || 0;
        const percentage = (marksObtained / totalMarks) * 100;
        
        // Calculate grade
        let grade = '-';
        let gradeColor = 'text-gray-900 dark:text-white';
        
        if (percentage >= 90) {
            grade = 'A+';
            gradeColor = 'text-green-600';
        } else if (percentage >= 80) {
            grade = 'A';
            gradeColor = 'text-green-600';
        } else if (percentage >= 70) {
            grade = 'B+';
            gradeColor = 'text-blue-600';
        } else if (percentage >= 60) {
            grade = 'B';
            gradeColor = 'text-blue-600';
        } else if (percentage >= 50) {
            grade = 'C+';
            gradeColor = 'text-yellow-600';
        } else if (percentage >= 40) {
            grade = 'C';
            gradeColor = 'text-yellow-600';
        } else if (marksObtained > 0) {
            grade = 'F';
            gradeColor = 'text-red-600';
        }
        
        // Update display
        gradeDisplay.innerHTML = `
            <span class="text-lg font-bold ${gradeColor}">${grade}</span>
            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">(${percentage.toFixed(2)}%)</span>
        `;
    });
});
</script>
@endpush
@endsection
