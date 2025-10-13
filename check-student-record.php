<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('email', 'student@school.com')->first();

if (!$user) {
    echo "❌ User not found with email: student@school.com\n";
    exit(1);
}

echo "✅ User found:\n";
echo "   ID: {$user->id}\n";
echo "   Name: {$user->name}\n";
echo "   Email: {$user->email}\n";
echo "   Roles: " . $user->getRoleNames()->implode(', ') . "\n\n";

$student = $user->student;

if (!$student) {
    echo "❌ NO STUDENT RECORD FOUND - This is the problem!\n";
    echo "\nSearching students table for user_id = {$user->id}...\n";
    $studentByUserId = App\Models\Student::where('user_id', $user->id)->first();
    if (!$studentByUserId) {
        echo "❌ No student record with user_id = {$user->id}\n";
    } else {
        echo "✅ Found student record with user_id = {$user->id}\n";
        echo "   Student ID: {$studentByUserId->id}\n";
    }
} else {
    echo "✅ Student record exists:\n";
    echo "   Student ID: {$student->id}\n";
    echo "   First Name: {$student->first_name}\n";
    echo "   Last Name: {$student->last_name}\n";
    echo "   Class ID: {$student->class_id}\n";
}
