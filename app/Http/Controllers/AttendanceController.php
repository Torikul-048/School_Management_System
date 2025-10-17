<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance dashboard
     */
    public function index(Request $request)
    {
        $classes = Classes::with('sections')->orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::all();
        
        // Initialize with empty paginator
        $attendances = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        $stats = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'on_leave' => 0,
            'total' => 0,
        ];

        if ($request->filled(['class_id', 'section_id', 'date'])) {
            $date = $request->date ?? today()->format('Y-m-d');
            
            $query = Attendance::with(['attendable', 'subject'])
                ->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->whereDate('date', $date);

            // Get stats before pagination
            $allAttendances = $query->get();
            $stats = [
                'present' => $allAttendances->where('status', 'present')->count(),
                'absent' => $allAttendances->where('status', 'absent')->count(),
                'late' => $allAttendances->where('status', 'late')->count(),
                'on_leave' => $allAttendances->where('status', 'on-leave')->count(),
                'total' => $allAttendances->count(),
            ];

            // Paginate the results
            $attendances = $query->paginate(15)->appends($request->query());
        }

        return view('attendance.index', compact('classes', 'sections', 'subjects', 'attendances', 'stats'));
    }

    /**
     * Show form for marking attendance
     */
    public function create(Request $request)
    {
        $classes = Classes::with('sections')->get();
        $subjects = Subject::all();
        $students = collect();

        if ($request->filled(['class_id', 'section_id'])) {
            $students = Student::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->get();

            // Get today's attendance if already marked
            $date = $request->date ?? today()->format('Y-m-d');
            $existingAttendance = Attendance::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('attendable_id');

            $students->each(function ($student) use ($existingAttendance) {
                $student->attendance_status = $existingAttendance->get($student->id)->status ?? 'present';
            });
        }

        return view('attendance.create', compact('classes', 'subjects', 'students'));
    }

    /**
     * Store attendance records
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,half-day,on-leave',
            'attendance.*.remarks' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['attendance'] as $record) {
                Attendance::updateOrCreate(
                    [
                        'attendable_type' => Student::class,
                        'attendable_id' => $record['student_id'],
                        'class_id' => $validated['class_id'],
                        'section_id' => $validated['section_id'],
                        'date' => $validated['date'],
                        'subject_id' => $validated['subject_id'],
                    ],
                    [
                        'status' => $record['status'],
                        'remarks' => $record['remarks'] ?? null,
                        'marked_by' => Auth::id(),
                        'check_in_time' => $record['status'] === 'present' ? now()->format('H:i:s') : null,
                    ]
                );
            }

            DB::commit();

            // TODO: Send SMS notifications for absent students
            $this->sendAbsentNotifications($validated['class_id'], $validated['section_id'], $validated['date']);

            return redirect()->route('attendance.index', [
                'class_id' => $validated['class_id'],
                'section_id' => $validated['section_id'],
                'date' => $validated['date']
            ])->with('success', 'Attendance marked successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to mark attendance: ' . $e->getMessage());
        }
    }

    /**
     * Display attendance reports
     */
    public function reports(Request $request)
    {
        $classes = Classes::with('sections')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $reportType = $request->report_type ?? 'daily';
        $reportData = null;
        $students = collect();

        if ($request->filled(['class_id', 'section_id'])) {
            // Get students for the selected class and section
            $students = Student::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->get();

            switch ($reportType) {
                case 'daily':
                    $reportData = $this->getDailyReport($request);
                    break;
                case 'monthly':
                    $reportData = $this->getMonthlyReport($request);
                    break;
                case 'yearly':
                    $reportData = $this->getYearlyReport($request);
                    break;
                case 'student':
                    $reportData = $this->getStudentReport($request);
                    break;
            }
        }

        return view('attendance.reports', compact('classes', 'academicYears', 'reportType', 'reportData', 'students'));
    }

    /**
     * Get daily attendance report
     */
    private function getDailyReport($request)
    {
        $date = $request->date ?? today()->format('Y-m-d');

        $attendances = Attendance::with(['attendable'])
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereDate('date', $date)
            ->get();

        $data = [];
        foreach ($attendances as $attendance) {
            $data[] = [
                $attendance->attendable ? $attendance->attendable->first_name . ' ' . $attendance->attendable->last_name : 'N/A',
                $attendance->attendable ? $attendance->attendable->admission_number : 'N/A',
                ucfirst($attendance->status),
                $attendance->check_in_time ?? 'N/A',
                $attendance->remarks ?? '-',
            ];
        }

        return [
            'title' => 'Daily Attendance Report - ' . date('F d, Y', strtotime($date)),
            'summary' => [
                'total' => $attendances->count(),
                'present' => $attendances->where('status', 'present')->count(),
                'absent' => $attendances->where('status', 'absent')->count(),
                'late' => $attendances->where('status', 'late')->count(),
            ],
            'columns' => ['Student Name', 'Admission No', 'Status', 'Check In', 'Remarks'],
            'data' => $data,
        ];
    }

    /**
     * Get monthly attendance report
     */
    private function getMonthlyReport($request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $students = Student::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('status', 'active')
            ->get();

        $data = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLate = 0;

        foreach ($students as $student) {
            $attendances = Attendance::where('attendable_type', Student::class)
                ->where('attendable_id', $student->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $totalDays = $attendances->count();
            $presentDays = $attendances->where('status', 'present')->count();
            $absentDays = $attendances->where('status', 'absent')->count();
            $lateDays = $attendances->where('status', 'late')->count();
            $percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

            $totalPresent += $presentDays;
            $totalAbsent += $absentDays;
            $totalLate += $lateDays;

            $data[] = [
                $student->first_name . ' ' . $student->last_name,
                $student->admission_number,
                $totalDays,
                $presentDays,
                $absentDays,
                $lateDays,
                $percentage . '%',
            ];
        }

        return [
            'title' => 'Monthly Attendance Report - ' . date('F Y', strtotime($month)),
            'summary' => [
                'total_students' => $students->count(),
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
                'total_late' => $totalLate,
            ],
            'columns' => ['Student Name', 'Admission No', 'Total Days', 'Present', 'Absent', 'Late', 'Percentage'],
            'data' => $data,
        ];
    }

    /**
     * Get yearly attendance report
     */
    private function getYearlyReport($request)
    {
        $year = $request->year ?? now()->year;
        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);

        $students = Student::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('status', 'active')
            ->get();

        $data = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLate = 0;

        foreach ($students as $student) {
            $attendances = Attendance::where('attendable_type', Student::class)
                ->where('attendable_id', $student->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $totalDays = $attendances->count();
            $presentDays = $attendances->where('status', 'present')->count();
            $absentDays = $attendances->where('status', 'absent')->count();
            $lateDays = $attendances->where('status', 'late')->count();
            $percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

            $totalPresent += $presentDays;
            $totalAbsent += $absentDays;
            $totalLate += $lateDays;

            $data[] = [
                $student->first_name . ' ' . $student->last_name,
                $student->admission_number,
                $totalDays,
                $presentDays,
                $absentDays,
                $lateDays,
                $percentage . '%',
            ];
        }

        return [
            'title' => 'Yearly Attendance Report - ' . $year,
            'summary' => [
                'total_students' => $students->count(),
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
                'total_late' => $totalLate,
            ],
            'columns' => ['Student Name', 'Admission No', 'Total Days', 'Present', 'Absent', 'Late', 'Percentage'],
            'data' => $data,
        ];
    }

    /**
     * Get individual student attendance report
     */
    private function getStudentReport($request)
    {
        if (!$request->filled('student_id')) {
            return [
                'title' => 'Student Attendance Report',
                'summary' => [],
                'columns' => [],
                'data' => [],
            ];
        }

        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $student = Student::find($request->student_id);
        $attendances = Attendance::where('attendable_type', Student::class)
            ->where('attendable_id', $request->student_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $data = [];
        foreach ($attendances as $attendance) {
            $data[] = [
                date('M d, Y', strtotime($attendance->date)),
                ucfirst($attendance->status),
                $attendance->check_in_time ?? 'N/A',
                $attendance->check_out_time ?? 'N/A',
                $attendance->remarks ?? '-',
            ];
        }

        return [
            'title' => 'Student Attendance Report - ' . ($student ? $student->first_name . ' ' . $student->last_name : 'Unknown'),
            'summary' => [
                'total_days' => $attendances->count(),
                'present' => $attendances->where('status', 'present')->count(),
                'absent' => $attendances->where('status', 'absent')->count(),
                'late' => $attendances->where('status', 'late')->count(),
            ],
            'columns' => ['Date', 'Status', 'Check In', 'Check Out', 'Remarks'],
            'data' => $data,
        ];
    }

    /**
     * Show student's own attendance
     */
    public function myAttendance()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found!');
        }

        $currentMonth = now()->format('Y-m');
        $attendances = Attendance::where('attendable_type', Student::class)
            ->where('attendable_id', $student->id)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->orderBy('date', 'desc')
            ->get();

        $stats = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'on_leave' => $attendances->where('status', 'on-leave')->count(),
        ];

        $stats['percentage'] = $stats['total'] > 0 
            ? round(($stats['present'] / $stats['total']) * 100, 2) 
            : 0;

        return view('attendance.my-attendance', compact('attendances', 'stats', 'student'));
    }

    /**
     * Send SMS notifications for absent students
     */
    private function sendAbsentNotifications($classId, $sectionId, $date)
    {
        $absentAttendances = Attendance::with(['attendable'])
            ->where('attendable_type', Student::class)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->whereDate('date', $date)
            ->where('status', 'absent')
            ->get();

        foreach ($absentAttendances as $attendance) {
            $student = $attendance->attendable;
            \Log::info("SMS notification: Student {$student->first_name} absent on {$date}");
        }
    }

    /**
     * Display the specified attendance record
     */
    public function show(Attendance $attendance)
    {
        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show form for editing attendance
     */
    public function edit(Request $request)
    {
        $classes = Classes::with('sections')->get();
        $subjects = Subject::all();
        
        $date = $request->date ?? today()->format('Y-m-d');
        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $students = collect();

        if ($classId && $sectionId) {
            $students = Student::where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->get();

            $existingAttendance = Attendance::where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('attendable_id');

            $students->each(function ($student) use ($existingAttendance) {
                $attendance = $existingAttendance->get($student->id);
                $student->attendance_status = $attendance ? $attendance->status : 'present';
                $student->remarks = $attendance ? $attendance->remarks : '';
            });
        }

        return view('attendance.edit', compact('students', 'classes', 'subjects', 'date', 'classId', 'sectionId'));
    }

    /**
     * Update attendance record
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late,half-day,on-leave',
            'remarks' => 'nullable|string|max:255',
        ]);

        $attendance->update($validated);

        return redirect()->back()->with('success', 'Attendance updated successfully!');
    }

    /**
     * Remove attendance record
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
        ]);

        Attendance::where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->whereDate('date', $validated['date'])
            ->delete();

        return redirect()->route('attendance.index')->with('success', 'Attendance record deleted successfully!');
    }
}
