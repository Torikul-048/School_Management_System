<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of exams
     */
    public function index(Request $request)
    {
        $query = Exam::with('academicYear');

        // Filter by academic year
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $exams = $query->orderBy('start_date', 'desc')->paginate(15);
        $academicYears = AcademicYear::all();

        return view('exams.index', compact('exams', 'academicYears'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        $academicYears = AcademicYear::where('is_current', true)->get();
        return view('exams.create', compact('academicYears'));
    }

    /**
     * Store a newly created exam
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'type' => 'required|in:mid-term,final,unit-test,quiz,practical',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'publish_results' => 'boolean',
        ]);

        $validated['status'] = 'scheduled';
        $validated['publish_results'] = $request->has('publish_results');

        $exam = Exam::create($validated);

        return redirect()->route('exams.show', $exam)
            ->with('success', 'Exam created successfully! Now add exam schedules.');
    }

    /**
     * Display the specified exam
     */
    public function show(Exam $exam)
    {
        $exam->load(['academicYear', 'schedules.class', 'schedules.subject']);
        $classes = Classes::all();
        $subjects = Subject::all();

        return view('exams.show', compact('exam', 'classes', 'subjects'));
    }

    /**
     * Show the form for editing the exam
     */
    public function edit(Exam $exam)
    {
        $academicYears = AcademicYear::all();
        return view('exams.edit', compact('exam', 'academicYears'));
    }

    /**
     * Update the specified exam
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'type' => 'required|in:mid-term,final,unit-test,quiz,practical',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'publish_results' => 'boolean',
        ]);

        $validated['publish_results'] = $request->has('publish_results');

        $exam->update($validated);

        return redirect()->route('exams.show', $exam)
            ->with('success', 'Exam updated successfully!');
    }

    /**
     * Remove the specified exam
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('exams.index')
            ->with('success', 'Exam deleted successfully!');
    }

    /**
     * Add schedule to exam
     */
    public function addSchedule(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_date' => 'required|date|between:' . $exam->start_date . ',' . $exam->end_date,
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'full_marks' => 'required|integer|min:1',
            'pass_marks' => 'required|integer|min:1|lte:full_marks',
        ]);

        $validated['exam_id'] = $exam->id;

        ExamSchedule::create($validated);

        return redirect()->back()->with('success', 'Exam schedule added successfully!');
    }

    /**
     * Update exam schedule
     */
    public function updateSchedule(Request $request, ExamSchedule $schedule)
    {
        $validated = $request->validate([
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'full_marks' => 'required|integer|min:1',
            'pass_marks' => 'required|integer|min:1|lte:full_marks',
        ]);

        $schedule->update($validated);

        return redirect()->back()->with('success', 'Exam schedule updated successfully!');
    }

    /**
     * Delete exam schedule
     */
    public function deleteSchedule(ExamSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->back()->with('success', 'Exam schedule deleted successfully!');
    }

    /**
     * Publish exam results
     */
    public function publishResults(Exam $exam)
    {
        $exam->update(['publish_results' => true]);

        return redirect()->back()->with('success', 'Results published successfully!');
    }

    /**
     * Unpublish exam results
     */
    public function unpublishResults(Exam $exam)
    {
        $exam->update(['publish_results' => false]);

        return redirect()->back()->with('success', 'Results unpublished successfully!');
    }
}
