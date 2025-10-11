<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherLeave;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    protected function getTeacher()
    {
        return Teacher::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $teacher = $this->getTeacher();
        
        $leaves = TeacherLeave::where('teacher_id', $teacher->id)
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $leaveStats = [
            'total' => $leaves->total(),
            'approved' => TeacherLeave::where('teacher_id', $teacher->id)->where('status', 'approved')->count(),
            'pending' => TeacherLeave::where('teacher_id', $teacher->id)->where('status', 'pending')->count(),
            'rejected' => TeacherLeave::where('teacher_id', $teacher->id)->where('status', 'rejected')->count(),
        ];
        
        return view('teacher.leaves.index', compact('teacher', 'leaves', 'leaveStats'));
    }

    public function create()
    {
        $teacher = $this->getTeacher();
        $leaveTypes = LeaveType::where('is_active', true)->get();
        
        return view('teacher.leaves.create', compact('teacher', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
        
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
        }
        
        $days = \Carbon\Carbon::parse($validated['start_date'])
            ->diffInDays(\Carbon\Carbon::parse($validated['end_date'])) + 1;
        
        TeacherLeave::create([
            'teacher_id' => $teacher->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days' => $days,
            'reason' => $validated['reason'],
            'attachment' => $attachmentPath,
            'status' => 'pending'
        ]);
        
        return redirect()->route('teacher.leaves.index')
            ->with('success', 'Leave application submitted successfully');
    }

    public function show($id)
    {
        $teacher = $this->getTeacher();
        
        $leave = TeacherLeave::where('teacher_id', $teacher->id)
            ->with('leaveType')
            ->findOrFail($id);
        
        return view('teacher.leaves.show', compact('teacher', 'leave'));
    }
}
