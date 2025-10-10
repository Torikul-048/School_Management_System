<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers
     */
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'subjects', 'classes']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by qualification
        if ($request->filled('qualification')) {
            $query->where('qualification', $request->qualification);
        }

        $teachers = $query->latest()->paginate(15);

        $stats = [
            'total' => Teacher::count(),
            'active' => Teacher::where('status', 'active')->count(),
            'on_leave' => Teacher::where('status', 'on_leave')->count(),
            'inactive' => Teacher::where('status', 'inactive')->count(),
        ];

        return view('teachers.index', compact('teachers', 'stats'));
    }

    /**
     * Show the form for creating a new teacher
     */
    public function create()
    {
        $subjects = Subject::all();
        $classes = Classes::with('sections')->get();
        
        return view('teachers.create', compact('subjects', 'classes'));
    }

    /**
     * Store a newly created teacher
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:teachers,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'qualification' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'joining_date' => 'required|date',
            'department' => 'nullable|string|max:100',
            'designation' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc_code' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'certificates' => 'nullable|mimes:pdf,doc,docx|max:10240',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive,on_leave',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'status' => $validated['status'],
            ]);

            // Assign Teacher role
            $user->assignRole('Teacher');

            // Handle file uploads
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('teachers/photos', 'public');
            }

            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('teachers/resumes', 'public');
            }

            $certificatesPath = null;
            if ($request->hasFile('certificates')) {
                $certificatesPath = $request->file('certificates')->store('teachers/certificates', 'public');
            }

            // Create teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $validated['employee_id'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'blood_group' => $validated['blood_group'] ?? null,
                'nationality' => $validated['nationality'] ?? null,
                'religion' => $validated['religion'] ?? null,
                'address' => $validated['address'],
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zip_code' => $validated['zip_code'] ?? null,
                'country' => $validated['country'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'qualification' => $validated['qualification'],
                'specialization' => $validated['specialization'] ?? null,
                'experience_years' => $validated['experience_years'] ?? 0,
                'joining_date' => $validated['joining_date'],
                'department' => $validated['department'] ?? null,
                'designation' => $validated['designation'],
                'salary' => $validated['salary'],
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_number' => $validated['bank_account_number'] ?? null,
                'bank_ifsc_code' => $validated['bank_ifsc_code'] ?? null,
                'photo' => $photoPath,
                'resume' => $resumePath,
                'certificates' => $certificatesPath,
                'status' => $validated['status'],
            ]);

            // Assign subjects
            if ($request->filled('subjects')) {
                $teacher->subjects()->attach($request->subjects);
            }

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded files if transaction fails
            if ($photoPath) Storage::disk('public')->delete($photoPath);
            if ($resumePath) Storage::disk('public')->delete($resumePath);
            if ($certificatesPath) Storage::disk('public')->delete($certificatesPath);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create teacher: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified teacher
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects', 'classes', 'subjectAssignments.class', 'subjectAssignments.section', 'subjectAssignments.subject']);

        // Get attendance statistics
        $currentMonth = now()->format('Y-m');
        $attendances = Attendance::where('attendable_type', Teacher::class)
            ->where('attendable_id', $teacher->id)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->get();

        $attendanceStats = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'on_leave' => $attendances->where('status', 'on-leave')->count(),
        ];

        // Get workload (assigned subjects and classes)
        $workload = $teacher->subjectAssignments()
            ->with(['class', 'section', 'subject'])
            ->get()
            ->groupBy('subject_id');

        return view('teachers.show', compact('teacher', 'attendanceStats', 'workload'));
    }

    /**
     * Show the form for editing the specified teacher
     */
    public function edit(Teacher $teacher)
    {
        $subjects = Subject::all();
        $classes = Classes::with('sections')->get();
        $assignedSubjects = $teacher->subjects->pluck('id')->toArray();

        return view('teachers.edit', compact('teacher', 'subjects', 'classes', 'assignedSubjects'));
    }

    /**
     * Update the specified teacher
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:teachers,employee_id,' . $teacher->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'nationality' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'qualification' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'joining_date' => 'required|date',
            'department' => 'nullable|string|max:100',
            'designation' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc_code' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'certificates' => 'nullable|mimes:pdf,doc,docx|max:10240',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive,on_leave',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        DB::beginTransaction();
        try {
            // Update user account
            $user = $teacher->user;
            $user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'status' => $validated['status'],
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }

            // Handle file uploads
            if ($request->hasFile('photo')) {
                if ($teacher->photo) {
                    Storage::disk('public')->delete($teacher->photo);
                }
                $validated['photo'] = $request->file('photo')->store('teachers/photos', 'public');
            }

            if ($request->hasFile('resume')) {
                if ($teacher->resume) {
                    Storage::disk('public')->delete($teacher->resume);
                }
                $validated['resume'] = $request->file('resume')->store('teachers/resumes', 'public');
            }

            if ($request->hasFile('certificates')) {
                if ($teacher->certificates) {
                    Storage::disk('public')->delete($teacher->certificates);
                }
                $validated['certificates'] = $request->file('certificates')->store('teachers/certificates', 'public');
            }

            // Update teacher record
            $teacher->update($validated);

            // Sync subjects
            if ($request->has('subjects')) {
                $teacher->subjects()->sync($request->subjects);
            }

            DB::commit();

            return redirect()->route('teachers.show', $teacher)
                ->with('success', 'Teacher updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update teacher: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified teacher
     */
    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();
        try {
            // Delete files
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            if ($teacher->resume) {
                Storage::disk('public')->delete($teacher->resume);
            }
            if ($teacher->certificates) {
                Storage::disk('public')->delete($teacher->certificates);
            }

            // Delete user account
            $teacher->user->delete();

            // Delete teacher
            $teacher->delete();

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    /**
     * Display teacher attendance
     */
    public function attendance(Teacher $teacher, Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $startDate = \Carbon\Carbon::parse($month)->startOfMonth();
        $endDate = \Carbon\Carbon::parse($month)->endOfMonth();

        $attendances = Attendance::where('attendable_type', Teacher::class)
            ->where('attendable_id', $teacher->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $stats = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'on_leave' => $attendances->where('status', 'on-leave')->count(),
        ];

        return view('teachers.attendance', compact('teacher', 'attendances', 'stats', 'month'));
    }

    /**
     * Display teacher workload
     */
    public function workload(Teacher $teacher)
    {
        $assignments = $teacher->subjectAssignments()
            ->with(['class', 'section', 'subject'])
            ->get();

        $workloadStats = [
            'total_classes' => $assignments->unique('class_id')->count(),
            'total_sections' => $assignments->unique('section_id')->count(),
            'total_subjects' => $assignments->unique('subject_id')->count(),
            'total_periods' => $assignments->sum('periods_per_week'),
        ];

        return view('teachers.workload', compact('teacher', 'assignments', 'workloadStats'));
    }

    /**
     * Display teacher performance evaluation
     */
    public function performance(Teacher $teacher)
    {
        // This can be expanded with actual evaluation data
        $evaluations = []; // Placeholder for performance evaluations
        
        return view('teachers.performance', compact('teacher', 'evaluations'));
    }

    /**
     * Download teacher ID card
     */
    public function idCard(Teacher $teacher)
    {
        return view('teachers.id-card', compact('teacher'));
    }
}
