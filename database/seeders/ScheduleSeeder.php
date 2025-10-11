<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classes;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        // First, run migration if needed
        if (!DB::getSchemaBuilder()->hasTable('schedules')) {
            $this->command->info('Creating schedules table...');
            DB::statement('CREATE TABLE IF NOT EXISTS schedules (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                class_id INTEGER NOT NULL,
                subject_id INTEGER NOT NULL,
                teacher_id INTEGER NOT NULL,
                academic_year_id INTEGER NULL,
                day TEXT NOT NULL,
                start_time TEXT NOT NULL,
                end_time TEXT NOT NULL,
                room_number TEXT NULL,
                notes TEXT NULL,
                created_at TEXT,
                updated_at TEXT,
                FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
                FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
                FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
            )');
        }
        
        // Get all subject-teacher assignments
        $assignments = DB::table('subject_teacher')
            ->get();
        
        if ($assignments->isEmpty()) {
            $this->command->warn('No subject-teacher assignments found. Please run SubjectTeacherSeeder first.');
            return;
        }
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timeslots = [
            ['08:00:00', '09:00:00'],
            ['09:00:00', '10:00:00'],
            ['10:00:00', '11:00:00'],
            ['11:00:00', '12:00:00'],
            ['13:00:00', '14:00:00'],
            ['14:00:00', '15:00:00'],
            ['15:00:00', '16:00:00'],
        ];
        
        $scheduleCount = 0;
        
        // Create schedules for each assignment
        foreach ($assignments as $assignment) {
            // Assign 2-3 random days for each subject-teacher-class combination
            $assignedDays = array_rand(array_flip($days), rand(2, 3));
            if (!is_array($assignedDays)) {
                $assignedDays = [$assignedDays];
            }
            
            foreach ($assignedDays as $day) {
                // Pick a random timeslot
                $timeslot = $timeslots[array_rand($timeslots)];
                
                // Check if schedule already exists
                $exists = DB::table('schedules')
                    ->where('class_id', $assignment->class_id)
                    ->where('subject_id', $assignment->subject_id)
                    ->where('teacher_id', $assignment->teacher_id)
                    ->where('day', $day)
                    ->where('start_time', $timeslot[0])
                    ->exists();
                
                if (!$exists) {
                    DB::table('schedules')->insert([
                        'class_id' => $assignment->class_id,
                        'subject_id' => $assignment->subject_id,
                        'teacher_id' => $assignment->teacher_id,
                        'academic_year_id' => null,
                        'day' => $day,
                        'start_time' => $timeslot[0],
                        'end_time' => $timeslot[1],
                        'room_number' => 'Room ' . rand(101, 210),
                        'notes' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $scheduleCount++;
                    
                    $subject = Subject::find($assignment->subject_id);
                    $class = Classes::find($assignment->class_id);
                    $this->command->info("✓ Created schedule: {$subject->name} for {$class->name} on {$day} at {$timeslot[0]}");
                }
            }
        }
        
        $this->command->info("Created {$scheduleCount} schedule entries!");
    }
}
