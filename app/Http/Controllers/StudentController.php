<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $query = Student::with(['class', 'section', 'academicYear', 'user']);

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Class filter
        if ($request->filled('class_id')) {
            $query->byClass($request->class_id);
        }

        // Section filter - now filters by section name instead of ID
        if ($request->filled('section_name')) {
            $query->whereHas('section', function($q) use ($request) {
                $q->where('name', $request->section_name);
            });
        }

        // Academic year filter
        if ($request->filled('academic_year_id')) {
            $query->byAcademicYear($request->academic_year_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->paginate(15);
        
        // Get filter options - with proper ordering
        $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->get();
        
        // Get unique section names only (A, B, C, etc.) - remove duplicates from different classes
        $sections = Section::select('name')
            ->distinct()
            ->orderBy('name')
            ->get();
        
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('students.index', compact('students', 'classes', 'sections', 'academicYears'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $academicYears = AcademicYear::where('is_current', true)->orderBy('year', 'desc')->get();
        
        return view('students.create', compact('classes', 'sections', 'academicYears'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:100',
            'caste' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:50',
            'mother_tongue' => 'nullable|string|max:100',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'guardian_name' => 'required|string|max:255',
            'guardian_relation' => 'required|string|max:50',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email',
            'guardian_occupation' => 'nullable|string|max:100',
            'guardian_address' => 'nullable|string',
            'previous_school' => 'nullable|string|max:255',
            'admission_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'transfer_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        // Generate admission number
        $validated['admission_number'] = $this->generateAdmissionNumber();
        
        // Generate roll number
        $validated['roll_number'] = $this->generateRollNumber($validated['class_id'], $validated['section_id']);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        // Handle birth certificate upload
        if ($request->hasFile('birth_certificate')) {
            $validated['birth_certificate'] = $request->file('birth_certificate')->store('students/documents', 'public');
        }

        // Handle transfer certificate upload
        if ($request->hasFile('transfer_certificate')) {
            $validated['transfer_certificate'] = $request->file('transfer_certificate')->store('students/documents', 'public');
        }

        // Set default status
        $validated['status'] = 'active';

        // Create user account for student
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'), // Default password
        ]);

        // Assign student role
        $user->assignRole('Student');

        // Create student record
        $validated['user_id'] = $user->id;
        $student = Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student admitted successfully! Admission Number: ' . $student->admission_number);
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load(['class', 'section', 'academicYear', 'user', 'grades.subject', 'grades.exam', 'feeInvoices', 'attendances']);
        
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        
        return view('students.edit', compact('student', 'classes', 'sections', 'academicYears'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'religion' => 'nullable|string|max:100',
            'caste' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:50',
            'mother_tongue' => 'nullable|string|max:100',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'guardian_name' => 'required|string|max:255',
            'guardian_relation' => 'required|string|max:50',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email',
            'guardian_occupation' => 'nullable|string|max:100',
            'guardian_address' => 'nullable|string',
            'previous_school' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,graduated,transferred',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'transfer_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        // Handle birth certificate upload
        if ($request->hasFile('birth_certificate')) {
            if ($student->birth_certificate) {
                Storage::disk('public')->delete($student->birth_certificate);
            }
            $validated['birth_certificate'] = $request->file('birth_certificate')->store('students/documents', 'public');
        }

        // Handle transfer certificate upload
        if ($request->hasFile('transfer_certificate')) {
            if ($student->transfer_certificate) {
                Storage::disk('public')->delete($student->transfer_certificate);
            }
            $validated['transfer_certificate'] = $request->file('transfer_certificate')->store('students/documents', 'public');
        }

        // Update student record
        $student->update($validated);

        // Update user record
        $student->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student information updated successfully!');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        // Delete associated files
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }
        if ($student->birth_certificate) {
            Storage::disk('public')->delete($student->birth_certificate);
        }
        if ($student->transfer_certificate) {
            Storage::disk('public')->delete($student->transfer_certificate);
        }

        // Delete user account
        $student->user->delete();

        // Delete student record
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Promote student to next class
     */
    public function promote(Request $request, Student $student)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student promoted successfully!');
    }

    /**
     * Transfer student to another school
     */
    public function transfer(Student $student)
    {
        $student->update(['status' => 'transferred']);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student marked as transferred!');
    }

    /**
     * Generate ID card for student
     */
    public function idCard(Student $student)
    {
        $student->load(['class', 'section', 'academicYear']);
        
        return view('students.id-card', compact('student'));
    }

    /**
     * Generate unique admission number
     */
    private function generateAdmissionNumber()
    {
        $year = date('Y');
        $lastStudent = Student::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastStudent ? intval(substr($lastStudent->admission_number, -4)) + 1 : 1;
        
        return 'ADM' . $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate roll number for class and section
     */
    private function generateRollNumber($classId, $sectionId)
    {
        $lastStudent = Student::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->orderBy('roll_number', 'desc')
            ->first();

        return $lastStudent ? $lastStudent->roll_number + 1 : 1;
    }
}
