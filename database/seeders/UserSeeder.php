<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@school.com',
            'password' => $defaultPassword,
            'phone' => '1234567890',
            'status' => 'active',
        ]);
        $superAdmin->assignRole('Super Admin');

        $this->command->info('✅ Created Super Admin: superadmin@school.com');

        // Admin
        $admin = User::create([
            'name' => 'School Administrator',
            'email' => 'admin@school.com',
            'password' => $defaultPassword,
            'phone' => '1234567891',
            'status' => 'active',
        ]);
        $admin->assignRole('Admin');

        $this->command->info('✅ Created Admin: admin@school.com');

        // Teacher
        $teacher = User::create([
            'name' => 'John Teacher',
            'email' => 'teacher@school.com',
            'password' => $defaultPassword,
            'phone' => '1234567892',
            'status' => 'active',
        ]);
        $teacher->assignRole('Teacher');

        $this->command->info('✅ Created Teacher: teacher@school.com');

        // Student
        $student = User::create([
            'name' => 'Jane Student',
            'email' => 'student@school.com',
            'password' => $defaultPassword,
            'phone' => '1234567893',
            'status' => 'active',
        ]);
        $student->assignRole('Student');

        $this->command->info('✅ Created Student: student@school.com');

        // Parent
        $parent = User::create([
            'name' => 'Bob Parent',
            'email' => 'parent@school.com',
            'password' => $defaultPassword,
            'phone' => '1234567894',
            'status' => 'active',
        ]);
        $parent->assignRole('Parent');

        $this->command->info('✅ Created Parent: parent@school.com');

        $this->command->newLine();
        $this->command->info('🔑 Default password for all accounts: password123');
        $this->command->warn('⚠️  Change these passwords in production!');
    }
}
