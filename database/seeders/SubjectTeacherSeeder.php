<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classes;

class SubjectTeacherSeeder extends Seeder
{
    public function run()
    {
        // Get all teachers
        $teachers = Teacher::all();
        
        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Please run CreateTeacherRecordsSeeder first.');
            return;
        }
        
        // Get all subjects
        $subjects = Subject::all();
        
        if ($subjects->isEmpty()) {
            $this->command->warn('No subjects found. Creating sample subjects...');
            $subjects = collect([
                Subject::create(['name' => 'Mathematics', 'code' => 'MATH101', 'description' => 'Mathematics']),
                Subject::create(['name' => 'English', 'code' => 'ENG101', 'description' => 'English Language']),
                Subject::create(['name' => 'Science', 'code' => 'SCI101', 'description' => 'General Science']),
                Subject::create(['name' => 'Social Studies', 'code' => 'SS101', 'description' => 'Social Studies']),
                Subject::create(['name' => 'Computer Science', 'code' => 'CS101', 'description' => 'Computer Science']),
            ]);
        }
        
        // Get all classes
        $classes = Classes::all();
        
        if ($classes->isEmpty()) {
            $this->command->warn('No classes found. Creating sample class...');
            $classes = collect([
                Classes::create(['name' => 'Class 10', 'capacity' => 40]),
            ]);
        }
        
        $assignmentCount = 0;
        
        // Assign subjects to teachers
        foreach ($teachers as $teacher) {
            // Assign 2-3 random subjects to each teacher
            $subjectsToAssign = $subjects->random(min(3, $subjects->count()));
            
            foreach ($subjectsToAssign as $subject) {
                // Assign to 1-2 random classes
                $classesToAssign = $classes->random(min(2, $classes->count()));
                
                foreach ($classesToAssign as $class) {
                    // Check if assignment already exists
                    $exists = DB::table('subject_teacher')
                        ->where('subject_id', $subject->id)
                        ->where('teacher_id', $teacher->id)
                        ->where('class_id', $class->id)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('subject_teacher')->insert([
                            'subject_id' => $subject->id,
                            'teacher_id' => $teacher->id,
                            'class_id' => $class->id,
                            'academic_year_id' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        
                        $assignmentCount++;
                        $this->command->info("✓ Assigned {$subject->name} to {$teacher->first_name} {$teacher->last_name} for {$class->name}");
                    }
                }
            }
        }
        
        $this->command->info("Created {$assignmentCount} subject-teacher assignments!");
    }
}
