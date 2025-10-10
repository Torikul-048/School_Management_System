#!/usr/bin/env php
<?php

/*
|--------------------------------------------------------------------------
| Password Reset Script
|--------------------------------------------------------------------------
| This script resets all user passwords to the default password.
| Run: php reset-passwords.php
|--------------------------------------------------------------------------
*/

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

$defaultPassword = 'password123';

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║        School Management System - Password Reset Tool          ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

try {
    // Check database connection
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n\n";
    
    // Get all users
    $users = User::with('roles')->get();
    $count = $users->count();
    
    if ($count === 0) {
        echo "⚠️  No users found in database!\n";
        echo "💡 You need to run database seeders first:\n";
        echo "   php artisan db:seed\n\n";
        exit(1);
    }
    
    echo "📊 Found {$count} users in database\n\n";
    
    // Display current users
    echo "📋 Current Users:\n";
    echo str_repeat('─', 80) . "\n";
    
    $tableData = [];
    foreach ($users as $user) {
        $roles = $user->roles->pluck('name')->join(', ') ?: 'No Role';
        echo sprintf("| %-35s | %-25s | %-10s |\n", $user->email, $roles, $user->status ?? 'N/A');
    }
    echo str_repeat('─', 80) . "\n\n";
    
    // Confirm reset
    echo "⚠️  WARNING: This will reset ALL user passwords to: {$defaultPassword}\n";
    echo "Do you want to continue? (yes/no): ";
    
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    $confirm = trim(strtolower($line));
    fclose($handle);
    
    if ($confirm !== 'yes' && $confirm !== 'y') {
        echo "\n❌ Password reset cancelled.\n\n";
        exit(0);
    }
    
    echo "\n🔄 Resetting passwords...\n";
    
    // Reset all passwords
    $hashedPassword = Hash::make($defaultPassword);
    $updatedCount = DB::table('users')->update([
        'password' => $hashedPassword,
        'updated_at' => now(),
    ]);
    
    echo "\n✅ Successfully reset passwords for {$updatedCount} users!\n\n";
    
    // Display updated credentials
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║                    NEW LOGIN CREDENTIALS                       ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n\n";
    
    foreach ($users as $user) {
        $roles = $user->roles->pluck('name')->join(', ') ?: 'No Role';
        echo "📧 Email:    {$user->email}\n";
        echo "🔑 Password: {$defaultPassword}\n";
        echo "👤 Role:     {$roles}\n";
        echo "📛 Name:     {$user->name}\n";
        echo str_repeat('─', 80) . "\n";
    }
    
    echo "\n⚠️  IMPORTANT SECURITY REMINDERS:\n";
    echo "   1. Users should change their passwords immediately after first login\n";
    echo "   2. Store these credentials securely\n";
    echo "   3. Delete or secure this script file\n";
    echo "   4. Check USER_CREDENTIALS.md for more information\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "💡 Troubleshooting:\n";
    echo "   1. Make sure XAMPP MySQL is running\n";
    echo "   2. Check .env database credentials\n";
    echo "   3. Run: php artisan migrate\n";
    echo "   4. Run: php artisan db:seed\n\n";
    exit(1);
}

echo "✨ Password reset complete!\n\n";
exit(0);
