<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome-new');
});

// Dashboard Routes with Role-based Access
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:Super Admin,Admin')
        ->name('admin.dashboard');
    
    // Teacher Dashboard
    Route::get('/teacher/dashboard', [DashboardController::class, 'teacherDashboard'])
        ->middleware('role:Teacher')
        ->name('teacher.dashboard');
    
    // Student Dashboard
    Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])
        ->middleware('role:Student')
        ->name('student.dashboard');
    
    // Parent Dashboard
    Route::get('/parent/dashboard', [DashboardController::class, 'parentDashboard'])
        ->middleware('role:Parent')
        ->name('parent.dashboard');
    
    // Accountant Dashboard
    Route::get('/accountant/dashboard', [DashboardController::class, 'accountantDashboard'])
        ->middleware('role:Accountant')
        ->name('accountant.dashboard');
    
    // Librarian Dashboard
    Route::get('/librarian/dashboard', [DashboardController::class, 'librarianDashboard'])
        ->middleware('role:Librarian')
        ->name('librarian.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student Management Routes
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdmissionController;

Route::middleware(['auth', 'role:Super Admin|Admin'])->group(function () {
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/id-card', [StudentController::class, 'idCard'])->name('students.id-card');
    Route::patch('students/{student}/promote', [StudentController::class, 'promote'])->name('students.promote');
    Route::patch('students/{student}/transfer', [StudentController::class, 'transfer'])->name('students.transfer');
    
    // Admission Management
    Route::get('admissions/pending', [AdmissionController::class, 'pending'])->name('admissions.pending');
    Route::post('admissions/{student}/approve', [AdmissionController::class, 'approve'])->name('admissions.approve');
    Route::post('admissions/{student}/reject', [AdmissionController::class, 'reject'])->name('admissions.reject');
});

// Public Admission Form
Route::get('admissions/apply', [AdmissionController::class, 'create'])->name('admissions.apply');
Route::get('admissions/create', [AdmissionController::class, 'create'])->name('admissions.create');
Route::post('admissions/apply', [AdmissionController::class, 'store'])->name('admissions.store');
Route::get('admissions/success', [AdmissionController::class, 'success'])->name('admissions.success');

require __DIR__.'/auth.php';
