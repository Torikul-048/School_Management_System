<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of classes
     */
    public function index(Request $request)
    {
        $query = Classes::with(['academicYear', 'teacher', 'sections', 'students']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by academic year
        if ($request->has('academic_year_id') && $request->academic_year_id != '') {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        // Sort by numeric name (1, 2, 3... 10) instead of creation date
        $classes = $query->orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->paginate(15);
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $teachers = Teacher::all();

        return view('academics.classes.index', compact('classes', 'academicYears', 'teachers'));
    }

    /**
     * Show the form for creating a new class
     */
    public function create()
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $teachers = Teacher::all();
        
        return view('academics.classes.create', compact('academicYears', 'teachers'));
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'nullable|integer|min:1',
            'room_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $class = Classes::create($validated);

        // Create default sections if requested
        if ($request->has('create_sections')) {
            $sectionNames = ['A', 'B', 'C'];
            foreach ($sectionNames as $index => $name) {
                Section::create([
                    'class_id' => $class->id,
                    'name' => $name,
                    'capacity' => $request->capacity ?? 40,
                    'room_number' => ($request->room_number ?? '101') . $index,
                ]);
            }
        }

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully!');
    }

    /**
     * Display the specified class
     */
    public function show(Classes $class)
    {
        $class->load(['academicYear', 'teacher', 'sections.students', 'students']);
        $subjects = Subject::where('class_id', $class->id)->get();
        
        return view('academics.classes.show', compact('class', 'subjects'));
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit(Classes $class)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $teachers = Teacher::all();
        
        return view('academics.classes.edit', compact('class', 'academicYears', 'teachers'));
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'nullable|integer|min:1',
            'room_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $class->update($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully!');
    }

    /**
     * Remove the specified class
     */
    public function destroy(Classes $class)
    {
        // Check if class has students
        if ($class->students()->count() > 0) {
            return redirect()->route('classes.index')
                ->with('error', 'Cannot delete class with enrolled students!');
        }

        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully!');
    }

    /**
     * Manage sections for a class
     */
    public function manageSections(Classes $class)
    {
        $sections = $class->sections()->with('students')->get();
        $teachers = Teacher::all();
        
        return view('academics.classes.sections', compact('class', 'sections', 'teachers'));
    }

    /**
     * Store a new section for a class
     */
    public function storeSection(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room_number' => 'nullable|string|max:50',
        ]);

        $validated['class_id'] = $class->id;
        Section::create($validated);

        return redirect()->route('classes.manage-sections', $class)
            ->with('success', 'Section created successfully!');
    }

    /**
     * Update a section
     */
    public function updateSection(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room_number' => 'nullable|string|max:50',
        ]);

        $section->update($validated);

        return redirect()->back()
            ->with('success', 'Section updated successfully!');
    }

    /**
     * Delete a section
     */
    public function destroySection(Section $section)
    {
        if ($section->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete section with students!');
        }

        $section->delete();

        return redirect()->back()
            ->with('success', 'Section deleted successfully!');
    }

    /**
     * Assign subjects to class
     */
    public function assignSubjects(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        // Update subjects to belong to this class
        Subject::whereIn('id', $validated['subject_ids'])
            ->update(['class_id' => $class->id]);

        return redirect()->route('classes.show', $class)
            ->with('success', 'Subjects assigned successfully!');
    }
}
