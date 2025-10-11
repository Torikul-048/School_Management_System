<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarksController extends Controller
{
    protected function getTeacher()
    {
        return Teacher::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $teacher = $this->getTeacher();
        
        $subjectIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('subject_id')
            ->unique();
        
        $exams = Exam::with(['class', 'marks'])
            ->where('status', 'active')
            ->orderBy('start_date', 'desc')
            ->get();
        
        return view('teacher.marks.index', compact('teacher', 'exams'));
    }

    public function enter(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $assignments = SubjectAssignment::where('teacher_id', $teacher->id)
            ->with(['class', 'subject'])
            ->get();
        
        $exams = Exam::where('status', 'active')->get();
        
        $students = collect();
        $selectedExam = null;
        $selectedClass = null;
        $selectedSubject = null;
        
        if ($request->has(['exam_id', 'class_id', 'subject_id'])) {
            $selectedExam = Exam::find($request->exam_id);
            $selectedClass = Classes::find($request->class_id);
            $selectedSubject = Subject::find($request->subject_id);
            
            // Verify teacher has access
            $hasAccess = SubjectAssignment::where('teacher_id', $teacher->id)
                ->where('class_id', $selectedClass->id)
                ->where('subject_id', $selectedSubject->id)
                ->exists();
            
            if ($hasAccess) {
                $students = Student::where('class_id', $selectedClass->id)
                    ->where('status', 'active')
                    ->with('user')
                    ->get();
                
                // Get existing marks
                $existingMarks = Mark::where('exam_id', $selectedExam->id)
                    ->where('subject_id', $selectedSubject->id)
                    ->pluck('marks_obtained', 'student_id');
                
                $students->each(function ($student) use ($existingMarks) {
                    $student->marks = $existingMarks->get($student->id, '');
                });
            }
        }
        
        return view('teacher.marks.enter', compact(
            'teacher', 'assignments', 'exams', 'students',
            'selectedExam', 'selectedClass', 'selectedSubject'
        ));
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|array',
            'marks.*' => 'nullable|numeric|min:0'
        ]);
        
        // Verify teacher has access
        $hasAccess = SubjectAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->where('subject_id', $validated['subject_id'])
            ->exists();
        
        if (!$hasAccess) {
            return back()->with('error', 'You do not have permission to enter marks for this subject');
        }
        
        $exam = Exam::findOrFail($validated['exam_id']);
        
        DB::beginTransaction();
        try {
            foreach ($validated['marks'] as $studentId => $marks) {
                if ($marks !== null && $marks !== '') {
                    Mark::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'exam_id' => $validated['exam_id'],
                            'subject_id' => $validated['subject_id']
                        ],
                        [
                            'class_id' => $validated['class_id'],
                            'marks_obtained' => $marks,
                            'total_marks' => $exam->total_marks ?? 100,
                            'grade' => $this->calculateGrade($marks, $exam->total_marks ?? 100),
                            'remarks' => $this->getRemarks($marks, $exam->total_marks ?? 100)
                        ]
                    );
                }
            }
            
            DB::commit();
            return redirect()->route('teacher.marks.index')
                ->with('success', 'Marks entered successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to enter marks: ' . $e->getMessage());
        }
    }

    public function edit($examId, $classId)
    {
        $teacher = $this->getTeacher();
        
        $exam = Exam::findOrFail($examId);
        $class = Classes::findOrFail($classId);
        
        $subjects = SubjectAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->with('subject')
            ->get()
            ->pluck('subject');
        
        $marks = Mark::where('exam_id', $examId)
            ->where('class_id', $classId)
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->with(['student.user', 'subject'])
            ->get()
            ->groupBy('subject_id');
        
        return view('teacher.marks.edit', compact('teacher', 'exam', 'class', 'subjects', 'marks'));
    }

    public function update(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $validated = $request->validate([
            'marks' => 'required|array',
            'marks.*.id' => 'required|exists:marks,id',
            'marks.*.marks_obtained' => 'required|numeric|min:0'
        ]);
        
        DB::beginTransaction();
        try {
            foreach ($validated['marks'] as $markData) {
                $mark = Mark::findOrFail($markData['id']);
                
                // Verify teacher has access
                $hasAccess = SubjectAssignment::where('teacher_id', $teacher->id)
                    ->where('class_id', $mark->class_id)
                    ->where('subject_id', $mark->subject_id)
                    ->exists();
                
                if (!$hasAccess) {
                    continue;
                }
                
                $mark->update([
                    'marks_obtained' => $markData['marks_obtained'],
                    'grade' => $this->calculateGrade($markData['marks_obtained'], $mark->total_marks),
                    'remarks' => $this->getRemarks($markData['marks_obtained'], $mark->total_marks)
                ]);
            }
            
            DB::commit();
            return back()->with('success', 'Marks updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update marks: ' . $e->getMessage());
        }
    }

    private function calculateGrade($obtained, $total)
    {
        $percentage = ($obtained / $total) * 100;
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }

    private function getRemarks($obtained, $total)
    {
        $percentage = ($obtained / $total) * 100;
        
        if ($percentage >= 90) return 'Outstanding';
        if ($percentage >= 80) return 'Excellent';
        if ($percentage >= 70) return 'Very Good';
        if ($percentage >= 60) return 'Good';
        if ($percentage >= 50) return 'Satisfactory';
        if ($percentage >= 40) return 'Pass';
        return 'Fail';
    }
}
