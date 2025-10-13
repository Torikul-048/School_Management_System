<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking Parent Portal Data...\n\n";

// Get parent user
$parent = DB::table('users')->where('email', 'parent@school.com')->first();
echo "Parent User: {$parent->name} (ID: {$parent->id})\n\n";

// Get students linked to this parent
$students = DB::table('students')
    ->where('parent_user_id', $parent->id)
    ->get();

echo "Children count: " . $students->count() . "\n\n";

if ($students->count() > 0) {
    echo "Children:\n";
    foreach ($students as $student) {
        $user = DB::table('users')->where('id', $student->user_id)->first();
        echo "  - Student ID: {$student->id}, Name: " . ($user->name ?? 'N/A') . ", Roll: {$student->roll_number}, Status: {$student->status}\n";
    }
} else {
    echo "No children found!\n";
}
