<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// First, check if student@school.com already exists
$existingUser = User::where('email', 'student@school.com')->first();

if ($existingUser) {
    // Just update the password
    $existingUser->password = Hash::make('password123');
    $existingUser->save();
    echo "✅ Student credentials updated successfully!\n";
    echo "Email: student@school.com\n";
    echo "Password: password123\n";
    echo "(User already existed, password updated)\n";
} else {
    // Find student1@demo.com and update it
    $user = User::where('email', 'student1@demo.com')->first();
    
    if ($user) {
        $user->email = 'student@school.com';
        $user->password = Hash::make('password123');
        $user->save();
        echo "✅ Student credentials updated successfully!\n";
        echo "Email: student@school.com (changed from student1@demo.com)\n";
        echo "Password: password123\n";
    } else {
        echo "❌ Neither user found. Creating new user...\n";
        $newUser = User::create([
            'name' => 'Student',
            'email' => 'student@school.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $newUser->assignRole('Student');
        echo "✅ New student user created!\n";
        echo "Email: student@school.com\n";
        echo "Password: password123\n";
    }
}

