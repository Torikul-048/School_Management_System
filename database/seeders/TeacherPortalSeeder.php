<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TeacherPortalSeeder extends Seeder
{
    /**
     * Seed the application's database for teacher portal.
     */
    public function run(): void
    {
        $this->command->info('=== Setting up Teacher Portal ===');
        $this->command->newLine();
        
        // 1. Create teacher records for users with Teacher role
        $this->command->info('Step 1: Creating teacher records...');
        $this->call(CreateTeacherRecordsSeeder::class);
        $this->command->newLine();
        
        // 2. Assign subjects to teachers
        $this->command->info('Step 2: Assigning subjects to teachers...');
        $this->call(SubjectTeacherSeeder::class);
        $this->command->newLine();
        
        // 3. Create class schedules
        $this->command->info('Step 3: Creating class schedules...');
        $this->call(ScheduleSeeder::class);
        $this->command->newLine();
        
        $this->command->info('=== Teacher Portal Setup Complete! ===');
        $this->command->newLine();
        $this->command->info('You can now login with:');
        $this->command->info('Email: teacher@demo.com or teacher@school.com');
        $this->command->info('Password: password');
    }
}
