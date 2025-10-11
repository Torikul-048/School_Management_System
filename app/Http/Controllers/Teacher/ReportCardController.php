<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Classes;
use App\Models\SubjectAssignment;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCardController extends Controller
{
    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

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
        
        $classes = Classes::whereIn('id', $classIds)->get();
        $exams = Exam::where('status', 'completed')->orderBy('start_date', 'desc')->get();
        
        return view('teacher.report-cards.index', compact('teacher', 'classes', 'exams'));
    }

    public function generate(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
        
        $classes = Classes::whereIn('id', $classIds)->get();
        $exams = Exam::where('status', 'completed')->get();
        
        return view('teacher.report-cards.generate', compact('teacher', 'classes', 'exams'));
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'nullable|exists:students,id'
        ]);
        
        // Verify teacher has access to this class
        $hasAccess = SubjectAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->exists();
        
        if (!$hasAccess) {
            return back()->with('error', 'You do not have permission to generate report cards for this class');
        }
        
        $exam = Exam::findOrFail($validated['exam_id']);
        $class = Classes::findOrFail($validated['class_id']);
        
        // Get students
        $students = isset($validated['student_id']) 
            ? Student::where('id', $validated['student_id'])->get()
            : Student::where('class_id', $class->id)->where('status', 'active')->get();
        
        $reportCards = [];
        
        foreach ($students as $student) {
            $marks = Mark::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->with('subject')
                ->get();
            
            if ($marks->count() > 0) {
                $totalMarks = $marks->sum('marks_obtained');
                $maxMarks = $marks->sum('total_marks');
                $percentage = round(($totalMarks / $maxMarks) * 100, 2);
                
                $reportCards[] = [
                    'student' => $student,
                    'marks' => $marks,
                    'total' => $totalMarks,
                    'max' => $maxMarks,
                    'percentage' => $percentage,
                    'grade' => $this->calculateOverallGrade($percentage)
                ];
            }
        }
        
        return view('teacher.report-cards.preview', compact('teacher', 'exam', 'class', 'reportCards'));
    }

    private function calculateOverallGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }
}
