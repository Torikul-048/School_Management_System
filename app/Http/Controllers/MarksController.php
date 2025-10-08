<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MarksController extends Controller
{
    /**
     * Display marks entry form
     */
    public function index(Request $request)
    {
        $exams = Exam::where('status', '!=', 'cancelled')->get();
        $classes = Classes::with('sections')->get();
        $subjects = Subject::all();
        $students = collect();
        $marks = collect();

        if ($request->filled(['exam_id', 'class_id', 'section_id', 'subject_id'])) {
            $students = Student::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->get();

            // Get existing marks
            $existingMarks = Mark::where('exam_id', $request->exam_id)
                ->where('subject_id', $request->subject_id)
                ->get()
                ->keyBy('student_id');

            $students->each(function ($student) use ($existingMarks) {
                $mark = $existingMarks->get($student->id);
                $student->obtained_marks = $mark ? $mark->obtained_marks : '';
                $student->grade = $mark ? $mark->grade : '';
                $student->remarks = $mark ? $mark->remarks : '';
            });

            // Get subject full marks
            $schedule = ExamSchedule::where('exam_id', $request->exam_id)
                ->where('class_id', $request->class_id)
                ->where('subject_id', $request->subject_id)
                ->first();

            $marks = $schedule;
        }

        return view('marks.index', compact('exams', 'classes', 'subjects', 'students', 'marks'));
    }

    /**
     * Store marks for students
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|array',
            'marks.*.student_id' => 'required|exists:students,id',
            'marks.*.obtained_marks' => 'required|numeric|min:0',
            'marks.*.remarks' => 'nullable|string|max:255',
        ]);

        // Get exam schedule to validate marks
        $schedule = ExamSchedule::where('exam_id', $validated['exam_id'])
            ->where('class_id', $validated['class_id'])
            ->where('subject_id', $validated['subject_id'])
            ->firstOrFail();

        DB::beginTransaction();
        try {
            foreach ($validated['marks'] as $markData) {
                // Validate obtained marks don't exceed full marks
                if ($markData['obtained_marks'] > $schedule->full_marks) {
                    throw new \Exception("Marks cannot exceed {$schedule->full_marks}");
                }

                // Calculate grade and percentage
                $percentage = ($markData['obtained_marks'] / $schedule->full_marks) * 100;
                $grade = $this->calculateGrade($percentage);

                Mark::updateOrCreate(
                    [
                        'exam_id' => $validated['exam_id'],
                        'student_id' => $markData['student_id'],
                        'subject_id' => $validated['subject_id'],
                    ],
                    [
                        'obtained_marks' => $markData['obtained_marks'],
                        'full_marks' => $schedule->full_marks,
                        'pass_marks' => $schedule->pass_marks,
                        'percentage' => round($percentage, 2),
                        'grade' => $grade,
                        'remarks' => $markData['remarks'] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'Marks entered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to enter marks: ' . $e->getMessage());
        }
    }

    /**
     * Calculate grade based on percentage
     */
    private function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 33) return 'D';
        return 'F';
    }

    /**
     * Show student's report card
     */
    public function reportCard(Request $request)
    {
        $exams = Exam::where('publish_results', true)->get();
        $students = collect();
        $reportData = null;

        if ($request->filled(['exam_id', 'student_id'])) {
            $student = Student::with(['class', 'section'])->findOrFail($request->student_id);
            $exam = Exam::with('academicYear')->findOrFail($request->exam_id);

            $marks = Mark::with('subject')
                ->where('exam_id', $request->exam_id)
                ->where('student_id', $request->student_id)
                ->get();

            $totalMarks = $marks->sum('obtained_marks');
            $totalFullMarks = $marks->sum('full_marks');
            $overallPercentage = $totalFullMarks > 0 
                ? round(($totalMarks / $totalFullMarks) * 100, 2) 
                : 0;
            $overallGrade = $this->calculateGrade($overallPercentage);

            $reportData = [
                'student' => $student,
                'exam' => $exam,
                'marks' => $marks,
                'total_marks' => $totalMarks,
                'total_full_marks' => $totalFullMarks,
                'overall_percentage' => $overallPercentage,
                'overall_grade' => $overallGrade,
            ];
        }

        if ($request->filled('class_id')) {
            $students = Student::where('class_id', $request->class_id)
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->get();
        }

        $classes = Classes::all();

        return view('marks.report-card', compact('exams', 'students', 'classes', 'reportData'));
    }

    /**
     * Download report card as PDF
     */
    public function downloadReportCard(Request $request, $examId, $studentId)
    {
        $student = Student::with(['class', 'section'])->findOrFail($studentId);
        $exam = Exam::with('academicYear')->findOrFail($examId);

        $marks = Mark::with('subject')
            ->where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->get();

        $totalMarks = $marks->sum('obtained_marks');
        $totalFullMarks = $marks->sum('full_marks');
        $overallPercentage = $totalFullMarks > 0 
            ? round(($totalMarks / $totalFullMarks) * 100, 2) 
            : 0;
        $overallGrade = $this->calculateGrade($overallPercentage);

        $data = [
            'student' => $student,
            'exam' => $exam,
            'marks' => $marks,
            'total_marks' => $totalMarks,
            'total_full_marks' => $totalFullMarks,
            'overall_percentage' => $overallPercentage,
            'overall_grade' => $overallGrade,
        ];

        $pdf = Pdf::loadView('marks.report-card-pdf', $data);
        
        return $pdf->download("report-card-{$student->admission_number}-{$exam->name}.pdf");
    }

    /**
     * Show progress tracking
     */
    public function progressTracking(Request $request)
    {
        $students = collect();
        $progressData = collect();

        if ($request->filled(['class_id', 'section_id'])) {
            $students = Student::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('status', 'active')
                ->orderBy('roll_number')
                ->get();

            $exams = Exam::where('publish_results', true)
                ->orderBy('start_date')
                ->get();

            $students->each(function ($student) use ($exams) {
                $student->exam_results = $exams->map(function ($exam) use ($student) {
                    $marks = Mark::where('exam_id', $exam->id)
                        ->where('student_id', $student->id)
                        ->get();

                    $totalMarks = $marks->sum('obtained_marks');
                    $totalFullMarks = $marks->sum('full_marks');
                    $percentage = $totalFullMarks > 0 
                        ? round(($totalMarks / $totalFullMarks) * 100, 2) 
                        : 0;

                    return [
                        'exam_name' => $exam->name,
                        'percentage' => $percentage,
                    ];
                });
            });
        }

        $classes = Classes::with('sections')->get();

        return view('marks.progress-tracking', compact('classes', 'students'));
    }

    /**
     * Display the specified mark
     */
    public function show(Mark $mark)
    {
        $mark->load(['student', 'exam', 'subject']);
        return view('marks.show', compact('mark'));
    }

    /**
     * Update the specified mark
     */
    public function update(Request $request, Mark $mark)
    {
        $validated = $request->validate([
            'obtained_marks' => 'required|numeric|min:0|max:' . $mark->full_marks,
            'remarks' => 'nullable|string|max:255',
        ]);

        $percentage = ($validated['obtained_marks'] / $mark->full_marks) * 100;
        $validated['percentage'] = round($percentage, 2);
        $validated['grade'] = $this->calculateGrade($percentage);

        $mark->update($validated);

        return redirect()->back()->with('success', 'Marks updated successfully!');
    }

    /**
     * Remove the specified mark
     */
    public function destroy(Mark $mark)
    {
        $mark->delete();

        return redirect()->back()->with('success', 'Mark deleted successfully!');
    }
}
