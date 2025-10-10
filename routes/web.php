<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\FeeCollectionController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\FinanceReportController;
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

// Phase 6: Faculty & Staff Management
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TeacherLeaveController;

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
    
    // Phase 6: Teacher Management
    Route::resource('teachers', TeacherController::class);
    Route::get('teachers/{teacher}/attendance', [TeacherController::class, 'attendance'])->name('teachers.attendance');
    Route::get('teachers/{teacher}/workload', [TeacherController::class, 'workload'])->name('teachers.workload');
    Route::get('teachers/{teacher}/performance', [TeacherController::class, 'performance'])->name('teachers.performance');
    Route::get('teachers/{teacher}/id-card', [TeacherController::class, 'idCard'])->name('teachers.id-card');
    
    // Payroll Management
    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
    Route::post('payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::patch('payroll/{payroll}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::delete('payroll/{payroll}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
    Route::get('payroll/salary-structure', [PayrollController::class, 'salaryStructure'])->name('payroll.salary-structure');
    Route::post('payroll/salary-structure', [PayrollController::class, 'storeSalaryComponent'])->name('payroll.salary-structure.store');
    Route::delete('payroll/salary-structure/{component}', [PayrollController::class, 'destroySalaryComponent'])->name('payroll.salary-structure.destroy');
    Route::get('payroll/{payroll}/salary-slip', [PayrollController::class, 'salarySlip'])->name('payroll.salary-slip');
    Route::get('payroll/{payroll}/salary-slip/pdf', [PayrollController::class, 'salarySlipPdf'])->name('payroll.salary-slip-pdf');
    Route::get('payroll/reports', [PayrollController::class, 'reports'])->name('payroll.reports');
    Route::get('payroll/{teacher}/history', [PayrollController::class, 'salaryHistory'])->name('payroll.salary-history');
    
    // Teacher Leave Management
    Route::get('teacher-leaves', [TeacherLeaveController::class, 'index'])->name('teacher-leaves.index');
    Route::get('teacher-leaves/create', [TeacherLeaveController::class, 'create'])->name('teacher-leaves.create');
    Route::post('teacher-leaves', [TeacherLeaveController::class, 'store'])->name('teacher-leaves.store');
    Route::get('teacher-leaves/{teacherLeave}', [TeacherLeaveController::class, 'show'])->name('teacher-leaves.show');
    Route::delete('teacher-leaves/{teacherLeave}', [TeacherLeaveController::class, 'destroy'])->name('teacher-leaves.destroy');
    Route::post('teacher-leaves/{teacherLeave}/approve', [TeacherLeaveController::class, 'approve'])->name('teacher-leaves.approve');
    Route::post('teacher-leaves/{teacherLeave}/reject', [TeacherLeaveController::class, 'reject'])->name('teacher-leaves.reject');
    Route::get('teacher-leaves/balance', [TeacherLeaveController::class, 'balance'])->name('teacher-leaves.balance');
    Route::get('teacher-leaves/history', [TeacherLeaveController::class, 'history'])->name('teacher-leaves.history');
    
    // Phase 7: Finance & Accounts Module
    // Fee Structures
    Route::resource('fee-structures', FeeStructureController::class);
    
    // Fee Collections
    Route::get('fee-collections', [FeeCollectionController::class, 'index'])->name('fee-collections.index');
    Route::get('fee-collections/create', [FeeCollectionController::class, 'create'])->name('fee-collections.create');
    Route::post('fee-collections', [FeeCollectionController::class, 'store'])->name('fee-collections.store');
    Route::get('fee-collections/{feeCollection}', [FeeCollectionController::class, 'show'])->name('fee-collections.show');
    Route::delete('fee-collections/{feeCollection}', [FeeCollectionController::class, 'destroy'])->name('fee-collections.destroy');
    Route::get('fee-collections/{id}/receipt', [FeeCollectionController::class, 'receipt'])->name('fee-collections.receipt');
    Route::get('fee-collections/{id}/print', [FeeCollectionController::class, 'printReceipt'])->name('fee-collections.print');
    Route::get('fee-collections/search/results', [FeeCollectionController::class, 'search'])->name('fee-collections.search');
    Route::get('fee-collections/defaulters/list', [FeeCollectionController::class, 'defaulters'])->name('fee-collections.defaulters');
    
    // Scholarships
    Route::resource('scholarships', ScholarshipController::class);
    Route::get('scholarships/assign/student', [ScholarshipController::class, 'assignStudent'])->name('scholarships.assign');
    Route::post('scholarships/assign/student', [ScholarshipController::class, 'storeAssignment'])->name('scholarships.assign.store');
    Route::post('scholarships/revoke/{id}', [ScholarshipController::class, 'revokeAssignment'])->name('scholarships.revoke');
    
    // Expenses
    Route::resource('expenses', ExpenseController::class);
    Route::post('expenses/{id}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{id}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    Route::get('expenses/by-category/view', [ExpenseController::class, 'byCategory'])->name('expenses.by-category');
    
    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{id}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('invoices/{id}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::get('invoices/{id}/print', [InvoiceController::class, 'printInvoice'])->name('invoices.print');
    
    // Finance Reports
    Route::get('finance/reports', [FinanceReportController::class, 'index'])->name('finance.reports.index');
    Route::get('finance/reports/income', [FinanceReportController::class, 'income'])->name('finance.reports.income');
    Route::get('finance/reports/expenses', [FinanceReportController::class, 'expenses'])->name('finance.reports.expenses');
    Route::get('finance/reports/balance', [FinanceReportController::class, 'balance'])->name('finance.reports.balance');
    Route::get('finance/reports/student-ledger', [FinanceReportController::class, 'studentLedgerList'])->name('finance.reports.student-ledger');
    Route::get('finance/reports/student-ledger/{student}', [FinanceReportController::class, 'studentLedger'])->name('finance.reports.student-ledger.show');
    Route::get('finance/reports/daily-collection', [FinanceReportController::class, 'dailyCollection'])->name('finance.reports.daily-collection');
    Route::get('finance/reports/income/pdf', [FinanceReportController::class, 'downloadIncomePdf'])->name('finance.reports.income.pdf');
    Route::get('finance/reports/expenses/pdf', [FinanceReportController::class, 'downloadExpensePdf'])->name('finance.reports.expenses.pdf');
    
    // Phase 8: Library Management Module
    // Library Dashboard
    Route::get('library/dashboard', [App\Http\Controllers\LibraryController::class, 'dashboard'])->name('library.dashboard');
    Route::get('library/statistics', [App\Http\Controllers\LibraryController::class, 'statistics'])->name('library.statistics');
    Route::get('library/reports', [App\Http\Controllers\LibraryController::class, 'reports'])->name('library.reports');
    
    // Library Settings
    Route::get('library/settings', [App\Http\Controllers\LibraryController::class, 'settings'])->name('library.settings');
    Route::patch('library/settings', [App\Http\Controllers\LibraryController::class, 'updateSettings'])->name('library.settings.update');
    
    // Book Categories
    Route::get('library/categories', [App\Http\Controllers\LibraryController::class, 'categories'])->name('library.categories');
    Route::get('library/categories/create', [App\Http\Controllers\LibraryController::class, 'createCategory'])->name('library.categories.create');
    Route::post('library/categories', [App\Http\Controllers\LibraryController::class, 'storeCategory'])->name('library.categories.store');
    Route::get('library/categories/{category}/edit', [App\Http\Controllers\LibraryController::class, 'editCategory'])->name('library.categories.edit');
    Route::patch('library/categories/{category}', [App\Http\Controllers\LibraryController::class, 'updateCategory'])->name('library.categories.update');
    Route::delete('library/categories/{category}', [App\Http\Controllers\LibraryController::class, 'destroyCategory'])->name('library.categories.destroy');
    
    // Books Management
    Route::resource('books', App\Http\Controllers\BookController::class);
    Route::get('books/search/results', [App\Http\Controllers\BookController::class, 'search'])->name('books.search');
    Route::get('books/inventory/list', [App\Http\Controllers\BookController::class, 'inventory'])->name('books.inventory');
    Route::get('books/digital-library/list', [App\Http\Controllers\BookController::class, 'digitalLibrary'])->name('books.digital-library');
    Route::get('books/{book}/download-pdf', [App\Http\Controllers\BookController::class, 'downloadPdf'])->name('books.download-pdf');
    Route::post('books/scan-barcode', [App\Http\Controllers\BookController::class, 'scanBarcode'])->name('books.scan-barcode');
    
    // Book Issues Management
    Route::resource('book-issues', App\Http\Controllers\BookIssueController::class);
    Route::post('book-issues/{bookIssue}/return', [App\Http\Controllers\BookIssueController::class, 'returnBook'])->name('book-issues.return');
    Route::post('book-issues/{bookIssue}/pay-fine', [App\Http\Controllers\BookIssueController::class, 'payFine'])->name('book-issues.pay-fine');
    Route::get('book-issues/overdue/list', [App\Http\Controllers\BookIssueController::class, 'overdue'])->name('book-issues.overdue');
    Route::get('book-issues/my-books/list', [App\Http\Controllers\BookIssueController::class, 'myBooks'])->name('book-issues.my-books');
    Route::post('book-issues/{bookIssue}/renew', [App\Http\Controllers\BookIssueController::class, 'renewBook'])->name('book-issues.renew');
    Route::get('book-issues/history/list', [App\Http\Controllers\BookIssueController::class, 'history'])->name('book-issues.history');
});

// Public Admission Form
Route::get('admissions/apply', [AdmissionController::class, 'create'])->name('admissions.apply');
Route::get('admissions/create', [AdmissionController::class, 'create'])->name('admissions.create');
Route::post('admissions/apply', [AdmissionController::class, 'store'])->name('admissions.store');
Route::get('admissions/success', [AdmissionController::class, 'success'])->name('admissions.success');

require __DIR__.'/auth.php';
