<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Classes;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects
     */
    public function index(Request $request)
    {
        $query = Subject::with(['class', 'teacher']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('class_id', $request->class_id);
        }

        $subjects = $query->latest()->paginate(15);
        $classes = Classes::all();
        $teachers = Teacher::all();

        return view('academics.subjects.index', compact('subjects', 'classes', 'teachers'));
    }

    /**
     * Show the form for creating a new subject
     */
    public function create()
    {
        $classes = Classes::all();
        $teachers = Teacher::all();
        
        return view('academics.subjects.create', compact('classes', 'teachers'));
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'type' => 'required|in:core,elective,optional',
            'credits' => 'nullable|integer|min:1|max:10',
            'pass_marks' => 'nullable|integer|min:0',
            'full_marks' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Subject::create($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    /**
     * Display the specified subject
     */
    public function show(Subject $subject)
    {
        $subject->load(['class', 'teacher', 'subjectAssignments']);
        
        return view('academics.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified subject
     */
    public function edit(Subject $subject)
    {
        $classes = Classes::all();
        $teachers = Teacher::all();
        
        return view('academics.subjects.edit', compact('subject', 'classes', 'teachers'));
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'type' => 'required|in:core,elective,optional',
            'credits' => 'nullable|integer|min:1|max:10',
            'pass_marks' => 'nullable|integer|min:0',
            'full_marks' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified subject
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }

    /**
     * Assign subject to multiple classes
     */
    public function assignToClasses(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        // Create copies for each class
        foreach ($validated['class_ids'] as $classId) {
            if ($classId != $subject->class_id) {
                Subject::create([
                    'name' => $subject->name,
                    'code' => $subject->code . '-' . $classId,
                    'class_id' => $classId,
                    'teacher_id' => $subject->teacher_id,
                    'type' => $subject->type,
                    'credits' => $subject->credits,
                    'pass_marks' => $subject->pass_marks,
                    'full_marks' => $subject->full_marks,
                    'description' => $subject->description,
                ]);
            }
        }

        return redirect()->route('subjects.index')
            ->with('success', 'Subject assigned to selected classes!');
    }
}
