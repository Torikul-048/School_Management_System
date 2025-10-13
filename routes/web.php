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
    
    // Phase 9: Communication System
    // Messages
    Route::resource('messages', App\Http\Controllers\MessageController::class);
    Route::get('messages/compose/new', [App\Http\Controllers\MessageController::class, 'compose'])->name('messages.compose');
    Route::post('messages/{message}/reply', [App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');
    Route::post('messages/{message}/archive', [App\Http\Controllers\MessageController::class, 'archive'])->name('messages.archive');
    Route::post('messages/mark-all-read', [App\Http\Controllers\MessageController::class, 'markAllAsRead'])->name('messages.mark-all-read');
    
    // Events
    Route::resource('events', App\Http\Controllers\EventController::class);
    Route::get('events-calendar', [App\Http\Controllers\EventController::class, 'calendar'])->name('events.calendar');
    Route::get('events/upcoming/list', [App\Http\Controllers\EventController::class, 'upcoming'])->name('events.upcoming');
    
    // Notices
    Route::resource('notices', App\Http\Controllers\NoticeController::class);
    Route::post('notices/{notice}/pin', [App\Http\Controllers\NoticeController::class, 'pin'])->name('notices.pin');
    Route::post('notices/{notice}/unpin', [App\Http\Controllers\NoticeController::class, 'unpin'])->name('notices.unpin');
    Route::post('notices/{notice}/archive', [App\Http\Controllers\NoticeController::class, 'archive'])->name('notices.archive');
    
    // Announcements
    Route::resource('announcements', App\Http\Controllers\AnnouncementController::class);
    Route::post('announcements/{announcement}/pin', [App\Http\Controllers\AnnouncementController::class, 'pin'])->name('announcements.pin');
    Route::post('announcements/{announcement}/unpin', [App\Http\Controllers\AnnouncementController::class, 'unpin'])->name('announcements.unpin');
    
    // Complaints
    Route::resource('complaints', App\Http\Controllers\ComplaintController::class);
    Route::get('my-complaints', [App\Http\Controllers\ComplaintController::class, 'myComplaints'])->name('complaints.my');
    Route::post('complaints/{complaint}/assign', [App\Http\Controllers\ComplaintController::class, 'assign'])->name('complaints.assign');
    Route::post('complaints/{complaint}/update-status', [App\Http\Controllers\ComplaintController::class, 'updateStatus'])->name('complaints.update-status');
    Route::post('complaints/{complaint}/resolve', [App\Http\Controllers\ComplaintController::class, 'resolve'])->name('complaints.resolve');
    Route::post('complaints/{complaint}/feedback', [App\Http\Controllers\ComplaintController::class, 'submitFeedback'])->name('complaints.feedback');
    Route::get('complaints-statistics', [App\Http\Controllers\ComplaintController::class, 'statistics'])->name('complaints.statistics');
    
    // Notifications
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('notification-settings', [App\Http\Controllers\NotificationController::class, 'settings'])->name('notifications.settings');
    Route::patch('notification-settings', [App\Http\Controllers\NotificationController::class, 'updateSettings'])->name('notifications.update-settings');
    Route::get('notifications/unread', [App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
});

// Analytics & Reports Routes
Route::middleware(['auth'])->group(function () {
    // Analytics Dashboard
    Route::get('analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/students', [App\Http\Controllers\AnalyticsController::class, 'studentAnalytics'])->name('analytics.students');
    Route::get('analytics/attendance', [App\Http\Controllers\AnalyticsController::class, 'attendanceAnalytics'])->name('analytics.attendance');
    Route::get('analytics/financial', [App\Http\Controllers\AnalyticsController::class, 'financialAnalytics'])->name('analytics.financial');
    Route::get('analytics/teachers', [App\Http\Controllers\AnalyticsController::class, 'teacherAnalytics'])->name('analytics.teachers');
    Route::get('analytics/performance', [App\Http\Controllers\AnalyticsController::class, 'performanceAnalytics'])->name('analytics.performance');
    Route::get('analytics/library', [App\Http\Controllers\AnalyticsController::class, 'libraryAnalytics'])->name('analytics.library');
    
    // Chart API Endpoints
    Route::get('analytics/chart-data', [App\Http\Controllers\AnalyticsController::class, 'chartData'])->name('analytics.chart-data');
    
    // Dashboard API Endpoints
    Route::get('dashboard/key-metrics', [DashboardController::class, 'getKeyMetrics'])->name('dashboard.key-metrics');
    Route::get('dashboard/today-attendance', [DashboardController::class, 'getTodayAttendance'])->name('dashboard.today-attendance');
    Route::get('dashboard/fee-status', [DashboardController::class, 'getFeeCollectionStatus'])->name('dashboard.fee-status');
    Route::get('dashboard/upcoming-exams', [DashboardController::class, 'getUpcomingExams'])->name('dashboard.upcoming-exams');
    Route::get('dashboard/upcoming-events', [DashboardController::class, 'getUpcomingEvents'])->name('dashboard.upcoming-events');
    Route::get('dashboard/recent-activities', [DashboardController::class, 'getRecentActivities'])->name('dashboard.recent-activities');
    
    // Reports
    Route::get('reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/templates', [App\Http\Controllers\ReportController::class, 'templates'])->name('reports.templates');
    Route::post('reports/generate', [App\Http\Controllers\ReportController::class, 'generate'])->name('reports.generate');
    Route::get('reports/my-reports', [App\Http\Controllers\ReportController::class, 'myReports'])->name('reports.my-reports');
    Route::get('reports/download/{id}', [App\Http\Controllers\ReportController::class, 'download'])->name('reports.download');
    Route::delete('reports/{id}', [App\Http\Controllers\ReportController::class, 'destroy'])->name('reports.destroy');
    
    // Specific Report Types
    Route::get('reports/students', [App\Http\Controllers\ReportController::class, 'studentReport'])->name('reports.students');
    Route::get('reports/attendance', [App\Http\Controllers\ReportController::class, 'attendanceReport'])->name('reports.attendance');
    Route::get('reports/fees', [App\Http\Controllers\ReportController::class, 'feeReport'])->name('reports.fees');
    Route::get('reports/exams', [App\Http\Controllers\ReportController::class, 'examReport'])->name('reports.exams');
    Route::get('reports/teachers', [App\Http\Controllers\ReportController::class, 'teacherReport'])->name('reports.teachers');
    Route::get('reports/financial', [App\Http\Controllers\ReportController::class, 'financialReport'])->name('reports.financial');
});

// Public Admission Form
Route::get('admissions/apply', [AdmissionController::class, 'create'])->name('admissions.apply');
Route::get('admissions/create', [AdmissionController::class, 'create'])->name('admissions.create');
Route::post('admissions/apply', [AdmissionController::class, 'store'])->name('admissions.store');
Route::get('admissions/success', [AdmissionController::class, 'success'])->name('admissions.success');

require __DIR__.'/auth.php';
