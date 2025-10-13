<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportTemplate;
use Illuminate\Support\Facades\Auth;

class ReportTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            // Academic Reports
            [
                'name' => 'Student List Report',
                'slug' => 'student-list-report',
                'category' => 'student',
                'description' => 'Complete list of students with class and section details',
                'parameters' => [
                    'class_id' => 'optional',
                    'section_id' => 'optional',
                    'status' => 'optional'
                ],
                'columns' => [
                    'admission_number', 'name', 'class', 'section', 'status', 'contact', 'parent_name'
                ],
                'query' => null,
                'controller_method' => 'studentReport',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Student Performance Report',
                'slug' => 'student-performance-report',
                'category' => 'academic',
                'description' => 'Student performance analysis by exam and class',
                'parameters' => [
                    'exam_id' => 'required',
                    'class_id' => 'optional'
                ],
                'columns' => [
                    'student_name', 'class', 'subject', 'marks_obtained', 'total_marks', 'percentage', 'grade', 'result'
                ],
                'query' => null,
                'controller_method' => 'examReport',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Class Wise Performance',
                'slug' => 'class-wise-performance',
                'category' => 'academic',
                'description' => 'Average performance comparison across classes',
                'parameters' => [
                    'exam_id' => 'optional'
                ],
                'columns' => [
                    'class', 'total_students', 'average_marks', 'pass_percentage', 'highest_marks', 'lowest_marks'
                ],
                'query' => null,
                'controller_method' => 'classPerformanceReport',
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Attendance Reports
            [
                'name' => 'Daily Attendance Report',
                'slug' => 'daily-attendance-report',
                'category' => 'attendance',
                'description' => 'Daily attendance summary of students',
                'parameters' => [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'class_id' => 'optional'
                ],
                'columns' => [
                    'date', 'student_name', 'class', 'status', 'remarks'
                ],
                'query' => null,
                'controller_method' => 'attendanceReport',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Monthly Attendance Summary',
                'slug' => 'monthly-attendance-summary',
                'category' => 'attendance',
                'description' => 'Month-wise attendance statistics',
                'parameters' => [
                    'month' => 'required',
                    'year' => 'required',
                    'class_id' => 'optional'
                ],
                'columns' => [
                    'student_name', 'class', 'total_days', 'present', 'absent', 'late', 'percentage'
                ],
                'query' => null,
                'controller_method' => 'monthlyAttendanceReport',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Attendance Defaulters',
                'slug' => 'attendance-defaulters',
                'category' => 'attendance',
                'description' => 'Students with low attendance percentage',
                'parameters' => [
                    'threshold' => 'optional',
                    'month' => 'optional'
                ],
                'columns' => [
                    'student_name', 'class', 'percentage', 'total_absent', 'contact'
                ],
                'query' => null,
                'controller_method' => 'attendanceDefaultersReport',
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Financial Reports
            [
                'name' => 'Fee Collection Report',
                'slug' => 'fee-collection-report',
                'category' => 'financial',
                'description' => 'Detailed fee collection transactions',
                'parameters' => [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'status' => 'optional'
                ],
                'columns' => [
                    'receipt_no', 'date', 'student_name', 'class', 'fee_type', 'amount', 'payment_method', 'status'
                ],
                'query' => null,
                'controller_method' => 'feeReport',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Fee Defaulters Report',
                'slug' => 'fee-defaulters-report',
                'category' => 'financial',
                'description' => 'Students with pending fee payments',
                'parameters' => [
                    'class_id' => 'optional',
                    'status' => 'optional'
                ],
                'columns' => [
                    'student_name', 'class', 'total_fee', 'paid_amount', 'pending_amount', 'due_date', 'contact'
                ],
                'query' => null,
                'controller_method' => 'feeDefaultersReport',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Monthly Income Report',
                'slug' => 'monthly-income-report',
                'category' => 'financial',
                'description' => 'Month-wise income breakdown',
                'parameters' => [
                    'start_date' => 'required',
                    'end_date' => 'required'
                ],
                'columns' => [
                    'month', 'fee_collection', 'other_income', 'total_income'
                ],
                'query' => null,
                'controller_method' => 'monthlyIncomeReport',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Expense Report',
                'slug' => 'expense-report',
                'category' => 'financial',
                'description' => 'Detailed expense records by category',
                'parameters' => [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'category' => 'optional'
                ],
                'columns' => [
                    'date', 'category', 'description', 'amount', 'approved_by', 'status'
                ],
                'query' => null,
                'controller_method' => 'expenseReport',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Income vs Expense Report',
                'slug' => 'income-vs-expense-report',
                'category' => 'financial',
                'description' => 'Comparative analysis of income and expenses',
                'parameters' => [
                    'start_date' => 'required',
                    'end_date' => 'required'
                ],
                'columns' => [
                    'month', 'total_income', 'total_expense', 'net_profit', 'profit_percentage'
                ],
                'query' => null,
                'controller_method' => 'financialReport',
                'is_active' => true,
                'sort_order' => 5,
            ],
            
            // Teacher Reports
            [
                'name' => 'Teacher List Report',
                'slug' => 'teacher-list-report',
                'category' => 'teacher',
                'description' => 'Complete list of teachers with details',
                'parameters' => [
                    'department' => 'optional',
                    'status' => 'optional'
                ],
                'columns' => [
                    'employee_id', 'name', 'department', 'subjects', 'classes', 'status', 'contact'
                ],
                'query' => null,
                'controller_method' => 'teacherReport',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Teacher Attendance Report',
                'slug' => 'teacher-attendance-report',
                'category' => 'teacher',
                'description' => 'Teacher attendance and leave records',
                'parameters' => [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'teacher_id' => 'optional'
                ],
                'columns' => [
                    'date', 'teacher_name', 'department', 'status', 'remarks'
                ],
                'query' => null,
                'controller_method' => 'teacherAttendanceReport',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Teacher Workload Report',
                'slug' => 'teacher-workload-report',
                'category' => 'teacher',
                'description' => 'Teacher class and subject assignment analysis',
                'parameters' => [],
                'columns' => [
                    'teacher_name', 'department', 'total_classes', 'total_subjects', 'weekly_hours'
                ],
                'query' => null,
                'controller_method' => 'teacherWorkloadReport',
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Library Reports
            [
                'name' => 'Book Issue Report',
                'slug' => 'book-issue-report',
                'category' => 'library',
                'description' => 'Book issue and return records',
                'parameters' => [
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'status' => 'optional'
                ],
                'columns' => [
                    'issue_date', 'book_title', 'borrower_name', 'due_date', 'return_date', 'status', 'fine'
                ],
                'query' => null,
                'controller_method' => 'bookIssueReport',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Overdue Books Report',
                'slug' => 'overdue-books-report',
                'category' => 'library',
                'description' => 'List of overdue books with fines',
                'parameters' => [],
                'columns' => [
                    'book_title', 'borrower_name', 'issue_date', 'due_date', 'days_overdue', 'fine_amount', 'contact'
                ],
                'query' => null,
                'controller_method' => 'overdueBookReport',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Popular Books Report',
                'slug' => 'popular-books-report',
                'category' => 'library',
                'description' => 'Most issued books statistics',
                'parameters' => [
                    'start_date' => 'optional',
                    'end_date' => 'optional'
                ],
                'columns' => [
                    'book_title', 'author', 'category', 'issue_count', 'availability'
                ],
                'query' => null,
                'controller_method' => 'popularBookReport',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($templates as $template) {
            $template['created_by'] = 1; // Default admin user
            ReportTemplate::create($template);
        }
    }
}
