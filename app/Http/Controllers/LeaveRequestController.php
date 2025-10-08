<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of leave requests
     */
    public function index(Request $request)
    {
        // Initialize variables - ALWAYS defined before any logic
        $students = collect(); // Empty collection as default
        $stats = null;
        
        $query = LeaveRequest::with(['leaveable', 'approver']);

        // Get all students for filter dropdown - always fetch if user is admin/teacher
        if (Auth::user()->hasRole(['Super Admin', 'Admin', 'Teacher'])) {
            try {
                $students = Student::where('status', 'active')
                    ->orderBy('first_name')
                    ->get();
            } catch (\Exception $e) {
                $students = collect(); // Fallback to empty collection
            }
            
            // Calculate stats for admin/teacher
            $stats = [
                'pending' => LeaveRequest::where('status', 'pending')->count(),
                'approved' => LeaveRequest::where('status', 'approved')->count(),
                'rejected' => LeaveRequest::where('status', 'rejected')->count(),
                'total' => LeaveRequest::count(),
            ];
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        // Filter by student (admin/teacher only)
        if ($request->filled('student_id')) {
            $query->where('leaveable_type', Student::class)
                ->where('leaveable_id', $request->student_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // If teacher/admin, show all. If student/parent, show own
        if (Auth::user()->hasRole(['Student', 'Parent'])) {
            if (Auth::user()->student) {
                $query->where(function ($q) {
                    $q->where('leaveable_type', Student::class)
                        ->where('leaveable_id', Auth::user()->student->id);
                });
            } else {
                // If no student record, return empty
                $leaveRequests = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
                return view('leave-requests.index', compact('leaveRequests', 'stats', 'students'));
            }
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('leave-requests.index', compact('leaveRequests', 'stats', 'students'));
    }

    /**
     * Show the form for creating a new leave request
     */
    public function create()
    {
        return view('leave-requests.create');
    }

    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:sick,casual,emergency,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Get student
        $student = Auth::user()->student;
        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found!');
        }

        $validated['leaveable_type'] = Student::class;
        $validated['leaveable_id'] = $student->id;
        $validated['status'] = 'pending';

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('leave-attachments', 'public');
        }

        LeaveRequest::create($validated);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    /**
     * Display the specified leave request
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load('leaveable');
        return view('leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Approve leave request
     */
    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave request approved successfully!');
    }

    /**
     * Reject leave request
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Leave request rejected!');
    }

    /**
     * Cancel leave request
     */
    public function cancel(LeaveRequest $leaveRequest)
    {
        // Only allow cancellation by the requester
        if ($leaveRequest->leaveable_id != Auth::user()->student->id) {
            return redirect()->back()->with('error', 'You can only cancel your own requests!');
        }

        if ($leaveRequest->status != 'pending') {
            return redirect()->back()->with('error', 'Only pending requests can be cancelled!');
        }

        $leaveRequest->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Leave request cancelled successfully!');
    }

    /**
     * Remove the specified leave request
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request deleted successfully!');
    }
}
