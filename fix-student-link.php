<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Section;
use App\Models\AcademicYear;

$user = User::where('email', 'student@school.com')->first();

if (!$user) {
    echo "❌ User not found\n";
    exit(1);
}

echo "✅ User found: {$user->name} (ID: {$user->id})\n\n";

// Check if there's already a student with this email
$existingStudent = Student::where('email', $user->email)->first();

if ($existingStudent) {
    echo "✅ Found existing student record with same email\n";
    echo "   Student ID: {$existingStudent->id}\n";
    echo "   Name: {$existingStudent->first_name} {$existingStudent->last_name}\n";
    echo "   Current user_id: " . ($existingStudent->user_id ?? 'NULL') . "\n\n";
    
    if ($existingStudent->user_id != $user->id) {
        echo "📝 Updating student record to link with user...\n";
        $existingStudent->user_id = $user->id;
        $existingStudent->email = $user->email;
        $existingStudent->save();
        echo "✅ Student record updated successfully!\n";
    } else {
        echo "✅ Student already linked to this user\n";
    }
} else {
    echo "📝 No existing student found. Creating new student record...\n\n";
    
    // Get first available class and section
    $class = Classes::first();
    $section = Section::first();
    $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();
    
    if (!$class || !$section || !$academicYear) {
        echo "❌ Missing required data (class, section, or academic year)\n";
        exit(1);
    }
    
    // Generate unique admission number
    $lastStudent = Student::orderBy('id', 'desc')->first();
    $nextNumber = $lastStudent ? (intval(substr($lastStudent->admission_number, 3)) + 1) : 1;
    $admissionNumber = 'ADM' . date('Y') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    
    $student = Student::create([
        'user_id' => $user->id,
        'roll_number' => intval(str_pad($user->id, 4, '0', STR_PAD_LEFT)),
        'class_id' => $class->id,
        'section_id' => $section->id,
        'academic_year_id' => $academicYear->id,
        'admission_number' => $admissionNumber,
        'admission_date' => now(),
        'date_of_birth' => now()->subYears(16),
        'gender' => 'male',
        'blood_group' => 'A+',
        'religion' => 'Islam',
        'nationality' => 'Bangladeshi',
        'current_address' => 'Student Address',
        'permanent_address' => 'Student Address',
        'father_name' => 'Father Name',
        'father_phone' => '01700000001',
        'father_occupation' => 'Business',
        'mother_name' => 'Mother Name',
        'mother_phone' => '01700000002',
        'mother_occupation' => 'Housewife',
        'status' => 'active',
    ]);
    
    echo "✅ Student record created successfully!\n";
    echo "   Student ID: {$student->id}\n";
    echo "   Admission Number: {$student->admission_number}\n";
}

echo "\n🎉 Student portal is now ready!\n";
echo "   Login: {$user->email}\n";
echo "   Password: password123\n";
