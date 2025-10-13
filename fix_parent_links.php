<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Fixing Parent-Student Links...\n\n";

// Get Bob Parent
$bobParent = DB::table('users')->where('email', 'parent@school.com')->first();
echo "Bob Parent (ID: {$bobParent->id}, Name: {$bobParent->name})\n\n";

// Update students to link them properly to Bob Parent
$updated = DB::table('students')
    ->whereIn('id', [5, 6]) // Emma and Michael
    ->update(['parent_user_id' => $bobParent->id]);

echo "Updated {$updated} students to link to Bob Parent\n";

// Verify
$students = DB::table('students')
    ->where('parent_user_id', $bobParent->id)
    ->get();

echo "\nBob Parent's children:\n";
foreach ($students as $student) {
    $user = DB::table('users')->where('id', $student->user_id)->first();
    echo "  - {$user->name} (Student ID: {$student->id}, Roll: {$student->roll_number})\n";
}

echo "\n✅ Done!\n";
