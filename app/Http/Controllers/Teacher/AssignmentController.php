<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\SubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    protected function getTeacher()
    {
        return Teacher::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $teacher = $this->getTeacher();
        $assignments = []; // Placeholder - create Assignment model if needed
        
        return view('teacher.assignments.index', compact('teacher', 'assignments'));
    }

    public function create()
    {
        $teacher = $this->getTeacher();
        
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
        
        $classes = Classes::whereIn('id', $classIds)->with('subjects')->get();
        
        return view('teacher.assignments.create', compact('teacher', 'classes'));
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'due_date' => 'required|date|after:today',
            'total_marks' => 'required|numeric|min:0',
            'file' => 'nullable|file|max:10240'
        ]);
        
        // Verify access and create assignment
        // Implementation depends on if you have Assignment model
        
        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment created successfully');
    }

    public function show($id)
    {
        $teacher = $this->getTeacher();
        return view('teacher.assignments.show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = $this->getTeacher();
        return view('teacher.assignments.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment updated successfully');
    }

    public function destroy($id)
    {
        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment deleted successfully');
    }

    public function submissions($id)
    {
        $teacher = $this->getTeacher();
        $submissions = []; // Placeholder
        
        return view('teacher.assignments.submissions', compact('teacher', 'submissions'));
    }
}
