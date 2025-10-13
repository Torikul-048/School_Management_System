<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\FeeCollection;
use App\Models\Expense;
use App\Models\Exam;
use App\Models\BookIssue;
use App\Models\Event;
use App\Services\ChartService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    protected $chartService;

    public function __construct(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function index()
    {
        $data = [
            'enrollmentTrend' => $this->chartService->getStudentEnrollmentTrend(),
            'attendanceTrend' => $this->chartService->getAttendanceTrend(30),
            'feeCollection' => $this->chartService->getMonthlyFeeCollection(),
            'expenseBreakdown' => $this->chartService->getExpenseBreakdown(),
            'incomeVsExpense' => $this->chartService->getIncomeVsExpense(12),
            'classDistribution' => $this->chartService->getClassDistribution(),
            'genderDistribution' => $this->chartService->getGenderDistribution(),
        ];

        return view('analytics.index', compact('data'));
    }

    public function studentAnalytics()
    {
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('status', 'active')->count(),
            'inactive' => Student::where('status', 'inactive')->count(),
            'by_class' => $this->chartService->getClassDistribution(),
            'by_gender' => $this->chartService->getGenderDistribution(),
            'enrollment_trend' => $this->chartService->getStudentEnrollmentTrend(),
            'attendance_rate' => $this->getStudentAttendanceRate(),
        ];

        return view('analytics.students', compact('stats'));
    }

    public function attendanceAnalytics(Request $request)
    {
        $days = $request->input('days', 30);
        
        $stats = [
            'trend' => $this->chartService->getAttendanceTrend($days),
            'overall' => $this->getOverallAttendanceStats($days),
            'by_class' => $this->getAttendanceByClass($days),
            'today' => $this->getTodayAttendance(),
        ];

        return view('analytics.attendance', compact('stats', 'days'));
    }

    public function financialAnalytics(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $stats = [
            'fee_collection' => $this->chartService->getMonthlyFeeCollection(),
            'expense_breakdown' => $this->chartService->getExpenseBreakdown($startDate, $endDate),
            'income_vs_expense' => $this->chartService->getIncomeVsExpense(12),
            'summary' => $this->getFinancialSummary($startDate, $endDate),
            'fee_defaulters' => $this->getFeeDefaulters(),
        ];

        return view('analytics.financial', compact('stats', 'startDate', 'endDate'));
    }

    public function teacherAnalytics()
    {
        $stats = [
            'total' => Teacher::count(),
            'active' => Teacher::where('status', 'active')->count(),
            'workload' => $this->chartService->getTeacherWorkload(),
            'by_department' => $this->getTeachersByDepartment(),
            'attendance_rate' => $this->getTeacherAttendanceRate(),
        ];

        return view('analytics.teachers', compact('stats'));
    }

    public function performanceAnalytics(Request $request)
    {
        $examId = $request->input('exam_id');
        
        $stats = [
            'by_class' => $this->chartService->getStudentPerformanceByClass($examId),
            'by_subject' => $this->getPerformanceBySubject($examId),
            'top_performers' => $this->getTopPerformers($examId, 10),
            'grade_distribution' => $this->getGradeDistribution($examId),
        ];

        $exams = Exam::orderBy('start_date', 'desc')->get();

        return view('analytics.performance', compact('stats', 'exams', 'examId'));
    }

    public function libraryAnalytics()
    {
        $stats = [
            'circulation' => $this->chartService->getLibraryCirculation(6),
            'popular_books' => $this->getPopularBooks(10),
            'overdue_stats' => $this->getOverdueStats(),
            'fine_collection' => $this->getFineCollection(),
        ];

        return view('analytics.library', compact('stats'));
    }

    public function chartData(Request $request)
    {
        $chartType = $request->input('type');
        
        $data = match($chartType) {
            'enrollment' => $this->chartService->getStudentEnrollmentTrend(),
            'attendance' => $this->chartService->getAttendanceTrend($request->input('days', 30)),
            'fees' => $this->chartService->getMonthlyFeeCollection(),
            'expenses' => $this->chartService->getExpenseBreakdown(),
            'income_expense' => $this->chartService->getIncomeVsExpense(12),
            'class_distribution' => $this->chartService->getClassDistribution(),
            'gender' => $this->chartService->getGenderDistribution(),
            'performance' => $this->chartService->getStudentPerformanceByClass($request->input('exam_id')),
            'teacher_workload' => $this->chartService->getTeacherWorkload(),
            'library' => $this->chartService->getLibraryCirculation(6),
            default => ['error' => 'Invalid chart type'],
        };

        return response()->json($data);
    }

    // Helper methods

    private function getStudentAttendanceRate()
    {
        $total = Attendance::where('attendable_type', 'App\\Models\\Student')
            ->where('date', '>=', now()->subDays(30))
            ->count();

        $present = Attendance::where('attendable_type', 'App\\Models\\Student')
            ->where('date', '>=', now()->subDays(30))
            ->where('status', 'present')
            ->count();

        return $total > 0 ? round(($present / $total) * 100, 2) : 0;
    }

    private function getOverallAttendanceStats($days)
    {
        $startDate = now()->subDays($days);

        return [
            'total' => Attendance::where('date', '>=', $startDate)->count(),
            'present' => Attendance::where('date', '>=', $startDate)->where('status', 'present')->count(),
            'absent' => Attendance::where('date', '>=', $startDate)->where('status', 'absent')->count(),
            'late' => Attendance::where('date', '>=', $startDate)->where('status', 'late')->count(),
        ];
    }

    private function getAttendanceByClass($days)
    {
        $startDate = now()->subDays($days);

        return DB::table('attendances')
            ->join('students', 'attendances.attendable_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->where('attendances.attendable_type', 'App\\Models\\Student')
            ->where('attendances.date', '>=', $startDate)
            ->selectRaw('classes.name, 
                         SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) as present,
                         COUNT(*) as total')
            ->groupBy('classes.id', 'classes.name')
            ->get()
            ->map(function ($item) {
                $item->rate = $item->total > 0 ? round(($item->present / $item->total) * 100, 2) : 0;
                return $item;
            });
    }

    private function getTodayAttendance()
    {
        return [
            'total' => Attendance::whereDate('date', today())->count(),
            'present' => Attendance::whereDate('date', today())->where('status', 'present')->count(),
            'absent' => Attendance::whereDate('date', today())->where('status', 'absent')->count(),
            'late' => Attendance::whereDate('date', today())->where('status', 'late')->count(),
        ];
    }

    private function getFinancialSummary($startDate, $endDate)
    {
        $income = FeeCollection::whereBetween('payment_date', [$startDate, $endDate])
            ->sum('paid_amount');

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->sum('amount');

        return [
            'income' => $income,
            'expenses' => $expenses,
            'net' => $income - $expenses,
            'pending_fees' => $this->getPendingFees(),
        ];
    }

    private function getPendingFees()
    {
        return DB::table('fee_invoices')
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->sum('total_amount');
    }

    private function getFeeDefaulters()
    {
        return DB::table('fee_invoices')
            ->join('students', 'fee_invoices.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->leftJoin(DB::raw('(SELECT fee_invoice_id, SUM(amount_paid) as total_paid FROM fee_payments GROUP BY fee_invoice_id) as payments'), 
                       'fee_invoices.id', '=', 'payments.fee_invoice_id')
            ->whereIn('fee_invoices.status', ['pending', 'partial', 'overdue'])
            ->where('fee_invoices.due_date', '<', now())
            ->selectRaw('CONCAT(students.first_name, " ", students.last_name) as student_name,
                         classes.name as class_name,
                         fee_invoices.total_amount - COALESCE(payments.total_paid, 0) as pending')
            ->orderByDesc('pending')
            ->limit(10)
            ->get();
    }

    private function getTeachersByDepartment()
    {
        return Teacher::selectRaw('department, COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('department')
            ->get();
    }

    private function getTeacherAttendanceRate()
    {
        $total = Attendance::where('attendable_type', 'App\\Models\\Teacher')
            ->where('date', '>=', now()->subDays(30))
            ->count();

        $present = Attendance::where('attendable_type', 'App\\Models\\Teacher')
            ->where('date', '>=', now()->subDays(30))
            ->where('status', 'present')
            ->count();

        return $total > 0 ? round(($present / $total) * 100, 2) : 0;
    }

    private function getPerformanceBySubject($examId)
    {
        $query = DB::table('marks')
            ->join('subjects', 'marks.subject_id', '=', 'subjects.id')
            ->selectRaw('subjects.name as subject_name, AVG(marks.marks_obtained) as avg_marks')
            ->groupBy('subjects.id', 'subjects.name')
            ->orderBy('subjects.name');

        if ($examId) {
            $query->where('marks.exam_id', $examId);
        }

        return $query->get();
    }

    private function getTopPerformers($examId, $limit = 10)
    {
        $query = DB::table('marks')
            ->join('students', 'marks.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->selectRaw('CONCAT(students.first_name, " ", students.last_name) as student_name,
                         classes.name as class_name,
                         AVG(marks.marks_obtained) as avg_marks')
            ->groupBy('students.id', 'student_name', 'classes.name')
            ->orderByDesc('avg_marks')
            ->limit($limit);

        if ($examId) {
            $query->where('marks.exam_id', $examId);
        }

        return $query->get();
    }

    private function getGradeDistribution($examId)
    {
        $query = DB::table('marks')
            ->selectRaw('grade, COUNT(*) as count')
            ->groupBy('grade')
            ->orderBy('grade');

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        return $query->get();
    }

    private function getPopularBooks($limit = 10)
    {
        return DB::table('book_issues')
            ->join('books', 'book_issues.book_id', '=', 'books.id')
            ->selectRaw('books.title, COUNT(*) as issue_count')
            ->groupBy('books.id', 'books.title')
            ->orderByDesc('issue_count')
            ->limit($limit)
            ->get();
    }

    private function getOverdueStats()
    {
        return [
            'total' => BookIssue::where('status', 'issued')
                ->where('due_date', '<', now())
                ->count(),
            'pending_fines' => BookIssue::where('fine_paid', false)
                ->where('fine_amount', '>', 0)
                ->sum('fine_amount'),
        ];
    }

    private function getFineCollection()
    {
        return BookIssue::selectRaw('DATE_FORMAT(return_date, "%Y-%m") as month, 
                                     SUM(fine_amount) as total')
            ->where('fine_paid', true)
            ->where('return_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
