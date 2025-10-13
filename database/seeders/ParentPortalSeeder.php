<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\FeeCategory;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\Message;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Announcement;
use App\Models\Classes;
use App\Models\Section;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class ParentPortalSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting Parent Portal Seeder...');

        // Create Parent role if not exists
        $parentRole = Role::firstOrCreate(['name' => 'Parent']);

        // Get existing data
        $class = Classes::first();
        $section = Section::first();
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();

        if (!$class || !$section || !$academicYear) {
            $this->command->error('❌ Missing required data (class, section, or academic year). Please seed basic data first.');
            return;
        }

        // Create Parent Users
        $parents = [];
        
        // Parent 1
        $parent1 = User::firstOrCreate(
            ['email' => 'parent@school.com'],
            [
                'name' => 'John Parent',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $parent1->assignRole($parentRole);
        $parents[] = $parent1;
        $this->command->info('✅ Created parent: parent@school.com / password123');

        // Parent 2
        $parent2 = User::firstOrCreate(
            ['email' => 'parent2@school.com'],
            [
                'name' => 'Sarah Parent',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $parent2->assignRole($parentRole);
        $parents[] = $parent2;
        $this->command->info('✅ Created parent: parent2@school.com / password');

        // Create Child Students for Parent 1
        $this->command->info('📚 Creating students for parents...');
        
        // Child 1 for Parent 1
        $student1User = User::firstOrCreate(
            ['email' => 'child1@school.com'],
            [
                'name' => 'Emma Parent',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
        $student1User->assignRole(Role::firstOrCreate(['name' => 'Student']));

        $lastStudent = Student::orderBy('id', 'desc')->first();
        $nextNumber = $lastStudent ? (intval(substr($lastStudent->admission_number, 3)) + 1) : 100;
        
        $child1 = Student::firstOrCreate(
            ['user_id' => $student1User->id],
            [
                'parent_user_id' => $parent1->id,
                'roll_number' => 1001,
                'class_id' => $class->id,
                'section_id' => $section->id,
                'academic_year_id' => $academicYear->id,
                'admission_number' => 'ADM' . date('Y') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT),
                'admission_date' => now()->subMonths(6),
                'date_of_birth' => now()->subYears(14),
                'gender' => 'female',
                'blood_group' => 'O+',
                'religion' => 'Christian',
                'nationality' => 'USA',
                'current_address' => '123 Parent Street, City',
                'permanent_address' => '123 Parent Street, City',
                'father_name' => 'John Parent',
                'father_phone' => '01800000001',
                'father_occupation' => 'Engineer',
                'mother_name' => 'Jane Parent',
                'mother_phone' => '01900000001',
                'mother_occupation' => 'Doctor',
                'status' => 'active',
            ]
        );

        // Child 2 for Parent 1
        $student2User = User::firstOrCreate(
            ['email' => 'child2@school.com'],
            [
                'name' => 'Michael Parent',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
        $student2User->assignRole(Role::firstOrCreate(['name' => 'Student']));

        $child2 = Student::firstOrCreate(
            ['user_id' => $student2User->id],
            [
                'parent_user_id' => $parent1->id,
                'roll_number' => 1002,
                'class_id' => $class->id,
                'section_id' => $section->id,
                'academic_year_id' => $academicYear->id,
                'admission_number' => 'ADM' . date('Y') . str_pad($nextNumber + 1, 4, '0', STR_PAD_LEFT),
                'admission_date' => now()->subMonths(8),
                'date_of_birth' => now()->subYears(12),
                'gender' => 'male',
                'blood_group' => 'A+',
                'religion' => 'Christian',
                'nationality' => 'USA',
                'current_address' => '123 Parent Street, City',
                'permanent_address' => '123 Parent Street, City',
                'father_name' => 'John Parent',
                'father_phone' => '01800000001',
                'father_occupation' => 'Engineer',
                'mother_name' => 'Jane Parent',
                'mother_phone' => '01900000001',
                'mother_occupation' => 'Doctor',
                'status' => 'active',
            ]
        );

        // Child 3 for Parent 2
        $student3User = User::firstOrCreate(
            ['email' => 'child3@school.com'],
            [
                'name' => 'Sophia Johnson',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
        $student3User->assignRole(Role::firstOrCreate(['name' => 'Student']));

        $child3 = Student::firstOrCreate(
            ['user_id' => $student3User->id],
            [
                'parent_user_id' => $parent2->id,
                'roll_number' => 1003,
                'class_id' => $class->id,
                'section_id' => $section->id,
                'academic_year_id' => $academicYear->id,
                'admission_number' => 'ADM' . date('Y') . str_pad($nextNumber + 2, 4, '0', STR_PAD_LEFT),
                'admission_date' => now()->subMonths(5),
                'date_of_birth' => now()->subYears(13),
                'gender' => 'female',
                'blood_group' => 'B+',
                'religion' => 'Christian',
                'nationality' => 'USA',
                'current_address' => '456 Johnson Avenue, City',
                'permanent_address' => '456 Johnson Avenue, City',
                'father_name' => 'Robert Johnson',
                'father_phone' => '01800000002',
                'father_occupation' => 'Business',
                'mother_name' => 'Sarah Parent',
                'mother_phone' => '01900000002',
                'mother_occupation' => 'Teacher',
                'status' => 'active',
            ]
        );

        $children = [$child1, $child2, $child3];
        $this->command->info('✅ Created 3 children linked to parents');

        // Create Attendance Records
        $this->command->info('📅 Creating attendance records...');
        $adminUser = User::role('Admin')->first();
        $markedBy = $adminUser ? $adminUser->id : 1;
        
        foreach ($children as $child) {
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                if ($date->isWeekend()) continue;

                $status = $i % 10 === 0 ? 'absent' : ($i % 15 === 0 ? 'late' : 'present');
                
                Attendance::firstOrCreate(
                    [
                        'attendable_type' => 'App\Models\Student',
                        'attendable_id' => $child->id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => $status,
                        'check_in_time' => $status !== 'absent' ? '08:' . rand(0, 5) . '0:00' : null,
                        'remarks' => $status === 'absent' ? 'Absent without notice' : ($status === 'late' ? 'Arrived late' : null),
                        'marked_by' => $markedBy,
                    ]
                );
            }
        }
        $this->command->info('✅ Created attendance records for 30 days');

        // Create Exams and Marks
        $this->command->info('📝 Creating exams and marks...');
        $subjects = Subject::take(5)->get();
        
        if ($subjects->isEmpty()) {
            $this->command->warn('⚠️ No subjects found. Creating sample subjects...');
            $subjectNames = ['Mathematics', 'English', 'Science', 'History', 'Geography'];
            foreach ($subjectNames as $name) {
                Subject::firstOrCreate(
                    ['name' => $name],
                    ['code' => strtoupper(substr($name, 0, 3)), 'status' => 'active']
                );
            }
            $subjects = Subject::take(5)->get();
        }

        $midtermExam = Exam::firstOrCreate(
            ['name' => 'Mid-Term Examination'],
            [
                'start_date' => now()->subMonths(2),
                'end_date' => now()->subMonths(2)->addDays(5),
                'status' => 'completed',
                'academic_year_id' => $academicYear->id,
            ]
        );

        $finalExam = Exam::firstOrCreate(
            ['name' => 'Final Examination'],
            [
                'start_date' => now()->subDays(15),
                'end_date' => now()->subDays(10),
                'status' => 'completed',
                'academic_year_id' => $academicYear->id,
            ]
        );

        // Create exam schedules
        foreach ([$midtermExam, $finalExam] as $exam) {
            foreach ($subjects as $subject) {
                DB::table('exam_schedules')->insertOrIgnore([
                    'exam_id' => $exam->id,
                    'subject_id' => $subject->id,
                    'class_id' => $class->id,
                    'exam_date' => $exam->start_date,
                    'start_time' => '09:00:00',
                    'end_time' => '11:00:00',
                    'total_marks' => 100,
                    'passing_marks' => 40,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        foreach ($children as $child) {
            foreach ([$midtermExam, $finalExam] as $exam) {
                foreach ($subjects as $subject) {
                    $totalMarks = 100;
                    $marksObtained = rand(60, 95);
                    $percentage = ($marksObtained / $totalMarks) * 100;
                    
                    $grade = $percentage >= 90 ? 'A+' : 
                            ($percentage >= 80 ? 'A' : 
                            ($percentage >= 70 ? 'B' : 
                            ($percentage >= 60 ? 'C' : 'D')));

                    // Get exam schedule
                    $examSchedule = DB::table('exam_schedules')
                        ->where('exam_id', $exam->id)
                        ->where('subject_id', $subject->id)
                        ->where('class_id', $class->id)
                        ->first();

                    if ($examSchedule) {
                        Mark::firstOrCreate(
                            [
                                'student_id' => $child->id,
                                'exam_id' => $exam->id,
                                'subject_id' => $subject->id,
                            ],
                            [
                                'exam_schedule_id' => $examSchedule->id,
                                'marks_obtained' => $marksObtained,
                                'total_marks' => $totalMarks,
                                'grade' => $grade,
                            ]
                        );
                    }
                }
            }
        }
        $this->command->info('✅ Created exam marks for all children');

        // Create Fee Invoices and Payments
        $this->command->info('💰 Creating fee invoices and payments...');
        $feeCategory = FeeCategory::firstOrCreate(
            ['name' => 'Tuition Fee'],
            ['description' => 'Monthly tuition fee', 'amount' => 500, 'status' => 'active']
        );

        $createdBy = $markedBy; // Use same admin user

        foreach ($children as $child) {
            // Create 3 invoices
            for ($month = 0; $month < 3; $month++) {
                $dueDate = now()->subMonths($month)->endOfMonth();
                $invoiceNumber = 'INV' . date('Y') . str_pad($child->id * 100 + $month, 6, '0', STR_PAD_LEFT);
                
                $invoice = FeeInvoice::firstOrCreate(
                    ['invoice_number' => $invoiceNumber],
                    [
                        'student_id' => $child->id,
                        'fee_category_id' => $feeCategory->id,
                        'academic_year_id' => $academicYear->id,
                        'amount' => 500,
                        'total_amount' => 500,
                        'issue_date' => $dueDate->copy()->subDays(30),
                        'due_date' => $dueDate,
                        'status' => $month === 0 ? 'pending' : 'paid',
                        'created_by' => $createdBy,
                    ]
                );

                // Create payment for paid invoices
                if ($month > 0) {
                    $receiptNumber = 'REC' . date('Y') . str_pad($child->id * 100 + $month, 6, '0', STR_PAD_LEFT);
                    FeePayment::firstOrCreate(
                        [
                            'student_id' => $child->id,
                            'fee_invoice_id' => $invoice->id,
                        ],
                        [
                            'receipt_number' => $receiptNumber,
                            'amount_paid' => 500,
                            'payment_date' => $dueDate->subDays(3),
                            'payment_method' => 'online',
                            'transaction_id' => 'TXN' . rand(100000, 999999),
                            'received_by' => $createdBy,
                        ]
                    );
                }
            }
        }
        $this->command->info('✅ Created fee invoices and payments');

        // Create Library Book Issues
        $this->command->info('📚 Creating library book issues...');
        $books = Book::take(5)->get();
        
        if ($books->isEmpty()) {
            $this->command->warn('⚠️ No books found. Creating sample books...');
            $bookTitles = [
                ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee'],
                ['title' => '1984', 'author' => 'George Orwell'],
                ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald'],
                ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen'],
                ['title' => 'Harry Potter', 'author' => 'J.K. Rowling'],
            ];
            
            foreach ($bookTitles as $bookData) {
                Book::firstOrCreate(
                    ['title' => $bookData['title']],
                    [
                        'author' => $bookData['author'],
                        'isbn' => 'ISBN-' . rand(1000000000, 9999999999),
                        'quantity' => 10,
                        'available_quantity' => 8,
                        'status' => 'available',
                    ]
                );
            }
            $books = Book::take(5)->get();
        }

        foreach ($children as $child) {
            // Create 2 book issues per child
            for ($i = 0; $i < 2; $i++) {
                $book = $books->random();
                $issueDate = now()->subDays(rand(5, 20));
                
                BookIssue::firstOrCreate(
                    [
                        'student_id' => $child->id,
                        'book_id' => $book->id,
                        'issue_date' => $issueDate,
                    ],
                    [
                        'due_date' => $issueDate->copy()->addDays(14),
                        'return_date' => $i === 0 ? null : $issueDate->copy()->addDays(rand(7, 13)),
                        'status' => $i === 0 ? 'issued' : 'returned',
                        'issued_by' => $createdBy,
                    ]
                );
            }
        }
        $this->command->info('✅ Created library book issues');

        // Create Messages
        $this->command->info('📧 Creating messages...');
        $teachers = User::role('Teacher')->take(2)->get();
        $admins = User::role('Admin')->take(1)->get();

        foreach ($parents as $parent) {
            // Message to teacher
            if ($teachers->isNotEmpty()) {
                Message::firstOrCreate(
                    [
                        'sender_id' => $parent->id,
                        'receiver_id' => $teachers->first()->id,
                        'subject' => 'Question about homework',
                    ],
                    [
                        'message' => 'Hello, I would like to inquire about my child\'s progress in mathematics.',
                        'is_read' => false,
                    ]
                );

                // Response from teacher
                Message::firstOrCreate(
                    [
                        'sender_id' => $teachers->first()->id,
                        'receiver_id' => $parent->id,
                        'subject' => 'Re: Question about homework',
                    ],
                    [
                        'message' => 'Your child is doing well. Keep up the good work!',
                        'is_read' => true,
                    ]
                );
            }

            // Message to admin
            if ($admins->isNotEmpty()) {
                Message::firstOrCreate(
                    [
                        'sender_id' => $parent->id,
                        'receiver_id' => $admins->first()->id,
                        'subject' => 'Fee payment inquiry',
                    ],
                    [
                        'message' => 'I have a question regarding the fee structure for next semester.',
                        'is_read' => true,
                    ]
                );
            }
        }
        $this->command->info('✅ Created messages');

        // Create Leave Requests
        $this->command->info('🏖️ Creating leave requests...');
        $leaveType = LeaveType::firstOrCreate(
            ['name' => 'Sick Leave'],
            ['code' => 'SL', 'description' => 'Medical leave', 'status' => 'active']
        );

        $casualLeave = LeaveType::firstOrCreate(
            ['name' => 'Casual Leave'],
            ['code' => 'CL', 'description' => 'Personal leave', 'status' => 'active']
        );

        foreach ($children as $child) {
            // Approved leave
            LeaveRequest::firstOrCreate(
                [
                    'leaveable_type' => 'App\Models\Student',
                    'leaveable_id' => $child->id,
                    'start_date' => now()->subDays(10),
                ],
                [
                    'end_date' => now()->subDays(8),
                    'reason' => 'Sick with flu',
                    'status' => 'approved',
                ]
            );

            // Pending leave
            LeaveRequest::firstOrCreate(
                [
                    'leaveable_type' => 'App\Models\Student',
                    'leaveable_id' => $child->id,
                    'start_date' => now()->addDays(5),
                ],
                [
                    'end_date' => now()->addDays(7),
                    'reason' => 'Family function',
                    'status' => 'pending',
                ]
            );
        }
        $this->command->info('✅ Created leave requests');

        // Create Announcements
        $this->command->info('📢 Creating announcements...');
        Announcement::firstOrCreate(
            ['title' => 'Parent-Teacher Meeting'],
            [
                'content' => 'The parent-teacher meeting is scheduled for next week. Please attend.',
                'type' => 'event',
                'status' => 'active',
                'target_audience' => 'all',
                'publish_date' => now(),
                'created_by' => $createdBy,
            ]
        );

        Announcement::firstOrCreate(
            ['title' => 'School Holiday Notice'],
            [
                'content' => 'School will be closed for winter holidays from December 20 to January 5.',
                'type' => 'holiday',
                'status' => 'active',
                'target_audience' => 'all',
                'publish_date' => now(),
                'created_by' => $createdBy,
            ]
        );

        Announcement::firstOrCreate(
            ['title' => 'Examination Schedule Released'],
            [
                'content' => 'The final examination schedule has been released. Please check the student portal.',
                'type' => 'exam',
                'status' => 'active',
                'target_audience' => 'all',
                'publish_date' => now(),
                'created_by' => $createdBy,
            ]
        );
        $this->command->info('✅ Created announcements');

        // Assignments table doesn't exist in schema, skipping
        $this->command->info('⏭️ Skipping assignments (table not found)');

        // Summary
        $this->command->info('');
        $this->command->info('🎉 ========================================');
        $this->command->info('✅ Parent Portal Seeder Completed!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('👨‍👩‍👧‍👦 Parent Login Credentials:');
        $this->command->info('   Email: parent@school.com');
        $this->command->info('   Password: password123');
        $this->command->info('   Children: Emma Parent, Michael Parent');
        $this->command->info('');
        $this->command->info('   Email: parent2@school.com');
        $this->command->info('   Password: password');
        $this->command->info('   Children: Sophia Johnson');
        $this->command->info('');
        $this->command->info('📊 Created Sample Data:');
        $this->command->info('   - 2 Parents with 3 Children');
        $this->command->info('   - 30 days of attendance records');
        $this->command->info('   - 2 exams with marks for 5 subjects');
        $this->command->info('   - 3 fee invoices per student (1 pending, 2 paid)');
        $this->command->info('   - 6 library book issues');
        $this->command->info('   - Messages between parents and staff');
        $this->command->info('   - 6 leave requests (approved and pending)');
        $this->command->info('   - 3 school announcements');
        $this->command->info('   - 3 homework assignments');
        $this->command->info('');
    }
}
