<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\BookIssue;
use App\Models\Announcement;
use App\Models\Message;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParentPortalController extends Controller
{
    /**
     * Get parent's children
     */
    protected function getChildren()
    {
        return Student::where('parent_user_id', Auth::id())
            ->with(['class', 'section', 'user'])
            ->where('status', 'active')
            ->get();
    }

    /**
     * Parent Dashboard
     */
    public function dashboard()
    {
        $children = $this->getChildren();
        
        if ($children->isEmpty()) {
            return redirect()->route('dashboard')
                ->with('error', 'No children found linked to your account. Please contact administration.');
        }

        $data = [];
        
        foreach ($children as $child) {
            // Get attendance summary for this month
            $attendanceThisMonth = Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $child->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->get();

            // Get pending fees
            $pendingFees = FeeInvoice::where('student_id', $child->id)
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->sum('total_amount');

            // Get recent exam results
            $recentResults = Mark::where('student_id', $child->id)
                ->with(['exam', 'subject'])
                ->latest()
                ->take(3)
                ->get();

            // Get recent leave requests
            $recentLeaves = LeaveRequest::where('leaveable_type', 'App\Models\Student')
                ->where('leaveable_id', $child->id)
                ->latest()
                ->take(3)
                ->get();

            $data[$child->id] = [
                'student' => $child,
                'attendance' => [
                    'total' => $attendanceThisMonth->count(),
                    'present' => $attendanceThisMonth->where('status', 'present')->count(),
                    'absent' => $attendanceThisMonth->where('status', 'absent')->count(),
                    'late' => $attendanceThisMonth->where('status', 'late')->count(),
                    'percentage' => $attendanceThisMonth->count() > 0 
                        ? round(($attendanceThisMonth->where('status', 'present')->count() / $attendanceThisMonth->count()) * 100, 1) 
                        : 0,
                ],
                'pendingFees' => $pendingFees,
                'recentResults' => $recentResults,
                'recentLeaves' => $recentLeaves,
            ];
        }

        // Get recent announcements
        $announcements = Announcement::where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('parent.dashboard', [
            'children' => $children,
            'childrenData' => $data,
            'announcements' => $announcements,
        ]);
    }

    /**
     * View child profile
     */
    public function childProfile($studentId)
    {
        $child = Student::where('id', $studentId)
            ->where('parent_user_id', Auth::id())
            ->with(['class', 'section', 'user', 'academicYear'])
            ->firstOrFail();

        return view('parent.child-profile', compact('child'));
    }

    /**
     * Track child's attendance
     */
    public function attendance(Request $request, $studentId)
    {
        $child = Student::where('id', $studentId)
            ->where('parent_user_id', Auth::id())
            ->firstOrFail();

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendanceRecords = Attendance::where('attendable_type', 'App\Models\Student')
            ->where('attendable_id', $child->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->paginate(30);

        $summary = [
            'total' => Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $child->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->count(),
            'present' => Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $child->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'present')
                ->count(),
            'absent' => Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $child->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'absent')
                ->count(),
            'late' => Attendance::where('attendable_type', 'App\Models\Student')
                ->where('attendable_id', $child->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'late')
                ->count(),
        ];

        return view('parent.attendance', compact('child', 'attendanceRecords', 'summary', 'month', 'year'));
    }

    /**
     * View exam results and report cards
     */
    public function results($studentId)
    {
        $child = Student::where('id', $studentId)
            ->where('parent_user_id', Auth::id())
            ->with(['class'])
            ->firstOrFail();

        // Get all exams with marks
        $exams = Exam::whereHas('marks', function($q) use ($child) {
                $q->where('student_id', $child->id);
            })
            ->with(['marks' => function($q) use ($child) {
                $q->where('student_id', $child->id)->with('subject');
            }])
            ->orderBy('start_date', 'desc')
            ->get();

        return view('parent.results', compact('child', 'exams'));
    }

    /**
     * View fee invoices and payment history
     */
    public function fees($studentId)
    {
        $child = Student::where('id', $studentId)
            ->where('parent_user_id', Auth::id())
            ->firstOrFail();

        $invoices = FeeInvoice::where('student_id', $child->id)
            ->with('feeCategory')
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        $payments = FeePayment::where('student_id', $child->id)
            ->with('feeInvoice')
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        $summary = [
            'total' => FeeInvoice::where('student_id', $child->id)->sum('total_amount'),
            'paid' => FeePayment::where('student_id', $child->id)->sum('amount_paid'),
            'pending' => FeeInvoice::where('student_id', $child->id)
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->sum('total_amount'),
        ];

        return view('parent.fees', compact('child', 'invoices', 'payments', 'summary'));
    }

    /**
     * Process online payment
     */
    public function payOnline(Request $request, $invoiceId)
    {
        $invoice = FeeInvoice::findOrFail($invoiceId);
        
        $child = Student::where('id', $invoice->student_id)
            ->where('parent_user_id', Auth::id())
            ->firstOrFail();

        // In a real application, integrate with payment gateway here
        // For now, we'll just mark as paid
        
        return redirect()->route('parent.fees', $child->id)
            ->with('info', 'Payment gateway integration required. Please contact admin for payment.');
    }

    /**
     * View notifications
     */
    public function notifications()
    {
        $children = $this->getChildren();
        
        // Get announcements
        $announcements = Announcement::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('parent.notifications', compact('children', 'announcements'));
    }

    /**
     * View homework/assignments for child
     */
    public function homework($studentId)
    {
        $child = Student::where('id', $studentId)
            ->where('parent_user_id', Auth::id())
            ->with(['class', 'section'])
            ->firstOrFail();

        // Get assignments for child's class (if assignments table exists)
        $assignments = collect();
        if (DB::getSchemaBuilder()->hasTable('assignments')) {
            $query = DB::table('assignments')->where('class_id', $child->class_id);
            
            // Only filter by section if the column exists
            if (DB::getSchemaBuilder()->hasColumn('assignments', 'section_id')) {
                $query->where('section_id', $child->section_id);
            }
            
            $assignments = $query->orderBy('due_date', 'desc')->paginate(15);
        }

        return view('parent.homework', compact('child', 'assignments'));
    }

    /**
     * View messages
     */
    public function messages()
    {
        $children = $this->getChildren();
        
        $messages = Message::where(function($q) {
                $q->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get teachers and admins for messaging
        $teachers = User::role('Teacher')->get();
        $admins = User::role('Admin')->get();

        return view('parent.messages', compact('children', 'messages', 'teachers', 'admins'));
    }

    /**
     * Send message to teacher/admin
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('parent.messages')
            ->with('success', 'Message sent successfully!');
    }

    /**
     * View child's leave requests
     */
    public function leaveRequests($studentId)
    {
        $child = Student::where('id', $studentId)
            ->where('parent_user_id', Auth::id())
            ->firstOrFail();

        $leaveRequests = LeaveRequest::where('leaveable_type', 'App\Models\Student')
            ->where('leaveable_id', $child->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $leaveTypes = DB::table('leave_types')->get();

        return view('parent.leave-requests', compact('child', 'leaveRequests', 'leaveTypes'));
    }

    /**
     * Apply leave on behalf of child
     */
    public function applyLeave(Request $request, $studentId)
    {
        $child = Student::where('id', $studentId)
            ->where('parent_user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        LeaveRequest::create([
            'leaveable_type' => 'App\Models\Student',
            'leaveable_id' => $child->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('parent.leave-requests', $child->id)
            ->with('success', 'Leave request submitted successfully!');
    }

    /**
     * View library books borrowed by child
     */
    public function library($studentId)
    {
        $child = Student::where('id', $studentId)
            ->where('parent_user_id', Auth::id())
            ->firstOrFail();

        $borrowedBooks = BookIssue::where('student_id', $child->id)
            ->with('book')
            ->orderBy('issue_date', 'desc')
            ->paginate(15);

        return view('parent.library', compact('child', 'borrowedBooks'));
    }
}
