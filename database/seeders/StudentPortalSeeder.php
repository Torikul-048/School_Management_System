<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\Mark;
use App\Models\FeePayment;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\LeaveRequest;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\FeeCategory;
use App\Models\FeeInvoice;
use App\Models\BookCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentPortalSeeder extends Seeder
{
    public function run(): void
    {
        // Get current academic year
        $academicYear = AcademicYear::where('is_current', true)->first();
        if (!$academicYear) {
            $academicYear = AcademicYear::first();
        }

        // Create or get a class
        $class = Classes::firstOrCreate(
            ['name' => 'Class 10'],
            [
                'code' => 'C10',
                'capacity' => 50,
                'class_teacher_id' => null,
            ]
        );

        // Create or get Section A for Class 10
        $section = Section::firstOrCreate(
            ['class_id' => $class->id, 'name' => 'Section A'],
            [
                'capacity' => 50,
                'teacher_id' => null,
                'status' => 'active',
            ]
        );

        // Create Student Users
        $studentUsers = [];
        
        // Create first student with custom credentials
        $user = User::updateOrCreate(
            ['email' => 'student@school.com'],
            [
                'name' => "Student 1",
                'password' => Hash::make('password123'),
                'phone' => "01712111111",
                'address' => "123 Student Street, Dhaka",
                'status' => 'active',
            ]
        );
        $user->assignRole('Student');
        $studentUsers[] = $user;
        
        // Create additional students
        for ($i = 2; $i <= 3; $i++) {
            $user = User::updateOrCreate(
                ['email' => "student{$i}@demo.com"],
                [
                    'name' => "Student {$i}",
                    'password' => Hash::make('password'),
                    'phone' => "01712{$i}{$i}{$i}{$i}{$i}{$i}",
                    'address' => "123 Student Street, Dhaka",
                    'status' => 'active',
                ]
            );
            $user->assignRole('Student');
            $studentUsers[] = $user;
        }

        // Create Student Records
        $students = [];
        foreach ($studentUsers as $index => $user) {
            $student = Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'roll_number' => 'STU' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'class_id' => $class->id,
                    'section_id' => $section->id,
                    'academic_year_id' => $academicYear->id,
                    'admission_number' => 'ADM2024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'admission_date' => now()->subMonths(6),
                    'date_of_birth' => now()->subYears(16)->format('Y-m-d'),
                    'gender' => $index % 2 == 0 ? 'male' : 'female',
                    'blood_group' => ['A+', 'B+', 'O+'][$index % 3],
                    'religion' => 'Islam',
                    'nationality' => 'Bangladeshi',
                    'current_address' => $user->address,
                    'permanent_address' => $user->address,
                    'father_name' => "Father of Student " . ($index + 1),
                    'father_phone' => "01812{$index}{$index}{$index}{$index}{$index}{$index}",
                    'father_occupation' => 'Business',
                    'mother_name' => "Mother of Student " . ($index + 1),
                    'mother_phone' => "01912{$index}{$index}{$index}{$index}{$index}{$index}",
                    'mother_occupation' => 'Housewife',
                    'status' => 'active',
                ]
            );
            $students[] = $student;
        }

        $this->command->info('Created ' . count($students) . ' students');

        // Create Subjects
        $subjects = [];
        $subjectData = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'type' => 'theory'],
            ['name' => 'English', 'code' => 'ENG', 'type' => 'theory'],
            ['name' => 'Physics', 'code' => 'PHY', 'type' => 'both'],
            ['name' => 'Chemistry', 'code' => 'CHEM', 'type' => 'both'],
            ['name' => 'Biology', 'code' => 'BIO', 'type' => 'both'],
        ];
        
        foreach ($subjectData as $subData) {
            $subject = Subject::firstOrCreate(
                ['code' => $subData['code']],
                [
                    'name' => $subData['name'],
                    'type' => $subData['type'],
                    'total_marks' => 100,
                    'passing_marks' => 40,
                    'status' => 'active',
                ]
            );
            $subjects[] = $subject;
        }

        // Create Attendance Records (Last 30 days)
        // Get a teacher or admin user to mark attendance
        $markedByUser = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Super Admin', 'Teacher']);
        })->first();
        
        if (!$markedByUser) {
            // Create a system user if none exists
            $markedByUser = User::create([
                'name' => 'System',
                'email' => 'system@school.com',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]);
        }

        foreach ($students as $student) {
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                if ($date->isWeekday()) {
                    $status = $i % 10 == 0 ? 'absent' : ($i % 15 == 0 ? 'late' : 'present');
                    Attendance::create([
                        'attendable_type' => 'App\Models\Student',
                        'attendable_id' => $student->id,
                        'class_id' => $class->id,
                        'section_id' => $section->id,
                        'date' => $date->format('Y-m-d'),
                        'status' => $status,
                        'marked_by' => $markedByUser->id,
                        'remarks' => $status == 'absent' ? 'Absent' : null,
                    ]);
                }
            }
        }
        $this->command->info('Created attendance records');

        // Create Exams
        $exams = [
            [
                'name' => 'First Terminal Exam',
                'academic_year_id' => $academicYear->id,
                'type' => 'final',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->subMonths(2)->addDays(7),
                'status' => 'completed',
                'publish_results' => true,
                'description' => 'First terminal examination',
            ],
            [
                'name' => 'Mid-Term Exam',
                'academic_year_id' => $academicYear->id,
                'type' => 'mid-term',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(17),
                'status' => 'scheduled',
                'publish_results' => false,
                'description' => 'Mid-term examination',
            ],
        ];

        foreach ($exams as $examData) {
            $exam = Exam::create($examData);

            // Create Exam Schedules and store them
            $examSchedules = [];
            foreach ($subjects as $index => $subject) {
                $examSchedule = ExamSchedule::create([
                    'exam_id' => $exam->id,
                    'subject_id' => $subject->id,
                    'class_id' => $class->id,
                    'section_id' => $section->id,
                    'exam_date' => $examData['start_date']->copy()->addDays($index),
                    'start_time' => '09:00:00',
                    'end_time' => '12:00:00',
                    'room_number' => 'Room ' . ($index + 1),
                    'total_marks' => 100,
                    'passing_marks' => 40,
                ]);
                $examSchedules[$subject->id] = $examSchedule;
            }

            // Create Marks for completed exam
            if ($exam->status == 'completed') {
                foreach ($students as $student) {
                    foreach ($subjects as $subject) {
                        Mark::create([
                            'exam_id' => $exam->id,
                            'exam_schedule_id' => $examSchedules[$subject->id]->id,
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'marks_obtained' => rand(60, 95),
                            'total_marks' => 100,
                            'grade' => 'A',
                        ]);
                    }
                }
            }
        }
        $this->command->info('Created exams and marks');

        // Create Assignments Table if not exists
        if (!DB::getSchemaBuilder()->hasTable('assignments')) {
            DB::statement('CREATE TABLE assignments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                class_id INTEGER NOT NULL,
                subject_id INTEGER NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                due_date DATE NOT NULL,
                file_path VARCHAR(255),
                created_at TIMESTAMP,
                updated_at TIMESTAMP
            )');
        }

        // Create Assignments
        foreach ($subjects as $subject) {
            for ($i = 1; $i <= 3; $i++) {
                DB::table('assignments')->insert([
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'title' => "Assignment {$i} - {$subject->name}",
                    'description' => "Complete the exercises from chapter {$i}. Submit handwritten solutions.",
                    'due_date' => now()->addDays(7 * $i)->format('Y-m-d'),
                    'file_path' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('Created assignments');

        // Create Fee Category
        $feeCategory = FeeCategory::firstOrCreate(
            ['name' => 'Tuition Fee'],
            [
                'description' => 'Monthly tuition fee',
                'amount' => 5000.00,
                'status' => 'active',
            ]
        );

        // Create Fee Invoices
        $createdInvoices = [];
        foreach ($students as $student) {
            // Paid invoice
            $paidInvoice = FeeInvoice::updateOrCreate(
                ['invoice_number' => 'INV-' . date('Y') . '-' . str_pad($student->id * 100, 6, '0', STR_PAD_LEFT)],
                [
                    'student_id' => $student->id,
                    'fee_category_id' => $feeCategory->id,
                    'academic_year_id' => $academicYear->id,
                    'amount' => 5000.00,
                    'discount' => 0,
                    'fine' => 0,
                    'total_amount' => 5000.00,
                    'due_date' => now()->subMonth(),
                    'issue_date' => now()->subMonth()->subDays(5),
                    'status' => 'paid',
                    'created_by' => $markedByUser->id,
                ]
            );
            $createdInvoices[] = $paidInvoice;

            // Pending invoice
            $pendingInvoice = FeeInvoice::updateOrCreate(
                ['invoice_number' => 'INV-' . date('Y') . '-' . str_pad($student->id * 100 + 1, 6, '0', STR_PAD_LEFT)],
                [
                    'student_id' => $student->id,
                    'fee_category_id' => $feeCategory->id,
                    'academic_year_id' => $academicYear->id,
                    'amount' => 5000.00,
                    'discount' => 0,
                    'fine' => 0,
                    'total_amount' => 5000.00,
                    'due_date' => now()->addDays(15),
                    'issue_date' => now(),
                    'status' => 'pending',
                    'created_by' => $markedByUser->id,
                ]
            );
            $createdInvoices[] = $pendingInvoice;
        }
        $this->command->info('Created fee invoices');

        // Create Fee Payments for paid invoices
        $paidInvoices = FeeInvoice::where('status', 'paid')->get();
        foreach ($paidInvoices as $invoice) {
            FeePayment::updateOrCreate(
                ['fee_invoice_id' => $invoice->id],
                [
                    'receipt_number' => 'RCP-' . date('Y') . '-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT),
                    'student_id' => $invoice->student_id,
                    'amount_paid' => 5000.00,
                    'payment_date' => now()->subMonth(),
                    'payment_method' => 'cash',
                    'transaction_id' => 'TXN' . str_pad($invoice->student_id, 8, '0', STR_PAD_LEFT),
                    'received_by' => $markedByUser->id,
                ]
            );
        }
        $this->command->info('Created fee payments');

        // Get or create a book category
        $bookCategory = BookCategory::firstOrCreate(
            ['code' => 'TEXTBOOK'],
            [
                'name' => 'Textbooks',
                'description' => 'Educational textbooks',
                'status' => 'active',
            ]
        );

        // Create Books
        $books = [
            ['title' => 'Physics for Class 10', 'author' => 'Dr. Ahmed', 'isbn' => 'PHY10-001', 'quantity' => 5],
            ['title' => 'Chemistry Basics', 'author' => 'Prof. Rahman', 'isbn' => 'CHE10-001', 'quantity' => 5],
            ['title' => 'English Grammar', 'author' => 'Sarah Khan', 'isbn' => 'ENG10-001', 'quantity' => 5],
        ];

        $bookIds = [];
        foreach ($books as $bookData) {
            $book = Book::firstOrCreate(
                ['isbn' => $bookData['isbn']],
                [
                    'title' => $bookData['title'],
                    'author' => $bookData['author'],
                    'publisher' => 'National Publishers',
                    'publication_year' => 2023,
                    'category_id' => $bookCategory->id,
                    'language' => 'English',
                    'total_copies' => $bookData['quantity'],
                    'available_copies' => $bookData['quantity'] - 1,
                    'price' => 500.00,
                    'rack_location' => 'R' . rand(1, 10),
                    'status' => 'available',
                ]
            );
            $bookIds[] = $book->id;
        }

        // Issue Books to Students
        foreach ($students as $index => $student) {
            if (isset($bookIds[$index])) {
                BookIssue::create([
                    'book_id' => $bookIds[$index],
                    'student_id' => $student->id,
                    'issue_date' => now()->subDays(10),
                    'due_date' => now()->addDays(4),
                    'return_date' => null,
                    'status' => 'issued',
                    'fine_amount' => 0,
                    'issued_by' => $markedByUser->id,
                ]);
            }
        }
        $this->command->info('Created library records');

        // Create Leave Requests
        foreach ($students as $student) {
            LeaveRequest::create([
                'leaveable_type' => 'App\Models\Student',
                'leaveable_id' => $student->id,
                'leave_type' => 'sick',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(7),
                'reason' => 'Medical appointment',
                'status' => 'pending',
            ]);
        }
        $this->command->info('Created leave requests');

        $this->command->info('✅ Student portal sample data created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Email: student@school.com | Password: password123');
        $this->command->info('Email: student2@demo.com | Password: password');
        $this->command->info('Email: student3@demo.com | Password: password');
    }
}
