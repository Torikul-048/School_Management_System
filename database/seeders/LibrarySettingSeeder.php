<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LibrarySetting;

class LibrarySettingSeeder extends Seeder
{
    public function run(): void
    {
        LibrarySetting::create([
            'max_books_per_student' => 3,
            'max_books_per_teacher' => 5,
            'student_issue_days' => 14,
            'teacher_issue_days' => 30,
            'fine_per_day' => 5.00,
            'max_renewal_times' => 2,
        ]);
    }
}
