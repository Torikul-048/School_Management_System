<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\FeeCollection;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\Scholarship;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AccountantTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating Accountant Test Data...');

        // 1. Create Payment Methods
        $this->command->info('Creating Payment Methods...');
        $paymentMethods = [
            ['name' => 'Cash', 'code' => 'cash', 'description' => 'Cash payment', 'status' => 'active'],
            ['name' => 'Cheque', 'code' => 'cheque', 'description' => 'Cheque payment', 'status' => 'active'],
            ['name' => 'Bank Transfer', 'code' => 'bank', 'description' => 'Online bank transfer', 'status' => 'active'],
            ['name' => 'Credit Card', 'code' => 'card', 'description' => 'Credit card payment', 'status' => 'active'],
            ['name' => 'Bkash', 'code' => 'bkash', 'description' => 'Bkash mobile payment', 'status' => 'active'],
            ['name' => 'Nagad', 'code' => 'nagad', 'description' => 'Nagad mobile payment', 'status' => 'active'],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate(['code' => $method['code']], $method);
        }

        $cash = PaymentMethod::where('code', 'cash')->first();
        $cheque = PaymentMethod::where('code', 'cheque')->first();
        $bankTransfer = PaymentMethod::where('code', 'bank')->first();

        // 2. Create Classes if not exist
        $this->command->info('Creating Classes and Sections...');
        
        // Create Academic Year first
        $academicYear = AcademicYear::firstOrCreate(
            ['is_current' => true],
            [
                'name' => '2025-2026',
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'is_current' => true,
            ]
        );
        
        $classes = [];
        foreach (range(1, 10) as $i) {
            $class = Classes::firstOrCreate(
                ['name' => "Class $i", 'academic_year_id' => $academicYear->id],
                [
                    'name' => "Class $i",
                    'numeric_name' => (string)$i,
                    'academic_year_id' => $academicYear->id,
                    'capacity' => 40,
                    'description' => "Standard Class $i",
                    'status' => 'active',
                ]
            );
            $classes[] = $class;
        }

        // 3. Create Students
        $this->command->info('Creating Students...');
        $students = [];
        $studentNames = [
            ['John', 'Smith'], ['Emma', 'Johnson'], ['Michael', 'Williams'], ['Sophia', 'Brown'],
            ['William', 'Jones'], ['Olivia', 'Garcia'], ['James', 'Martinez'], ['Ava', 'Rodriguez'],
            ['Robert', 'Wilson'], ['Isabella', 'Anderson'], ['David', 'Taylor'], ['Mia', 'Thomas'],
            ['Joseph', 'Hernandez'], ['Charlotte', 'Moore'], ['Thomas', 'Martin'], ['Amelia', 'Jackson'],
            ['Charles', 'Thompson'], ['Harper', 'White'], ['Daniel', 'Lopez'], ['Evelyn', 'Lee'],
            ['Matthew', 'Gonzalez'], ['Abigail', 'Harris'], ['Anthony', 'Clark'], ['Emily', 'Lewis'],
            ['Mark', 'Robinson'], ['Elizabeth', 'Walker'], ['Donald', 'Perez'], ['Sofia', 'Hall'],
            ['Steven', 'Young'], ['Avery', 'Allen'], ['Paul', 'Sanchez'], ['Ella', 'Wright'],
            ['Andrew', 'King'], ['Scarlett', 'Scott'], ['Joshua', 'Green'], ['Grace', 'Baker'],
            ['Kenneth', 'Adams'], ['Chloe', 'Nelson'], ['Kevin', 'Hill'], ['Victoria', 'Ramirez'],
        ];

        foreach ($studentNames as $index => $name) {
            $user = User::firstOrCreate(
                ['email' => strtolower($name[0] . '.' . $name[1]) . '@student.school.com'],
                [
                    'name' => $name[0] . ' ' . $name[1],
                    'email' => strtolower($name[0] . '.' . $name[1]) . '@student.school.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('Student')) {
                $user->assignRole('Student');
            }

            $class = $classes[array_rand($classes)];
            
            // Get or create first section for this class
            $section = Section::firstOrCreate(
                ['class_id' => $class->id, 'name' => 'Section A'],
                [
                    'class_id' => $class->id,
                    'name' => 'Section A',
                    'room_number' => 'Room ' . $class->id . 'A',
                    'capacity' => 40,
                    'status' => 'active',
                ]
            );

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'admission_number' => 'STU' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                    'admission_date' => Carbon::now()->subMonths(rand(1, 24))->format('Y-m-d'),
                    'class_id' => $class->id,
                    'section_id' => $section->id,
                    'academic_year_id' => $academicYear->id,
                    'roll_number' => $index + 1,
                    'date_of_birth' => Carbon::now()->subYears(rand(6, 18))->format('Y-m-d'),
                    'gender' => rand(0, 1) ? 'male' : 'female',
                    'blood_group' => ['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-'][array_rand(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-'])],
                    'religion' => ['Christian', 'Muslim', 'Hindu', 'Buddhist', 'Other'][array_rand(['Christian', 'Muslim', 'Hindu', 'Buddhist', 'Other'])],
                    'nationality' => 'USA',
                    'current_address' => rand(100, 999) . ' Main Street, City, State',
                    'permanent_address' => rand(100, 999) . ' Main Street, City, State',
                    'father_name' => $name[1] . ' Sr.',
                    'father_phone' => '+1-555-' . rand(1000, 9999),
                    'father_occupation' => ['Engineer', 'Doctor', 'Teacher', 'Businessman'][array_rand(['Engineer', 'Doctor', 'Teacher', 'Businessman'])],
                    'mother_name' => 'Mrs. ' . $name[1],
                    'mother_phone' => '+1-555-' . rand(1000, 9999),
                    'mother_occupation' => ['Engineer', 'Doctor', 'Teacher', 'Homemaker'][array_rand(['Engineer', 'Doctor', 'Teacher', 'Homemaker'])],
                    'status' => 'active',
                ]
            );

            $students[] = $student;
        }

        // 4. Create Teachers
        $this->command->info('Creating Teachers...');
        $teachers = [];
        $teacherNames = [
            ['Robert', 'Johnson'], ['Mary', 'Williams'], ['Michael', 'Brown'], ['Patricia', 'Jones'],
            ['William', 'Garcia'], ['Jennifer', 'Miller'], ['David', 'Davis'], ['Linda', 'Rodriguez'],
            ['Richard', 'Martinez'], ['Barbara', 'Hernandez'], ['Joseph', 'Lopez'], ['Susan', 'Gonzalez'],
        ];

        foreach ($teacherNames as $index => $name) {
            $user = User::firstOrCreate(
                ['email' => strtolower($name[0] . '.' . $name[1]) . '@teacher.school.com'],
                [
                    'name' => $name[0] . ' ' . $name[1],
                    'email' => strtolower($name[0] . '.' . $name[1]) . '@teacher.school.com',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('Teacher')) {
                $user->assignRole('Teacher');
            }

            $teacher = Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'employee_id' => 'TECH' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'first_name' => $name[0],
                    'last_name' => $name[1],
                    'email' => strtolower($name[0] . '.' . $name[1]) . '@teacher.school.com',
                    'phone' => '+1-555-' . rand(1000, 9999),
                    'joining_date' => Carbon::now()->subMonths(rand(6, 60))->format('Y-m-d'),
                    'designation' => ['Senior Teacher', 'Assistant Teacher', 'Head of Department'][array_rand(['Senior Teacher', 'Assistant Teacher', 'Head of Department'])],
                    'qualification' => ['B.Ed', 'M.Ed', 'M.Sc', 'Ph.D'][array_rand(['B.Ed', 'M.Ed', 'M.Sc', 'Ph.D'])],
                    'specialization' => ['Mathematics', 'Science', 'English', 'History'][array_rand(['Mathematics', 'Science', 'English', 'History'])],
                    'experience_years' => rand(1, 20),
                    'date_of_birth' => Carbon::now()->subYears(rand(25, 55))->format('Y-m-d'),
                    'gender' => rand(0, 1) ? 'male' : 'female',
                    'blood_group' => ['A+', 'B+', 'O+', 'AB+'][array_rand(['A+', 'B+', 'O+', 'AB+'])],
                    'nationality' => 'USA',
                    'marital_status' => ['single', 'married'][array_rand(['single', 'married'])],
                    'address' => rand(100, 999) . ' Teacher Avenue, City, State',
                    'emergency_contact' => '+1-555-' . rand(1000, 9999),
                    'emergency_contact_name' => 'Emergency Contact',
                    'salary' => rand(30000, 80000),
                    'employment_type' => 'full-time',
                    'status' => 'active',
                ]
            );

            $teachers[] = $teacher;
        }

        // 5. Create Fee Structures
        $this->command->info('Creating Fee Structures...');
        $feeTypes = [
            ['name' => 'Tuition Fee', 'amount' => 5000, 'type' => 'tuition', 'frequency' => 'monthly'],
            ['name' => 'Library Fee', 'amount' => 500, 'type' => 'library', 'frequency' => 'yearly'],
            ['name' => 'Lab Fee', 'amount' => 800, 'type' => 'lab', 'frequency' => 'yearly'],
            ['name' => 'Sports Fee', 'amount' => 600, 'type' => 'sports', 'frequency' => 'yearly'],
            ['name' => 'Transport Fee', 'amount' => 1200, 'type' => 'transport', 'frequency' => 'monthly'],
            ['name' => 'Hostel Fee', 'amount' => 3000, 'type' => 'hostel', 'frequency' => 'monthly'],
            ['name' => 'Examination Fee', 'amount' => 400, 'type' => 'examination', 'frequency' => 'quarterly'],
        ];

        $feeStructures = [];
        foreach ($classes as $class) {
            foreach ($feeTypes as $feeType) {
                $fee = FeeStructure::firstOrCreate(
                    [
                        'class_id' => $class->id,
                        'name' => $feeType['name'],
                    ],
                    [
                        'class_id' => $class->id,
                        'name' => $feeType['name'],
                        'description' => $feeType['name'] . ' for ' . $class->name,
                        'amount' => $feeType['amount'],
                        'fee_type' => $feeType['type'],
                        'frequency' => $feeType['frequency'],
                        'applicable_from' => Carbon::now()->startOfYear(),
                        'applicable_to' => Carbon::now()->endOfYear(),
                        'is_mandatory' => true,
                        'status' => 'active',
                    ]
                );
                $feeStructures[] = $fee;
            }
        }

        // 6. Create Fee Collections
        $this->command->info('Creating Fee Collections...');
        $receiptCounter = 1;
        foreach ($students as $student) {
            // Get fee structures for student's class
            $studentFees = FeeStructure::where('class_id', $student->class_id)->get();
            
            // Randomly pay some fees
            $feesToPay = $studentFees->random(rand(1, min(5, $studentFees->count())));
            
            foreach ($feesToPay as $feeStructure) {
                $paymentDate = Carbon::now()->subDays(rand(0, 90));
                $paymentMethod = [$cash, $cheque, $bankTransfer][array_rand([$cash, $cheque, $bankTransfer])];
                
                FeeCollection::create([
                    'student_id' => $student->id,
                    'fee_structure_id' => $feeStructure->id,
                    'receipt_number' => 'RCP-' . $paymentDate->format('Ymd') . '-' . str_pad($receiptCounter++, 4, '0', STR_PAD_LEFT),
                    'fee_amount' => $feeStructure->amount,
                    'discount_amount' => rand(0, 1) ? rand(100, 500) : 0,
                    'paid_amount' => $feeStructure->amount - (rand(0, 1) ? rand(100, 500) : 0),
                    'payment_method_id' => $paymentMethod->id,
                    'payment_date' => $paymentDate,
                    'transaction_id' => $paymentMethod->name === 'Bank Transfer' ? 'TXN' . strtoupper(Str::random(10)) : null,
                    'cheque_number' => $paymentMethod->name === 'Cheque' ? 'CHQ' . rand(100000, 999999) : null,
                    'bank_name' => in_array($paymentMethod->name, ['Cheque', 'Bank Transfer']) ? ['State Bank', 'City Bank', 'National Bank'][array_rand(['State Bank', 'City Bank', 'National Bank'])] : null,
                    'status' => 'paid',
                    'remarks' => 'Payment received successfully',
                    'collected_by' => 1, // Admin user
                ]);
            }
        }

        // 7. Create Scholarships
        $this->command->info('Creating Scholarships...');
        $scholarships = [
            ['name' => 'Merit Scholarship', 'percentage' => 50, 'type' => 'merit'],
            ['name' => 'Sports Scholarship', 'percentage' => 30, 'type' => 'sports'],
            ['name' => 'Need-Based Scholarship', 'percentage' => 40, 'type' => 'need_based'],
            ['name' => 'Academic Excellence', 'percentage' => 60, 'type' => 'academic'],
            ['name' => 'Sibling Discount', 'percentage' => 20, 'type' => 'sibling'],
        ];

        foreach ($scholarships as $scholarship) {
            Scholarship::firstOrCreate(
                ['name' => $scholarship['name']],
                [
                    'name' => $scholarship['name'],
                    'description' => $scholarship['name'] . ' - ' . $scholarship['percentage'] . '% discount',
                    'scholarship_type' => $scholarship['type'],
                    'amount_type' => 'percentage',
                    'amount' => $scholarship['percentage'],
                    'start_date' => Carbon::now()->subMonths(3),
                    'end_date' => Carbon::now()->addMonths(9),
                    'eligibility_criteria' => 'Based on ' . $scholarship['type'] . ' performance',
                    'is_active' => true,
                ]
            );
        }

        // 8. Create Expenses
        $this->command->info('Creating Expenses...');
        $expenseCategories = [
            'salary' => ['Teacher Salary', 'Staff Salary'],
            'maintenance' => ['Building Repair', 'Equipment Maintenance', 'Garden Maintenance'],
            'utilities' => ['Electricity Bill', 'Water Bill', 'Internet Bill'],
            'supplies' => ['Office Supplies', 'Cleaning Supplies', 'Lab Equipment'],
            'transport' => ['Bus Fuel', 'Vehicle Maintenance'],
            'miscellaneous' => ['Event Expenses', 'Printing', 'Marketing'],
        ];

        $expenseCounter = 1;
        foreach ($expenseCategories as $category => $items) {
            foreach ($items as $item) {
                for ($i = 0; $i < rand(2, 5); $i++) {
                    $expenseDate = Carbon::now()->subDays(rand(0, 90));
                    $amount = rand(500, 10000);
                    $status = ['pending', 'approved', 'approved', 'approved'][array_rand(['pending', 'approved', 'approved', 'approved'])]; // More approved than pending
                    
                    Expense::create([
                        'expense_number' => 'EXP-' . $expenseDate->format('Ymd') . '-' . str_pad($expenseCounter++, 4, '0', STR_PAD_LEFT),
                        'title' => $item . ' - ' . $expenseDate->format('M Y'),
                        'category' => $category,
                        'amount' => $amount,
                        'expense_date' => $expenseDate,
                        'payment_method_id' => [$cash, $cheque, $bankTransfer][array_rand([$cash, $cheque, $bankTransfer])],
                        'description' => 'Payment for ' . $item,
                        'vendor_name' => 'Vendor ' . rand(1, 10),
                        'invoice_number' => 'INV' . rand(10000, 99999),
                        'status' => $status,
                        'approved_by' => $status === 'approved' ? 1 : null,
                        'approved_at' => $status === 'approved' ? $expenseDate->addDay() : null,
                        'created_by' => 1,
                    ]);
                }
            }
        }

        // 9. Create Payroll
        $this->command->info('Creating Payroll Records...');
        foreach ($teachers as $teacher) {
            // Create payroll for last 3 months
            for ($month = 2; $month >= 0; $month--) {
                $payrollDate = Carbon::now()->subMonths($month);
                
                $basicSalary = $teacher->salary ?? 50000;
                $allowances = $basicSalary * 0.3; // 30% allowances
                $deductions = $basicSalary * 0.1; // 10% deductions
                $netSalary = $basicSalary + $allowances - $deductions;
                
                $payroll = Payroll::create([
                    'teacher_id' => $teacher->id,
                    'month' => $payrollDate->month,
                    'year' => $payrollDate->year,
                    'basic_salary' => $basicSalary,
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'gross_salary' => $basicSalary + $allowances,
                    'net_salary' => $netSalary,
                    'payment_date' => $payrollDate->endOfMonth(),
                    'payment_method_id' => $bankTransfer->id,
                    'status' => $month > 0 ? 'paid' : 'pending',
                    'remarks' => 'Monthly salary for ' . $payrollDate->format('F Y'),
                ]);

                // Create payroll items
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'item_type' => 'earning',
                    'item_name' => 'Basic Salary',
                    'amount' => $basicSalary,
                ]);

                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'item_type' => 'earning',
                    'item_name' => 'House Rent Allowance',
                    'amount' => $basicSalary * 0.2,
                ]);

                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'item_type' => 'earning',
                    'item_name' => 'Transport Allowance',
                    'amount' => $basicSalary * 0.1,
                ]);

                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'item_type' => 'deduction',
                    'item_name' => 'Tax',
                    'amount' => $basicSalary * 0.08,
                ]);

                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'item_type' => 'deduction',
                    'item_name' => 'Insurance',
                    'amount' => $basicSalary * 0.02,
                ]);
            }
        }

        // 10. Create Invoices
        $this->command->info('Creating Invoices...');
        $invoiceCounter = 1;
        foreach ($students->random(20) as $student) {
            $invoiceDate = Carbon::now()->subDays(rand(0, 60));
            $dueDate = $invoiceDate->copy()->addDays(30);
            
            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . $invoiceDate->format('Ymd') . '-' . str_pad($invoiceCounter++, 4, '0', STR_PAD_LEFT),
                'student_id' => $student->id,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'subtotal' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'status' => ['draft', 'sent', 'paid', 'overdue'][array_rand(['draft', 'sent', 'paid', 'overdue'])],
                'notes' => 'Fee invoice for ' . $invoiceDate->format('F Y'),
                'created_by' => 1,
            ]);

            // Add invoice items
            $fees = FeeStructure::where('class_id', $student->class_id)->limit(rand(2, 4))->get();
            $subtotal = 0;
            
            foreach ($fees as $fee) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $fee->name,
                    'quantity' => 1,
                    'unit_price' => $fee->amount,
                    'amount' => $fee->amount,
                ]);
                $subtotal += $fee->amount;
            }

            // Update invoice totals
            $taxAmount = $subtotal * 0.05; // 5% tax
            $discountAmount = rand(0, 1) ? rand(100, 500) : 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;
            
            $invoice->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $invoice->status === 'paid' ? $totalAmount : 0,
            ]);
        }

        $this->command->info('✅ Accountant Test Data Created Successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('- Students: ' . Student::count());
        $this->command->info('- Teachers: ' . Teacher::count());
        $this->command->info('- Classes: ' . Classes::count());
        $this->command->info('- Fee Structures: ' . FeeStructure::count());
        $this->command->info('- Fee Collections: ' . FeeCollection::count());
        $this->command->info('- Scholarships: ' . Scholarship::count());
        $this->command->info('- Expenses: ' . Expense::count());
        $this->command->info('- Payroll Records: ' . Payroll::count());
        $this->command->info('- Invoices: ' . Invoice::count());
        $this->command->info('');
        $this->command->info('🔑 Test Credentials:');
        $this->command->info('Student: john.smith@student.school.com / password');
        $this->command->info('Teacher: robert.johnson@teacher.school.com / password');
        $this->command->info('Accountant: accountant@demo.com / password');
    }
}
