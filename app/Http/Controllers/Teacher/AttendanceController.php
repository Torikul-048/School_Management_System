<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\SubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    protected function getTeacher()
    {
        return Teacher::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $teacher = $this->getTeacher();
        
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
        
        // If teacher has no subject assignments, get all classes
        if ($classIds->isEmpty()) {
            $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")
                ->orderBy('name')
                ->get();
        } else {
            $classes = Classes::whereIn('id', $classIds)
                ->orderByRaw("CAST(numeric_name AS INTEGER)")
                ->orderBy('name')
                ->get();
        }
        
        $recentAttendance = Attendance::whereIn('class_id', $classIds)
            ->where('date', '>=', now()->subDays(7))
            ->with(['student', 'class'])
            ->orderBy('date', 'desc')
            ->limit(50)
            ->get();
        
        return view('teacher.attendance.index', compact('teacher', 'classes', 'recentAttendance'));
    }

    public function take(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
        
        // If teacher has no subject assignments, get all classes
        if ($classIds->isEmpty()) {
            $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")
                ->orderBy('name')
                ->get();
        } else {
            $classes = Classes::whereIn('id', $classIds)
                ->orderByRaw("CAST(numeric_name AS INTEGER)")
                ->orderBy('name')
                ->get();
        }
        
        $students = collect();
        $selectedClass = null;
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        
        if ($request->has('class_id')) {
            $selectedClass = Classes::find($request->class_id);
            
            // Allow access if teacher has no assignments OR if class is in their assigned classes
            if ($selectedClass && ($classIds->isEmpty() || $classIds->contains($selectedClass->id))) {
                $students = Student::where('class_id', $selectedClass->id)
                    ->where('status', 'active')
                    ->with('user')
                    ->get();
                
                // Get existing attendance for this date
                $existingAttendance = Attendance::where('class_id', $selectedClass->id)
                    ->where('date', $selectedDate)
                    ->pluck('status', 'student_id');
                
                $students->each(function ($student) use ($existingAttendance) {
                    $student->attendance_status = $existingAttendance->get($student->id, 'present');
                });
            }
        }
        
        return view('teacher.attendance.take', compact('teacher', 'classes', 'students', 'selectedClass', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,excused'
        ]);
        
        // Verify teacher has access to this class
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
            
        $hasAccess = $classIds->isEmpty() || SubjectAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->exists();
        
        if (!$hasAccess) {
            return back()->with('error', 'You do not have permission to mark attendance for this class');
        }
        
        DB::beginTransaction();
        try {
            foreach ($validated['attendance'] as $studentId => $status) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $validated['class_id'],
                        'date' => $validated['date']
                    ],
                    [
                        'status' => $status,
                        'marked_by' => Auth::id()
                    ]
                );
            }
            
            DB::commit();
            return redirect()->route('teacher.attendance.index')
                ->with('success', 'Attendance marked successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to mark attendance: ' . $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
        
        // If teacher has no subject assignments, get all classes
        if ($classIds->isEmpty()) {
            $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")
                ->orderBy('name')
                ->get();
        } else {
            $classes = Classes::whereIn('id', $classIds)
                ->orderByRaw("CAST(numeric_name AS INTEGER)")
                ->orderBy('name')
                ->get();
        }
        
        $selectedClass = $request->has('class_id') ? Classes::find($request->class_id) : null;
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        $attendanceData = collect();
        
        if ($selectedClass && ($classIds->isEmpty() || $classIds->contains($selectedClass->id))) {
            $attendanceData = Attendance::where('class_id', $selectedClass->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->with('student')
                ->get()
                ->groupBy('student_id')
                ->map(function ($records) {
                    return [
                        'student' => $records->first()->student,
                        'total' => $records->count(),
                        'present' => $records->where('status', 'present')->count(),
                        'absent' => $records->where('status', 'absent')->count(),
                        'late' => $records->where('status', 'late')->count(),
                        'excused' => $records->where('status', 'excused')->count(),
                        'percentage' => round(($records->where('status', 'present')->count() / $records->count()) * 100, 2)
                    ];
                });
        }
        
        // Calculate statistics
        $statistics = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'rate' => 0
        ];
        
        if ($attendanceData->isNotEmpty()) {
            $statistics['present'] = $attendanceData->sum('present');
            $statistics['absent'] = $attendanceData->sum('absent');
            $statistics['late'] = $attendanceData->sum('late');
            $total = $attendanceData->sum('total');
            $statistics['rate'] = $total > 0 ? round(($statistics['present'] / $total) * 100, 1) : 0;
        }
        
        // Prepare report data for table
        $reportData = $attendanceData->map(function ($data) {
            return [
                'student_name' => $data['student']->user->name ?? 'Unknown',
                'class_name' => $data['student']->class->name ?? 'N/A',
                'present' => $data['present'],
                'absent' => $data['absent'],
                'late' => $data['late'],
                'rate' => $data['percentage']
            ];
        })->values();
        
        return view('teacher.attendance.report', compact('teacher', 'classes', 'selectedClass', 'statistics', 'reportData', 'startDate', 'endDate'));
    }
}
