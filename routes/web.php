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
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TimetableController;

// Phase 5: Attendance & Examination System
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MarksController;

Route::middleware(['auth', 'role:Super Admin|Admin'])->group(function () {
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/id-card', [StudentController::class, 'idCard'])->name('students.id-card');
    Route::patch('students/{student}/promote', [StudentController::class, 'promote'])->name('students.promote');
    Route::patch('students/{student}/transfer', [StudentController::class, 'transfer'])->name('students.transfer');
    
    // Admission Management
    Route::get('admissions/pending', [AdmissionController::class, 'pending'])->name('admissions.pending');
    Route::post('admissions/{student}/approve', [AdmissionController::class, 'approve'])->name('admissions.approve');
    Route::post('admissions/{student}/reject', [AdmissionController::class, 'reject'])->name('admissions.reject');
    
    // Academic Management - Phase 4
    // Class Management
    Route::resource('classes', ClassController::class);
    Route::get('classes/{class}/sections', [ClassController::class, 'manageSections'])->name('classes.manage-sections');
    Route::post('classes/{class}/sections', [ClassController::class, 'storeSection'])->name('classes.store-section');
    Route::patch('sections/{section}', [ClassController::class, 'updateSection'])->name('sections.update');
    Route::delete('sections/{section}', [ClassController::class, 'destroySection'])->name('sections.destroy');
    Route::post('classes/{class}/assign-subjects', [ClassController::class, 'assignSubjects'])->name('classes.assign-subjects');
    
    // Subject Management
    Route::resource('subjects', SubjectController::class);
    Route::post('subjects/{subject}/assign-classes', [SubjectController::class, 'assignToClasses'])->name('subjects.assign-classes');
    
    // Timetable Management
    Route::get('timetable', [TimetableController::class, 'index'])->name('timetable.index');
    Route::get('timetable/create', [TimetableController::class, 'create'])->name('timetable.create');
    Route::post('timetable', [TimetableController::class, 'store'])->name('timetable.store');
    Route::get('timetable/auto-generate', [TimetableController::class, 'showAutoGenerateForm'])->name('timetable.auto-generate');
    Route::post('timetable/auto-generate', [TimetableController::class, 'autoGenerate'])->name('timetable.auto-generate.store');
    Route::get('timetable/{timetable}/edit', [TimetableController::class, 'edit'])->name('timetable.edit');
    Route::patch('timetable/{timetable}', [TimetableController::class, 'update'])->name('timetable.update');
    Route::delete('timetable/{timetable}', [TimetableController::class, 'destroy'])->name('timetable.destroy');
    Route::get('timetable/teacher/{teacher}', [TimetableController::class, 'teacherTimetable'])->name('timetable.teacher');
    
    // Attendance Management
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('attendance/reports', [AttendanceController::class, 'reports'])->name('attendance.reports');
    Route::get('attendance/reports/daily', [AttendanceController::class, 'reports'])->name('attendance.reports.daily');
    Route::get('attendance/reports/monthly', [AttendanceController::class, 'reports'])->name('attendance.reports.monthly');
    Route::get('attendance/reports/yearly', [AttendanceController::class, 'reports'])->name('attendance.reports.yearly');
    Route::get('attendance/reports/student', [AttendanceController::class, 'reports'])->name('attendance.reports.student');
    Route::get('attendance/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::patch('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendance', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my-attendance');
    
    // Leave Requests
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    Route::post('leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
    
    // Examination Management
    Route::resource('exams', ExamController::class);
    Route::post('exams/{exam}/schedule', [ExamController::class, 'addSchedule'])->name('exams.add-schedule');
    Route::patch('exam-schedules/{schedule}', [ExamController::class, 'updateSchedule'])->name('exams.update-schedule');
    Route::delete('exam-schedules/{schedule}', [ExamController::class, 'deleteSchedule'])->name('exams.delete-schedule');
    Route::post('exams/{exam}/publish', [ExamController::class, 'publishResults'])->name('exams.publish');
    Route::post('exams/{exam}/unpublish', [ExamController::class, 'unpublishResults'])->name('exams.unpublish');
    
    // Marks Management
    Route::get('marks', [MarksController::class, 'index'])->name('marks.index');
    Route::post('marks', [MarksController::class, 'store'])->name('marks.store');
    Route::get('marks/{mark}', [MarksController::class, 'show'])->name('marks.show');
    Route::patch('marks/{mark}', [MarksController::class, 'update'])->name('marks.update');
    Route::delete('marks/{mark}', [MarksController::class, 'destroy'])->name('marks.destroy');
    Route::get('report-card', [MarksController::class, 'reportCard'])->name('marks.report-card');
    Route::get('report-card/{exam}/{student}/download', [MarksController::class, 'downloadReportCard'])->name('marks.download-report-card');
    Route::get('progress-tracking', [MarksController::class, 'progressTracking'])->name('marks.progress-tracking');
});

// Public Admission Form
Route::get('admissions/apply', [AdmissionController::class, 'create'])->name('admissions.apply');
Route::get('admissions/create', [AdmissionController::class, 'create'])->name('admissions.create');
Route::post('admissions/apply', [AdmissionController::class, 'store'])->name('admissions.store');
Route::get('admissions/success', [AdmissionController::class, 'success'])->name('admissions.success');

require __DIR__.'/auth.php';
