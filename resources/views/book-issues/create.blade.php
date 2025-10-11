@extends('layouts.admin')

@section('title', 'Issue Book')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Issue Book</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Issue a book to a student or teacher</p>
            </div>
            <a href="{{ route('book-issues.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                Back to Issues
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <form action="{{ route('book-issues.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Book Selection -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Book *</label>
                        <select name="book_id" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('book_id') border-red-500 @enderror">
                            <option value="">Choose a book</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }} - {{ $book->author }} ({{ $book->available_copies }} available)
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Borrower Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Borrower Type *</label>
                        <select name="borrower_type" id="borrowerType" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('borrower_type') border-red-500 @enderror">
                            <option value="">Select Type</option>
                            <option value="student" {{ old('borrower_type') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="teacher" {{ old('borrower_type') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        </select>
                        @error('borrower_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Borrower Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Borrower *</label>
                        <select name="borrower_id" id="borrowerSelect" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('borrower_id') border-red-500 @enderror">
                            <option value="">Choose borrower</option>
                        </select>
                        @error('borrower_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Issue Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Issue Date *</label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('issue_date') border-red-500 @enderror">
                        @error('issue_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remarks -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks</label>
                        <textarea name="remarks" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('remarks') border-red-500 @enderror">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Library Settings Info -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <h3 class="font-medium text-gray-800 dark:text-white mb-2">Library Settings</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Students can borrow up to {{ $settings->max_books_per_student ?? 3 }} books for {{ $settings->student_issue_days ?? 14 }} days</li>
                        <li>• Teachers can borrow up to {{ $settings->max_books_per_teacher ?? 5 }} books for {{ $settings->teacher_issue_days ?? 30 }} days</li>
                        <li>• Fine: ৳{{ $settings->fine_per_day ?? 5 }} per day for overdue books</li>
                    </ul>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('book-issues.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Issue Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const students = @json($students);
    const teachers = @json($teachers);
    
    document.getElementById('borrowerType').addEventListener('change', function() {
        const borrowerSelect = document.getElementById('borrowerSelect');
        borrowerSelect.innerHTML = '<option value="">Choose borrower</option>';
        
        if (this.value === 'student') {
            students.forEach(student => {
                borrowerSelect.innerHTML += `<option value="${student.id}">${student.name} - ${student.roll_number}</option>`;
            });
        } else if (this.value === 'teacher') {
            teachers.forEach(teacher => {
                borrowerSelect.innerHTML += `<option value="${teacher.id}">${teacher.name} - ${teacher.employee_id}</option>`;
            });
        }
    });
</script>
@endpush
@endsection
