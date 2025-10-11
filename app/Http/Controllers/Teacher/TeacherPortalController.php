<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\Timetable;
use App\Models\SubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherPortalController extends Controller
{
    protected function getTeacher()
    {
        return Teacher::where('user_id', Auth::id())->firstOrFail();
    }

    public function profile()
    {
        $teacher = $this->getTeacher();
        $teacher->load('user');
        
        return view('teacher.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = $this->getTeacher();
        
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update teacher details
        $teacher->update([
            'phone' => $validated['phone'] ?? $teacher->phone,
            'address' => $validated['address'] ?? $teacher->address,
            'emergency_contact' => $validated['emergency_contact'] ?? $teacher->emergency_contact,
        ]);

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $teacher->user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            
            $teacher->user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        return back()->with('success', 'Profile updated successfully');
    }

    public function timetable()
    {
        $teacher = $this->getTeacher();
        
        // Get schedules from the schedules table
        $schedules = \DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.id')
            ->join('subjects', 'schedules.subject_id', '=', 'subjects.id')
            ->where('schedules.teacher_id', $teacher->id)
            ->select(
                'schedules.*',
                'classes.name as class_name',
                'classes.id as class_id',
                'subjects.name as subject_name',
                'subjects.id as subject_id'
            )
            ->orderBy('schedules.start_time')
            ->get()
            ->groupBy('day');
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        return view('teacher.timetable', compact('teacher', 'schedules', 'days'));
    }

    public function myClasses()
    {
        $teacher = $this->getTeacher();
        
        $classes = SubjectAssignment::where('teacher_id', $teacher->id)
            ->with(['class', 'subject'])
            ->get()
            ->groupBy('class_id')
            ->map(function ($assignments) {
                return [
                    'class' => $assignments->first()->class,
                    'subjects' => $assignments->pluck('subject'),
                    'student_count' => Student::where('class_id', $assignments->first()->class_id)->count()
                ];
            });
        
        return view('teacher.my-classes', compact('teacher', 'classes'));
    }

    public function mySubjects()
    {
        $teacher = $this->getTeacher();
        
        $subjects = SubjectAssignment::where('teacher_id', $teacher->id)
            ->with(['subject', 'class'])
            ->get()
            ->groupBy('subject_id')
            ->map(function ($assignments) {
                return [
                    'subject' => $assignments->first()->subject,
                    'classes' => $assignments->pluck('class')
                ];
            });
        
        return view('teacher.my-subjects', compact('teacher', 'subjects'));
    }

    public function students()
    {
        $teacher = $this->getTeacher();
        
        $classIds = SubjectAssignment::where('teacher_id', $teacher->id)
            ->pluck('class_id')
            ->unique();
        
        $students = Student::whereIn('class_id', $classIds)
            ->with(['class', 'user'])
            ->paginate(20);
        
        return view('teacher.students.index', compact('teacher', 'students'));
    }

    public function studentDetails($id)
    {
        $teacher = $this->getTeacher();
        
        $student = Student::with(['class', 'user', 'marks', 'attendance'])
            ->findOrFail($id);
        
        // Verify teacher has access to this student
        $hasAccess = SubjectAssignment::where('teacher_id', $teacher->id)
            ->where('class_id', $student->class_id)
            ->exists();
        
        if (!$hasAccess) {
            abort(403, 'You do not have access to this student');
        }
        
        return view('teacher.students.show', compact('teacher', 'student'));
    }
}
