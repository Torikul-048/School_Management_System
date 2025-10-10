<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherLeave;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeacherLeaveController extends Controller
{
    /**
     * Display a listing of teacher leave requests
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = TeacherLeave::with(['teacher', 'approver']);

        // If teacher, show only their leaves
        if ($user->hasRole('Teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                $query->where('teacher_id', $teacher->id);
            }
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $leaves = $query->latest()->paginate(15);

        $teachers = collect();
        $leaveTypes = LeaveType::all();

        // Load teachers for admin/super admin
        if ($user->hasAnyRole(['Super Admin', 'Admin'])) {
            $teachers = Teacher::active()->get();
        }

        // Calculate statistics
        $stats = [
            'pending' => TeacherLeave::where('status', 'pending')->count(),
            'approved' => TeacherLeave::where('status', 'approved')->count(),
            'rejected' => TeacherLeave::where('status', 'rejected')->count(),
            'total' => TeacherLeave::count(),
        ];

        return view('teacher-leaves.index', compact('leaves', 'teachers', 'leaveTypes', 'stats'));
    }

    /**
     * Show the form for creating a new leave request
     */
    public function create()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found!');
        }

        $leaveTypes = LeaveType::where('applicable_to', 'teacher')
            ->orWhere('applicable_to', 'both')
            ->get();

        // Get leave balances
        $leaveBalances = $this->getLeaveBalance($teacher);

        return view('teacher-leaves.create', compact('leaveTypes', 'leaveBalances'));
    }

    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found!');
        }

        DB::beginTransaction();
        try {
            // Calculate leave days
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $totalDays = $startDate->diffInDays($endDate) + 1;

            // Check leave balance
            $leaveType = LeaveType::find($validated['leave_type_id']);
            $leaveBalance = $this->getLeaveBalance($teacher);
            
            if ($leaveType->is_paid) {
                $usedLeaves = $leaveBalance->where('leave_type_id', $leaveType->id)->first();
                $availableLeaves = $leaveType->max_days_per_year - ($usedLeaves->used_days ?? 0);

                if ($totalDays > $availableLeaves) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Insufficient leave balance! Available: {$availableLeaves} days");
                }
            }

            // Handle file upload
            $documentPath = null;
            if ($request->hasFile('supporting_document')) {
                $documentPath = $request->file('supporting_document')->store('teacher-leaves', 'public');
            }

            // Create leave request
            $leave = TeacherLeave::create([
                'teacher_id' => $teacher->id,
                'leave_type_id' => $validated['leave_type_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'total_days' => $totalDays,
                'reason' => $validated['reason'],
                'supporting_document' => $documentPath,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('teacher-leaves.index')
                ->with('success', 'Leave request submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit leave request: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified leave request
     */
    public function show(TeacherLeave $teacherLeave)
    {
        $teacherLeave->load(['teacher', 'leaveType', 'approver']);

        return view('teacher-leaves.show', compact('teacherLeave'));
    }

    /**
     * Approve leave request
     */
    public function approve(TeacherLeave $teacherLeave)
    {
        if ($teacherLeave->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending leave requests can be approved!');
        }

        DB::beginTransaction();
        try {
            $teacherLeave->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Update teacher status to on_leave if leave starts today or already started
            if (Carbon::parse($teacherLeave->start_date)->lte(now())) {
                $teacherLeave->teacher->update(['status' => 'on_leave']);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Leave request approved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to approve leave request: ' . $e->getMessage());
        }
    }

    /**
     * Reject leave request
     */
    public function reject(Request $request, TeacherLeave $teacherLeave)
    {
        if ($teacherLeave->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending leave requests can be rejected!');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $teacherLeave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->back()
            ->with('success', 'Leave request rejected successfully!');
    }

    /**
     * Cancel leave request
     */
    public function cancel(TeacherLeave $teacherLeave)
    {
        $user = Auth::user();

        // Only teacher can cancel their own leave
        if (!$user->teacher || $user->teacher->id !== $teacherLeave->teacher_id) {
            return redirect()->back()
                ->with('error', 'You can only cancel your own leave requests!');
        }

        if ($teacherLeave->status !== 'pending' && $teacherLeave->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Only pending or approved leave requests can be cancelled!');
        }

        $teacherLeave->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', 'Leave request cancelled successfully!');
    }

    /**
     * Get leave balance for a teacher
     */
    public function balance()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found!');
        }

        $leaveBalances = $this->getLeaveBalance($teacher);

        return view('teacher-leaves.balance', compact('teacher', 'leaveBalances'));
    }

    /**
     * Calculate leave balance for a teacher
     */
    private function getLeaveBalance($teacher)
    {
        $currentYear = now()->year;

        $leaveTypes = LeaveType::where('applicable_to', 'teacher')
            ->orWhere('applicable_to', 'both')
            ->get();

        $balances = [];

        foreach ($leaveTypes as $leaveType) {
            $usedDays = TeacherLeave::where('teacher_id', $teacher->id)
                ->where('leave_type_id', $leaveType->id)
                ->where('status', 'approved')
                ->whereYear('start_date', $currentYear)
                ->sum('total_days');

            $balances[] = [
                'leave_type_id' => $leaveType->id,
                'leave_type' => $leaveType->name,
                'total_days' => $leaveType->max_days_per_year,
                'used_days' => $usedDays,
                'remaining_days' => max(0, $leaveType->max_days_per_year - $usedDays),
                'is_paid' => $leaveType->is_paid,
            ];
        }

        return collect($balances);
    }

    /**
     * Display leave history
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found!');
        }

        $query = TeacherLeave::where('teacher_id', $teacher->id)
            ->with(['leaveType', 'approver']);

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        } else {
            $query->whereYear('start_date', now()->year);
        }

        $leaves = $query->latest()->get();

        $stats = [
            'total_leaves' => $leaves->count(),
            'approved_leaves' => $leaves->where('status', 'approved')->count(),
            'rejected_leaves' => $leaves->where('status', 'rejected')->count(),
            'pending_leaves' => $leaves->where('status', 'pending')->count(),
            'total_days' => $leaves->where('status', 'approved')->sum('total_days'),
        ];

        return view('teacher-leaves.history', compact('leaves', 'stats'));
    }

    /**
     * Delete leave request
     */
    public function destroy(TeacherLeave $teacherLeave)
    {
        $user = Auth::user();

        // Only teacher can delete their own pending leave requests
        if (!$user->hasAnyRole(['Super Admin', 'Admin'])) {
            if (!$user->teacher || $user->teacher->id !== $teacherLeave->teacher_id) {
                return redirect()->back()
                    ->with('error', 'You can only delete your own leave requests!');
            }

            if ($teacherLeave->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Only pending leave requests can be deleted!');
            }
        }

        $teacherLeave->delete();

        return redirect()->route('teacher-leaves.index')
            ->with('success', 'Leave request deleted successfully!');
    }
}
