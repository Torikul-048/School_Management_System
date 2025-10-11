<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\SubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClassMaterialController extends Controller
{
    protected function getTeacher()
    {
        return Teacher::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $teacher = $this->getTeacher();
        
        // For now, we'll store materials as files in storage
        // You may want to create a ClassMaterial model later
        $materials = []; // Placeholder
        
        return view('teacher.materials.index', compact('teacher', 'materials'));
    }

    public function create()
    {
        $teacher = $this->getTeacher();
        
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
        
        $classes = Classes::whereIn('id', $classIds)->with('subjects')->get();
        
        return view('teacher.materials.create', compact('teacher', 'classes'));
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'file' => 'required|file|max:10240', // 10MB max
            'type' => 'required|in:syllabus,notes,assignment,other'
        ]);
        
        // Verify teacher has access
        $hasAccess = SubjectAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $validated['class_id'])
            ->exists();
        
        if (!$hasAccess) {
            return back()->with('error', 'You do not have permission to upload materials for this class');
        }
        
        // Store file
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('materials', 'public');
            
            // Here you would save to database if you have a ClassMaterial model
            // For now, just redirect with success
        }
        
        return redirect()->route('teacher.materials.index')
            ->with('success', 'Material uploaded successfully');
    }

    public function show($id)
    {
        // Show material details
        return view('teacher.materials.show');
    }

    public function destroy($id)
    {
        // Delete material
        return redirect()->route('teacher.materials.index')
            ->with('success', 'Material deleted successfully');
    }
}
