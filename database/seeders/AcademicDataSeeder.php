<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;
use App\Models\Section;
use App\Models\AcademicYear;

class AcademicDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Academic Years
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        
        $academicYear = AcademicYear::create([
            'name' => "$currentYear-$nextYear",
            'start_date' => "$currentYear-04-01",
            'end_date' => "$nextYear-03-31",
            'is_current' => true,
            'status' => 'active',
        ]);

        // Create Classes
        $classes = [
            ['name' => 'Class 1', 'capacity' => 40],
            ['name' => 'Class 2', 'capacity' => 40],
            ['name' => 'Class 3', 'capacity' => 40],
            ['name' => 'Class 4', 'capacity' => 40],
            ['name' => 'Class 5', 'capacity' => 40],
            ['name' => 'Class 6', 'capacity' => 45],
            ['name' => 'Class 7', 'capacity' => 45],
            ['name' => 'Class 8', 'capacity' => 45],
            ['name' => 'Class 9', 'capacity' => 50],
            ['name' => 'Class 10', 'capacity' => 50],
            ['name' => 'Class 11 (Science)', 'capacity' => 40],
            ['name' => 'Class 11 (Commerce)', 'capacity' => 40],
            ['name' => 'Class 12 (Science)', 'capacity' => 40],
            ['name' => 'Class 12 (Commerce)', 'capacity' => 40],
        ];

        foreach ($classes as $classData) {
            $class = Classes::create([
                'name' => $classData['name'],
                'academic_year_id' => $academicYear->id,
                'capacity' => $classData['capacity'],
                'status' => 'active',
            ]);

            // Create Sections for each class
            $sections = ['A', 'B', 'C'];
            foreach ($sections as $sectionName) {
                Section::create([
                    'class_id' => $class->id,
                    'name' => 'Section ' . $sectionName,
                    'capacity' => intval($classData['capacity'] / 3),
                    'status' => 'active',
                ]);
            }
        }

        $this->command->info('Academic data seeded successfully!');
    }
}
