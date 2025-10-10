<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\LibrarySetting;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = BookIssue::with(['book', 'student', 'teacher', 'issuer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('borrower_type')) {
            if ($request->borrower_type === 'student') {
                $query->whereNotNull('student_id');
            } else {
                $query->whereNotNull('teacher_id');
            }
        }

        if ($request->filled('search')) {
            $query->where('issue_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('book', function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%');
                });
        }

        $issues = $query->latest()->paginate(20);

        return view('book-issues.index', compact('issues'));
    }

    public function create()
    {
        $books = Book::where('status', 'available')
            ->where('available_copies', '>', 0)
            ->orderBy('title')
            ->get();
        $students = Student::where('status', 'active')->orderBy('name')->get();
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $settings = LibrarySetting::getSettings();

        return view('book-issues.create', compact('books', 'students', 'teachers', 'settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_type' => 'required|in:student,teacher',
            'borrower_id' => 'required|integer',
            'issue_date' => 'required|date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if (!$book->isAvailable()) {
            return redirect()->back()->with('error', 'Book is not available for issue!');
        }

        $settings = LibrarySetting::getSettings();

        // Check borrower type and validate
        if ($validated['borrower_type'] === 'student') {
            $borrower = Student::findOrFail($validated['borrower_id']);
            $maxBooks = $settings->max_books_per_student;
            $issueDays = $settings->student_issue_days;
            
            $activeIssues = BookIssue::where('student_id', $borrower->id)
                ->where('status', 'issued')
                ->count();
        } else {
            $borrower = Teacher::findOrFail($validated['borrower_id']);
            $maxBooks = $settings->max_books_per_teacher;
            $issueDays = $settings->teacher_issue_days;
            
            $activeIssues = BookIssue::where('teacher_id', $borrower->id)
                ->where('status', 'issued')
                ->count();
        }

        if ($activeIssues >= $maxBooks) {
            return redirect()->back()->with('error', 'Maximum book limit reached for this borrower!');
        }

        $issueDate = Carbon::parse($validated['issue_date']);
        $dueDate = $issueDate->copy()->addDays($issueDays);

        $issueData = [
            'book_id' => $book->id,
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'status' => 'issued',
            'issued_by' => Auth::id(),
            'remarks' => $validated['remarks'],
        ];

        if ($validated['borrower_type'] === 'student') {
            $issueData['student_id'] = $validated['borrower_id'];
        } else {
            $issueData['teacher_id'] = $validated['borrower_id'];
        }

        $issue = BookIssue::create($issueData);

        // Update book available copies
        $book->decrement('available_copies');

        // Update status if no more copies
        if ($book->available_copies <= 0) {
            $book->update(['status' => 'unavailable']);
        }

        return redirect()->route('book-issues.show', $issue)->with('success', 'Book issued successfully!');
    }

    public function show(BookIssue $bookIssue)
    {
        $bookIssue->load(['book', 'student', 'teacher', 'issuer', 'receiver']);
        
        if ($bookIssue->isOverdue()) {
            $bookIssue->fine_amount = $bookIssue->calculateFine();
        }

        return view('book-issues.show', compact('bookIssue'));
    }

    public function returnBook(Request $request, BookIssue $bookIssue)
    {
        if ($bookIssue->status !== 'issued') {
            return redirect()->back()->with('error', 'This book is not currently issued!');
        }

        $validated = $request->validate([
            'return_date' => 'required|date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $returnDate = Carbon::parse($validated['return_date']);
        
        // Calculate fine if overdue
        $fineAmount = 0;
        if ($returnDate->gt($bookIssue->due_date)) {
            $settings = LibrarySetting::getSettings();
            $daysLate = $returnDate->diffInDays($bookIssue->due_date);
            $fineAmount = $daysLate * $settings->fine_per_day;
        }

        $bookIssue->update([
            'return_date' => $returnDate,
            'status' => 'returned',
            'fine_amount' => $fineAmount,
            'fine_paid' => $fineAmount > 0 ? false : true,
            'remarks' => $validated['remarks'] ?? $bookIssue->remarks,
            'returned_to' => Auth::id(),
        ]);

        // Update book available copies
        $book = $bookIssue->book;
        $book->increment('available_copies');
        
        if ($book->available_copies > 0 && $book->status !== 'available') {
            $book->update(['status' => 'available']);
        }

        $message = 'Book returned successfully!';
        if ($fineAmount > 0) {
            $message .= ' Fine Amount: ৳' . number_format($fineAmount, 2);
        }

        return redirect()->route('book-issues.show', $bookIssue)->with('success', $message);
    }

    public function payFine(Request $request, BookIssue $bookIssue)
    {
        if ($bookIssue->fine_paid || $bookIssue->fine_amount <= 0) {
            return redirect()->back()->with('error', 'No pending fine for this issue!');
        }

        $bookIssue->update(['fine_paid' => true]);

        return redirect()->back()->with('success', 'Fine paid successfully!');
    }

    public function overdue()
    {
        $overdueIssues = BookIssue::overdue()
            ->with(['book', 'student', 'teacher'])
            ->get()
            ->map(function ($issue) {
                $issue->calculated_fine = $issue->calculateFine();
                return $issue;
            });

        return view('book-issues.overdue', compact('overdueIssues'));
    }

    public function myBooks(Request $request)
    {
        $user = Auth::user();
        
        // Determine if user is student or teacher
        $borrowerType = $request->input('type', 'student');
        
        if ($borrowerType === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return redirect()->back()->with('error', 'Student profile not found!');
            }
            $issues = BookIssue::where('student_id', $student->id)
                ->with(['book', 'issuer'])
                ->latest()
                ->paginate(20);
        } else {
            $teacher = Teacher::where('user_id', $user->id)->first();
            if (!$teacher) {
                return redirect()->back()->with('error', 'Teacher profile not found!');
            }
            $issues = BookIssue::where('teacher_id', $teacher->id)
                ->with(['book', 'issuer'])
                ->latest()
                ->paginate(20);
        }

        return view('book-issues.my-books', compact('issues'));
    }

    public function renewBook(BookIssue $bookIssue)
    {
        if ($bookIssue->status !== 'issued') {
            return redirect()->back()->with('error', 'Only issued books can be renewed!');
        }

        if ($bookIssue->isOverdue()) {
            return redirect()->back()->with('error', 'Cannot renew overdue books! Please return and clear fine first.');
        }

        $settings = LibrarySetting::getSettings();
        $renewalCount = BookIssue::where('book_id', $bookIssue->book_id)
            ->where(function ($q) use ($bookIssue) {
                $q->where('student_id', $bookIssue->student_id)
                    ->orWhere('teacher_id', $bookIssue->teacher_id);
            })
            ->where('created_at', '>', $bookIssue->issue_date)
            ->count();

        if ($renewalCount >= $settings->max_renewal_times) {
            return redirect()->back()->with('error', 'Maximum renewal limit reached!');
        }

        $issueDays = $bookIssue->student_id 
            ? $settings->student_issue_days 
            : $settings->teacher_issue_days;

        $newDueDate = Carbon::now()->addDays($issueDays);
        $bookIssue->update(['due_date' => $newDueDate]);

        return redirect()->back()->with('success', 'Book renewed successfully! New due date: ' . $newDueDate->format('d M, Y'));
    }

    public function history(Request $request)
    {
        $query = BookIssue::with(['book', 'student', 'teacher'])
            ->where('status', 'returned');

        if ($request->filled('search')) {
            $query->where('issue_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('return_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('return_date', '<=', $request->date_to);
        }

        $history = $query->latest('return_date')->paginate(20);

        return view('book-issues.history', compact('history'));
    }
}