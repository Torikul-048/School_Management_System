<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\FeeStructure;
use App\Models\FeeCollection;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SimpleAccountantDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Creating Simple Test Data for Accountant Module...');

        // 1. Create Payment Methods
        $this->command->info('Creating Payment Methods...');
        $paymentMethods = [
            ['name' => 'Cash', 'code' => 'cash', 'description' => 'Cash payment', 'status' => 'active'],
            ['name' => 'Cheque', 'code' => 'cheque', 'description' => 'Cheque payment', 'status' => 'active'],
            ['name' => 'Bank Transfer', 'code' => 'bank', 'description' => 'Bank transfer', 'status' => 'active'],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate(['code' => $method['code']], $method);
        }

        $cash = PaymentMethod::where('code', 'cash')->first();
        $bank = PaymentMethod::where('code', 'bank')->first();

        // 2. Create Academic Year
        $this->command->info('Creating Academic Year...');
        $academicYear = AcademicYear::firstOrCreate(
            ['is_current' => true],
            [
                'name' => '2025-2026',
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'is_current' => true,
            ]
        );

        // 3. Create Classes
        $this->command->info('Creating Classes...');
        $classes = [];
        for ($i = 1; $i <= 5; $i++) {
            $class = Classes::firstOrCreate(
                ['name' => "Class $i", 'academic_year_id' => $academicYear->id],
                [
                    'name' => "Class $i",
                    'numeric_name' => (string)$i,
                    'academic_year_id' => $academicYear->id,
                    'capacity' => 40,
                    'status' => 'active',
                ]
            );
            $classes[] = $class;
            
            // Create Section for each class
            Section::firstOrCreate(
                ['class_id' => $class->id, 'name' => 'Section A'],
                [
                    'class_id' => $class->id,
                    'name' => 'Section A',
                    'room_number' => "Room {$i}A",
                    'capacity' => 40,
                    'status' => 'active',
                ]
            );
        }

        // 4. Create 10 Students
        $this->command->info('Creating Students...');
        $studentNames = [
            ['John', 'Doe'], ['Jane', 'Smith'], ['Mike', 'Johnson'], ['Sarah', 'Williams'],
            ['Tom', 'Brown'], ['Lisa', 'Davis'], ['David', 'Miller'], ['Emily', 'Wilson'],
            ['Chris', 'Moore'], ['Anna', 'Taylor'],
        ];

        $students = [];
        foreach ($studentNames as $index => $name) {
            $class = $classes[array_rand($classes)];
            $section = Section::where('class_id', $class->id)->first();
            
            $user = User::firstOrCreate(
                ['email' => strtolower($name[0] . '.' . $name[1]) . '@student.test'],
                [
                    'name' => $name[0] . ' ' . $name[1],
                    'email' => strtolower($name[0] . '.' . $name[1]) . '@student.test',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole('Student')) {
                $user->assignRole('Student');
            }

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'admission_number' => 'STU' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                    'admission_date' => Carbon::now()->subMonths(rand(1, 12)),
                    'class_id' => $class->id,
                    'section_id' => $section->id,
                    'academic_year_id' => $academicYear->id,
                    'roll_number' => $index + 1,
                    'date_of_birth' => Carbon::now()->subYears(rand(10, 16)),
                    'gender' => $index % 2 == 0 ? 'male' : 'female',
                    'father_name' => $name[1] . ' Sr.',
                    'mother_name' => 'Mrs. ' . $name[1],
                    'status' => 'active',
                ]
            );

            $students[] = $student;
        }

        // 5. Create 3 Teachers
        $this->command->info('Creating Teachers...');
        $teacherNames = [['Robert', 'Anderson'], ['Mary', 'Thomas'], ['James', 'Jackson']];
        
        $teachers = [];
        foreach ($teacherNames as $index => $name) {
            $user = User::firstOrCreate(
                ['email' => strtolower($name[0] . '.' . $name[1]) . '@teacher.test'],
                [
                    'name' => $name[0] . ' ' . $name[1],
                    'email' => strtolower($name[0] . '.' . $name[1]) . '@teacher.test',
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
                    'email' => strtolower($name[0] . '.' . $name[1]) . '@teacher.test',
                    'phone' => '+1-555-' . rand(1000, 9999),
                    'joining_date' => Carbon::now()->subMonths(rand(6, 36)),
                    'designation' => 'Senior Teacher',
                    'qualification' => 'M.Ed',
                    'date_of_birth' => Carbon::now()->subYears(rand(30, 50)),
                    'gender' => $index % 2 == 0 ? 'male' : 'female',
                    'salary' => 50000,
                    'employment_type' => 'full-time',
                    'status' => 'active',
                ]
            );

            $teachers[] = $teacher;
        }

        // 6. Create Fee Structures
        $this->command->info('Creating Fee Structures...');
        $feeStructures = [];
        foreach ($classes as $class) {
            $fee = FeeStructure::firstOrCreate(
                ['class_id' => $class->id, 'name' => 'Tuition Fee'],
                [
                    'class_id' => $class->id,
                    'name' => 'Tuition Fee',
                    'description' => 'Monthly tuition fee for ' . $class->name,
                    'amount' => 5000,
                    'fee_type' => 'tuition',
                    'frequency' => 'monthly',
                    'applicable_from' => Carbon::now()->startOfYear(),
                    'applicable_to' => Carbon::now()->endOfYear(),
                    'is_mandatory' => true,
                    'status' => 'active',
                ]
            );
            $feeStructures[] = $fee;
        }

        // 7. Create Fee Collections
        $this->command->info('Creating Fee Collections...');
        $receiptCounter = 1;
        foreach ($students as $student) {
            // Create 2-3 fee collections per student
            $collections = rand(2, 3);
            for ($i = 0; $i < $collections; $i++) {
                $feeStructure = FeeStructure::where('class_id', $student->class_id)->first();
                if ($feeStructure) {
                    $paymentDate = Carbon::now()->subDays(rand(1, 90));
                    
                    FeeCollection::create([
                        'student_id' => $student->id,
                        'fee_structure_id' => $feeStructure->id,
                        'receipt_number' => 'RCP-' . $paymentDate->format('Ymd') . '-' . str_pad($receiptCounter++, 4, '0', STR_PAD_LEFT),
                        'fee_amount' => $feeStructure->amount,
                        'discount_amount' => 0,
                        'paid_amount' => $feeStructure->amount,
                        'payment_method_id' => $i % 2 == 0 ? $cash->id : $bank->id,
                        'payment_date' => $paymentDate,
                        'status' => 'paid',
                        'remarks' => 'Payment received',
                        'collected_by' => 1,
                    ]);
                }
            }
        }

        $this->command->info('✅ Test Data Created Successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('- Academic Years: ' . AcademicYear::count());
        $this->command->info('- Classes: ' . Classes::count());
        $this->command->info('- Sections: ' . Section::count());
        $this->command->info('- Students: ' . Student::count());
        $this->command->info('- Teachers: ' . Teacher::count());
        $this->command->info('- Fee Structures: ' . FeeStructure::count());
        $this->command->info('- Fee Collections: ' . FeeCollection::count());
        $this->command->info('- Payment Methods: ' . PaymentMethod::count());
        $this->command->info('');
        $this->command->info('🔑 Test Credentials:');
        $this->command->info('Student: john.doe@student.test / password');
        $this->command->info('Teacher: robert.anderson@teacher.test / password');
        $this->command->info('Accountant: accountant@demo.com / password');
    }
}
