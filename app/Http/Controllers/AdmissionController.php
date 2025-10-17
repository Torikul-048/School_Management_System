<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Models\Section;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdmissionController extends Controller
{
    /**
     * Show public admission form
     */
    public function create()
    {
        $classes = Classes::orderByRaw("CAST(numeric_name AS INTEGER)")->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $academicYears = AcademicYear::where('is_current', true)->orderBy('year', 'desc')->get();
        
        return view('admissions.create', compact('classes', 'sections', 'academicYears'));
    }

    /**
     * Store admission application
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
            'section_id' => 'nullable|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'guardian_name' => 'required|string|max:255',
            'guardian_relation' => 'required|string|max:50',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email',
            'guardian_occupation' => 'nullable|string|max:100',
            'guardian_address' => 'nullable|string',
            'previous_school' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'transfer_certificate' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        // Generate admission number
        $validated['admission_number'] = $this->generateAdmissionNumber();
        $validated['admission_date'] = now();
        $validated['status'] = 'pending'; // Pending until approved by admin

        // Handle file uploads
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        if ($request->hasFile('birth_certificate')) {
            $validated['birth_certificate'] = $request->file('birth_certificate')->store('students/documents', 'public');
        }

        if ($request->hasFile('transfer_certificate')) {
            $validated['transfer_certificate'] = $request->file('transfer_certificate')->store('students/documents', 'public');
        }

        $student = Student::create($validated);

        return redirect()->route('admissions.success')
            ->with([
                'success' => 'Admission application submitted successfully!',
                'admission_number' => $student->admission_number
            ]);
    }

    /**
     * Show admission success page
     */
    public function success()
    {
        return view('admissions.success');
    }

    /**
     * Show pending admissions (for admin)
     */
    public function pending()
    {
        $students = Student::where('status', 'pending')
            ->with(['class', 'section', 'academicYear'])
            ->latest()
            ->paginate(15);

        return view('admissions.pending', compact('students'));
    }

    /**
     * Approve admission
     */
    public function approve(Student $student)
    {
        // Update student status
        $student->update(['status' => 'active']);

        // Generate roll number
        $rollNumber = $this->generateRollNumber($student->class_id, $student->section_id);
        $student->update(['roll_number' => $rollNumber]);

        // Create user account would be done here if needed

        return redirect()->route('admissions.pending')
            ->with('success', 'Admission approved successfully!');
    }

    /**
     * Reject admission
     */
    public function reject(Student $student)
    {
        $student->update(['status' => 'rejected']);

        return redirect()->route('admissions.pending')
            ->with('success', 'Admission rejected!');
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
     * Generate roll number
     */
    private function generateRollNumber($classId, $sectionId)
    {
        $lastStudent = Student::where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->where('status', '!=', 'pending')
            ->orderBy('roll_number', 'desc')
            ->first();

        return $lastStudent ? $lastStudent->roll_number + 1 : 1;
    }
}
