<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // User Management
            'user-list', 'user-create', 'user-edit', 'user-delete',
            
            // Student Management
            'student-list', 'student-create', 'student-edit', 'student-delete',
            
            // Teacher Management
            'teacher-list', 'teacher-create', 'teacher-edit', 'teacher-delete',
            
            // Class Management
            'class-list', 'class-create', 'class-edit', 'class-delete',
            
            // Subject Management
            'subject-list', 'subject-create', 'subject-edit', 'subject-delete',
            
            // Attendance Management
            'attendance-list', 'attendance-create', 'attendance-edit', 'attendance-delete',
            
            // Exam Management
            'exam-list', 'exam-create', 'exam-edit', 'exam-delete',
            
            // Fee Management
            'fee-list', 'fee-create', 'fee-edit', 'fee-delete',
            
            // Library Management
            'library-list', 'library-create', 'library-edit', 'library-delete',
            
            // Report Management
            'report-list', 'report-generate',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        
        // Admin - Full Access
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        // Teacher Role
        $teacher = Role::create(['name' => 'Teacher']);
        $teacher->givePermissionTo([
            'student-list', 
            'attendance-list', 'attendance-create', 'attendance-edit',
            'exam-list', 'exam-create', 'exam-edit',
            'class-list', 'subject-list',
            'report-list', 'report-generate',
        ]);

        // Student Role
        $student = Role::create(['name' => 'Student']);
        $student->givePermissionTo([
            'attendance-list',
            'exam-list',
            'class-list',
            'subject-list',
            'report-list',
            'library-list',
        ]);

        // Parent Role
        $parent = Role::create(['name' => 'Parent']);
        $parent->givePermissionTo([
            'student-list',
            'attendance-list',
            'exam-list',
            'report-list',
            'fee-list',
        ]);

        // Accountant Role
        $accountant = Role::create(['name' => 'Accountant']);
        $accountant->givePermissionTo([
            'fee-list', 'fee-create', 'fee-edit', 'fee-delete',
            'report-list', 'report-generate',
            'student-list',
        ]);

        // Librarian Role
        $librarian = Role::create(['name' => 'Librarian']);
        $librarian->givePermissionTo([
            'library-list', 'library-create', 'library-edit', 'library-delete',
            'student-list',
            'report-list',
        ]);

        // Create Main Admin User
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'status' => 'active',
        ]);
        $adminUser->assignRole($admin);

        // Create Demo Users for Each Role

        // Demo Teacher
        $teacherUser = User::create([
            'name' => 'John Teacher',
            'email' => 'teacher@demo.com',
            'password' => Hash::make('password'),
            'phone' => '1234567892',
            'status' => 'active',
        ]);
        $teacherUser->assignRole($teacher);

        // Demo Student
        $studentUser = User::create([
            'name' => 'Jane Student',
            'email' => 'student@demo.com',
            'password' => Hash::make('password'),
            'phone' => '1234567893',
            'status' => 'active',
        ]);
        $studentUser->assignRole($student);

        // Demo Parent
        $parentUser = User::create([
            'name' => 'Parent User',
            'email' => 'parent@demo.com',
            'password' => Hash::make('password'),
            'phone' => '1234567894',
            'status' => 'active',
        ]);
        $parentUser->assignRole($parent);

        // Demo Accountant
        $accountantUser = User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@demo.com',
            'password' => Hash::make('password'),
            'phone' => '1234567895',
            'status' => 'active',
        ]);
        $accountantUser->assignRole($accountant);

        // Demo Librarian
        $librarianUser = User::create([
            'name' => 'Librarian User',
            'email' => 'librarian@demo.com',
            'password' => Hash::make('password'),
            'phone' => '1234567896',
            'status' => 'active',
        ]);
        $librarianUser->assignRole($librarian);

        $this->command->info('Roles, Permissions, and Demo Users created successfully!');
    }
}
