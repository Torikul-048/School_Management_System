<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\StudentScholarship;
use App\Models\Student;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::withCount('studentScholarships')->latest()->paginate(15);
        return view('finance.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        return view('finance.scholarships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:merit,need-based,sports,special,other',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_students' => 'nullable|integer|min:1',
            'min_percentage' => 'nullable|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'applicable_fee_types' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        Scholarship::create($validated);

        return redirect()->route('scholarships.index')->with('success', 'Scholarship created successfully.');
    }

    public function show(Scholarship $scholarship)
    {
        $scholarship->load(['studentScholarships.student']);
        return view('finance.scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarship $scholarship)
    {
        return view('finance.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:merit,need-based,sports,special,other',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_students' => 'nullable|integer|min:1',
            'min_percentage' => 'nullable|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'applicable_fee_types' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        $scholarship->update($validated);

        return redirect()->route('scholarships.index')->with('success', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();
        return redirect()->route('scholarships.index')->with('success', 'Scholarship deleted successfully.');
    }

    public function assignStudent()
    {
        $scholarships = Scholarship::active()->get();
        $students = Student::with('class')->active()->get();
        return view('finance.scholarships.assign', compact('scholarships', 'students'));
    }

    public function storeAssignment(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'scholarship_id' => 'required|exists:scholarships,id',
            'granted_date' => 'required|date',
            'valid_until' => 'nullable|date|after:granted_date',
            'remarks' => 'nullable|string',
        ]);

        $validated['status'] = 'active';

        StudentScholarship::create($validated);

        return redirect()->route('scholarships.index')->with('success', 'Scholarship assigned successfully.');
    }

    public function revokeAssignment($id)
    {
        $studentScholarship = StudentScholarship::findOrFail($id);
        $studentScholarship->update(['status' => 'revoked']);

        return redirect()->back()->with('success', 'Scholarship revoked successfully.');
    }
}
