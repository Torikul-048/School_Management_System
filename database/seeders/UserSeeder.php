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

        // Librarian
        $librarian = User::create([
            'name' => 'Library Manager',
            'email' => 'librarian@school.com',
            'password' => $defaultPassword,
            'phone' => '1234567891',
            'status' => 'active',
        ]);
        $librarian->assignRole('Librarian');

        $this->command->info('✅ Created Librarian: librarian@school.com');

        // Accountant
        $accountant = User::create([
            'name' => 'School Accountant',
            'email' => 'accountant@school.com',
            'password' => $defaultPassword,
            'phone' => '1234567895',
            'status' => 'active',
        ]);
        $accountant->assignRole('Accountant');

        $this->command->info('✅ Created Accountant: accountant@school.com');

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
