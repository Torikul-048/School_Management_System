<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use Spatie\Permission\Models\Role;

class CreateTeacherRecordsSeeder extends Seeder
{
    public function run()
    {
        // Get all users with Teacher role
        $teacherRole = Role::where('name', 'Teacher')->first();
        
        if (!$teacherRole) {
            $this->command->error('Teacher role not found!');
            return;
        }
        
        $teacherUsers = User::role('Teacher')->get();
        
        $this->command->info("Found {$teacherUsers->count()} users with Teacher role");
        
        foreach ($teacherUsers as $user) {
            // Check if teacher record already exists
            $existingTeacher = Teacher::where('user_id', $user->id)->first();
            
            if ($existingTeacher) {
                $this->command->info("✓ Teacher record already exists for: {$user->name}");
                continue;
            }
            
            // Split user's name into first and last name
            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? 'Teacher';
            
            // Create teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'EMP' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $user->email,
                'phone' => $user->phone ?? '0123456789',
                'designation' => 'Teacher',
                'joining_date' => now()->subYears(rand(1, 5))->format('Y-m-d'),
                'qualification' => 'Bachelor of Education',
                'experience_years' => rand(1, 15),
                'specialization' => 'General Teaching',
                'department' => 'General',
                'date_of_birth' => now()->subYears(rand(25, 50))->format('Y-m-d'),
                'gender' => 'male',
                'blood_group' => 'O+',
                'nationality' => 'Bangladesh',
                'emergency_contact' => '0987654321',
                'emergency_contact_name' => 'Emergency Contact',
                'emergency_contact_phone' => '0987654321',
                'current_address' => $user->address ?? 'Default Address',
                'permanent_address' => $user->address ?? 'Default Address',
                'address' => $user->address ?? 'Default Address',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'zip_code' => '1000',
                'country' => 'Bangladesh',
                'salary' => rand(30000, 80000),
                'employment_type' => 'full-time',
                'status' => 'active',
            ]);
            
            $this->command->info("✓ Created teacher record for: {$user->name} (Employee ID: {$teacher->employee_id})");
        }
        
        $this->command->info('Teacher records creation completed!');
    }
}
