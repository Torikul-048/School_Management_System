<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Student;
use App\Models\FeePayment;
use App\Models\BookIssue;
use App\Models\Announcement;
use App\Models\Message;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentPortalController extends Controller
{
    /**
     * Show student dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student record not found.');
        }

        // Get today's attendance
        $todayAttendance = Attendance::where('attendable_type', 'App\Models\Student')
            ->where('attendable_id', $student->id)
            ->whereDate('date', today())
            ->first();

        // Get upcoming exams
        $upcomingExams = Exam::with('schedules')
            ->where('status', 'scheduled')
            ->whereHas('schedules', function($q) use ($student) {
                $q->where('class_id', $student->class_id);
            })
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Get recent announcements
        $announcements = Announcement::active()
            ->latest()
            ->take(5)
            ->get();

        // Get pending fees using FeeInvoice model
        $pendingFees = \App\Models\FeeInvoice::where('student_id', $student->id)
            ->where('status', 'pending')
            ->sum('total_amount');

        // Get issued books
        $issuedBooks = BookIssue::where('student_id', $student->id)
            ->where('status', 'issued')
            ->count();

        // Get attendance percentage
        $totalAttendance = Attendance::where('attendable_type', 'App\Models\Student')
            ->where('attendable_id', $student->id)
            ->count();
        $presentCount = Attendance::where('attendable_type', 'App\Models\Student')
            ->where('attendable_id', $student->id)
            ->where('status', 'present')
            ->count();
        $attendancePercentage = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 2) : 0;

        return view('student.dashboard', compact(
            'student',
            'todayAttendance',
            'upcomingExams',
            'announcements',
            'pendingFees',
            'issuedBooks',
            'attendancePercentage'
        ));
    }

    /**
     * Show student profile
     */
    public function profile()
    {
        $student = Auth::user()->student;
        return view('student.profile', compact('student'));
    }

    /**
     * Update student profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'blood_group' => 'nullable|string|max:10',
            'parent_phone' => 'nullable|string|max:20',
        ]);

        $student->update($validated);

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show timetable
     */
    public function timetable()
    {
        $student = Auth::user()->student;
        
        $schedules = DB::table('schedules')
            ->join('subjects', 'schedules.subject_id', '=', 'subjects.id')
            ->join('teachers', 'schedules.teacher_id', '=', 'teachers.id')
            ->where('schedules.class_id', $student->class_id)
            ->select(
                'schedules.*',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'teachers.first_name',
                'teachers.last_name'
            )
            ->orderBy('schedules.day')
            ->orderBy('schedules.start_time')
            ->get()
            ->groupBy('day');

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('student.timetable', compact('schedules', 'days'));
    }

    /**
     * Show attendance record
     */
    public function attendance()
    {
        $student = Auth::user()->student;
        
        $attendances = Attendance::where('attendable_type', 'App\Models\Student')
            ->where('attendable_id', $student->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $student->id)->count(),
            'present' => Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $student->id)->where('status', 'present')->count(),
            'absent' => Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $student->id)->where('status', 'absent')->count(),
            'late' => Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $student->id)->where('status', 'late')->count(),
        ];

        $stats['percentage'] = $stats['total'] > 0 
            ? round(($stats['present'] / $stats['total']) * 100, 2) 
            : 0;

        return view('student.attendance', compact('attendances', 'stats'));
    }

    /**
     * Show exam schedule
     */
    public function exams()
    {
        $student = Auth::user()->student;
        
        $exams = Exam::with(['schedules' => function($q) use ($student) {
                $q->where('class_id', $student->class_id);
            }])
            ->whereHas('schedules', function($q) use ($student) {
                $q->where('class_id', $student->class_id);
            })
            ->orderBy('start_date', 'desc')
            ->get();

        return view('student.exams', compact('exams'));
    }

    /**
     * Show marks
     */
    public function marks()
    {
        $student = Auth::user()->student;
        
        $marks = Mark::with(['exam', 'subject'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('exam_id');

        $exams = Exam::whereIn('id', $marks->keys())->get()->keyBy('id');

        return view('student.marks', compact('marks', 'exams'));
    }

    /**
     * Download report card
     */
    public function downloadReportCard($examId)
    {
        $student = Auth::user()->student;
        
        $exam = Exam::findOrFail($examId);
        $marks = Mark::with('subject')
            ->where('student_id', $student->id)
            ->where('exam_id', $examId)
            ->get();

        if ($marks->isEmpty()) {
            return redirect()->back()->with('error', 'No marks found for this exam.');
        }

        // Generate PDF (simplified version)
        return view('student.report-card-pdf', compact('student', 'exam', 'marks'));
    }

    /**
     * Show assignments
     */
    public function assignments()
    {
        $student = Auth::user()->student;
        
        // Assuming we have an assignments table
        $assignments = DB::table('assignments')
            ->where('class_id', $student->class_id)
            ->orderBy('due_date', 'desc')
            ->paginate(15);

        return view('student.assignments', compact('assignments'));
    }

    /**
     * Download assignment
     */
    public function downloadAssignment($id)
    {
        $assignment = DB::table('assignments')->find($id);
        
        if (!$assignment || !$assignment->file_path) {
            return redirect()->back()->with('error', 'Assignment file not found.');
        }

        return Storage::disk('public')->download($assignment->file_path);
    }

    /**
     * Show fee payments
     */
    public function fees()
    {
        $student = Auth::user()->student;
        
        $feeInvoices = \App\Models\FeeInvoice::where('student_id', $student->id)
            ->orderBy('due_date', 'desc')
            ->get();

        $payments = FeePayment::where('student_id', $student->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        $totalDue = \App\Models\FeeInvoice::where('student_id', $student->id)
            ->where('status', 'pending')
            ->sum('total_amount');

        $totalPaid = $payments->sum('amount_paid');

        return view('student.fees', compact('feeInvoices', 'payments', 'totalDue', 'totalPaid'));
    }

    /**
     * Show library books
     */
    public function library()
    {
        $student = Auth::user()->student;
        
        $issuedBooks = BookIssue::with('book')
            ->where('student_id', $student->id)
            ->orderBy('issue_date', 'desc')
            ->get();

        return view('student.library', compact('issuedBooks'));
    }

    /**
     * Show announcements
     */
    public function announcements()
    {
        $announcements = Announcement::active()
            ->latest()
            ->paginate(15);

        return view('student.announcements', compact('announcements'));
    }

    /**
     * Show messages
     */
    public function messages()
    {
        $user = Auth::user();
        
        $messages = Message::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.messages', compact('messages'));
    }

    /**
     * Send message
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $validated['sender_id'] = Auth::id();
        $validated['status'] = 'unread';

        Message::create($validated);

        return redirect()->route('student.messages')->with('success', 'Message sent successfully!');
    }

    /**
     * Show leave requests
     */
    public function leaveRequests()
    {
        $student = Auth::user()->student;
        
        $leaveRequests = LeaveRequest::where('leaveable_type', 'App\Models\Student')
            ->where('leaveable_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.leave-requests', compact('leaveRequests'));
    }

    /**
     * Apply for leave
     */
    public function applyLeave(Request $request)
    {
        $student = Auth::user()->student;

        $validated = $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $validated['leaveable_type'] = 'App\Models\Student';
        $validated['leaveable_id'] = $student->id;
        $validated['status'] = 'pending';

        LeaveRequest::create($validated);

        return redirect()->route('student.leave-requests')->with('success', 'Leave request submitted successfully!');
    }
}
