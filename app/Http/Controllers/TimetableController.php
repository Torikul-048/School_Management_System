<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimetableController extends Controller
{
    /**
     * Display timetable for a class/section
     */
    public function index(Request $request)
    {
        $classes = Classes::with('sections')->orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->get();
        
        // Only load sections if a class is selected, otherwise empty collection
        if ($request->filled('class_id')) {
            $sections = Section::where('class_id', $request->class_id)->orderBy('name')->get();
        } else {
            $sections = collect(); // Empty collection when no class selected
        }
        
        $academicYears = AcademicYear::where('is_current', true)->orderBy('year', 'desc')->get();
        
        $timetables = collect();
        $selectedClass = null;
        $selectedSection = null;

        if ($request->has('class_id') && $request->class_id != '') {
            $selectedClass = Classes::find($request->class_id);
            
            $query = Timetable::with(['class', 'section', 'subject', 'teacher'])
                ->where('class_id', $request->class_id);

            if ($request->has('section_id') && $request->section_id != '') {
                $query->where('section_id', $request->section_id);
                $selectedSection = Section::find($request->section_id);
            }

            if ($request->has('academic_year_id') && $request->academic_year_id != '') {
                $query->where('academic_year_id', $request->academic_year_id);
            }

            $timetables = $query->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day_of_week');
        }

        return view('academics.timetable.index', compact(
            'timetables', 
            'classes',
            'sections',
            'academicYears',
            'selectedClass',
            'selectedSection'
        ));
    }

    /**
     * Show the form for creating timetable
     */
    public function create(Request $request)
    {
        $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $academicYears = AcademicYear::where('is_current', true)->orderBy('year', 'desc')->get();

        $selectedClass = $request->has('class_id') ? Classes::find($request->class_id) : null;

        return view('academics.timetable.create', compact(
            'classes',
            'sections',
            'subjects',
            'teachers',
            'academicYears',
            'selectedClass'
        ));
    }

    /**
     * Store a new timetable entry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
        ]);

        // Check for conflicts
        $conflict = $this->checkConflict($validated);
        
        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->with('error', $conflict);
        }

        Timetable::create($validated);

        return redirect()->route('timetable.index', [
                'class_id' => $validated['class_id'],
                'section_id' => $validated['section_id']
            ])
            ->with('success', 'Timetable entry created successfully!');
    }

    /**
     * Show the form for editing timetable entry
     */
    public function edit(Timetable $timetable)
    {
        $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->get();
        $sections = Section::where('class_id', $timetable->class_id)->orderBy('name')->get();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('academics.timetable.edit', compact(
            'timetable',
            'classes',
            'sections',
            'subjects',
            'teachers',
            'academicYears'
        ));
    }

    /**
     * Update timetable entry
     */
    public function update(Request $request, Timetable $timetable)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
        ]);

        // Check for conflicts (excluding current entry)
        $conflict = $this->checkConflict($validated, $timetable->id);
        
        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->with('error', $conflict);
        }

        $timetable->update($validated);

        return redirect()->route('timetable.index', [
                'class_id' => $validated['class_id'],
                'section_id' => $validated['section_id']
            ])
            ->with('success', 'Timetable entry updated successfully!');
    }

    /**
     * Delete timetable entry
     */
    public function destroy(Timetable $timetable)
    {
        $classId = $timetable->class_id;
        $sectionId = $timetable->section_id;
        
        $timetable->delete();

        return redirect()->route('timetable.index', [
                'class_id' => $classId,
                'section_id' => $sectionId
            ])
            ->with('success', 'Timetable entry deleted successfully!');
    }

    /**
     * Show auto-generate form
     */
    public function showAutoGenerateForm()
    {
        $classes = Classes::with('sections')->get();
        $academicYears = AcademicYear::where('is_current', true)->get();
        
        return view('academics.timetable.auto-generate', compact('classes', 'academicYears'));
    }

    /**
     * Auto-generate timetable for a class/section
     */
    public function autoGenerate(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'periods_per_day' => 'required|integer|min:4|max:10',
            'period_duration' => 'required|integer|min:30|max:90',
            'start_time' => 'required|date_format:H:i',
            'break_after_period' => 'nullable|integer',
            'break_duration' => 'nullable|integer',
        ]);

        // Get subjects for this class
        $subjects = Subject::where('class_id', $validated['class_id'])->with('teacher')->get();

        if ($subjects->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No subjects assigned to this class!');
        }

        // Clear existing timetable for this class/section
        Timetable::where('class_id', $validated['class_id'])
            ->where('section_id', $validated['section_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->delete();

        $periodsPerDay = $validated['periods_per_day'];
        $periodDuration = $validated['period_duration'];
        $breakAfter = $validated['break_after_period'] ?? 0;
        $breakDuration = $validated['break_duration'] ?? 0;

        // Generate for Monday to Friday (1-5)
        for ($day = 1; $day <= 5; $day++) {
            $currentTime = Carbon::createFromFormat('H:i', $validated['start_time']);
            $subjectIndex = 0;

            for ($period = 1; $period <= $periodsPerDay; $period++) {
                $subject = $subjects[$subjectIndex % $subjects->count()];

                $startTime = $currentTime->format('H:i');
                $endTime = $currentTime->copy()->addMinutes($periodDuration)->format('H:i');

                Timetable::create([
                    'class_id' => $validated['class_id'],
                    'section_id' => $validated['section_id'],
                    'subject_id' => $subject->id,
                    'teacher_id' => $subject->teacher_id,
                    'academic_year_id' => $validated['academic_year_id'],
                    'day_of_week' => $day,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);

                $currentTime->addMinutes($periodDuration);

                // Add break time
                if ($breakAfter > 0 && $period == $breakAfter) {
                    $currentTime->addMinutes($breakDuration);
                }

                $subjectIndex++;
            }
        }

        return redirect()->route('timetable.index', [
                'class_id' => $validated['class_id'],
                'section_id' => $validated['section_id'],
                'academic_year_id' => $validated['academic_year_id']
            ])
            ->with('success', 'Timetable generated successfully!');
    }

    /**
     * View teacher's timetable
     */
    public function teacherTimetable(Teacher $teacher)
    {
        $timetables = Timetable::with(['class', 'section', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('academics.timetable.teacher', compact('teacher', 'timetables'));
    }

    /**
     * Check for timetable conflicts
     */
    private function checkConflict($data, $excludeId = null)
    {
        // Check teacher conflict
        $teacherConflict = Timetable::where('teacher_id', $data['teacher_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where(function($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function($q) use ($data) {
                        $q->where('start_time', '<=', $data['start_time'])
                          ->where('end_time', '>=', $data['end_time']);
                    });
            });

        if ($excludeId) {
            $teacherConflict->where('id', '!=', $excludeId);
        }

        if ($teacherConflict->exists()) {
            return 'Teacher has a conflicting schedule at this time!';
        }

        // Check class/section conflict
        $classConflict = Timetable::where('class_id', $data['class_id'])
            ->where('section_id', $data['section_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where(function($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function($q) use ($data) {
                        $q->where('start_time', '<=', $data['start_time'])
                          ->where('end_time', '>=', $data['end_time']);
                    });
            });

        if ($excludeId) {
            $classConflict->where('id', '!=', $excludeId);
        }

        if ($classConflict->exists()) {
            return 'Class/Section already has a subject scheduled at this time!';
        }

        return null;
    }
}
