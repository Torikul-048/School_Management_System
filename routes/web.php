<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

require __DIR__.'/auth.php';
