<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\FeeCollection;
use App\Models\Exam;
use App\Models\Event;
use App\Models\DashboardWidget;
use App\Services\ChartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $chartService;

    public function __construct(ChartService $chartService)
    {
        $this->chartService = $chartService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Redirect to appropriate dashboard based on role
        if ($user->hasRole('Admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('Teacher')) {
            return $this->teacherDashboard();
        } elseif ($user->hasRole('Student')) {
            return redirect()->route('student.dashboard');
        } elseif ($user->hasRole('Parent')) {
            return redirect()->route('parent.dashboard');
        } elseif ($user->hasRole('Accountant')) {
            return $this->accountantDashboard();
        } elseif ($user->hasRole('Librarian')) {
            return $this->librarianDashboard();
        }
        
        // Default fallback
        return view('dashboard');
    }

    public function adminDashboard()
    {
        $data = [
            // Key metrics
            'metrics' => $this->getKeyMetrics(),
            
            // Today's attendance
            'todayAttendance' => $this->getTodayAttendance(),
            
            // Fee collection status
            'feeStatus' => $this->getFeeCollectionStatus(),
            
            // Upcoming exams
            'upcomingExams' => $this->getUpcomingExams(),
            
            // Upcoming events
            'upcomingEvents' => $this->getUpcomingEvents(),
            
            // Recent activities
            'recentActivities' => $this->getRecentActivities(),
            
            // Charts data
            'charts' => [
                'enrollment' => $this->chartService->getStudentEnrollmentTrend(),
                'attendance' => $this->chartService->getAttendanceTrend(30),
                'feeCollection' => $this->chartService->getMonthlyFeeCollection(),
                'incomeExpense' => $this->chartService->getIncomeVsExpense(6),
            ],
        ];

        return view('dashboards.admin', $data);
    }

    public function teacherDashboard()
    {
        $teacher = auth()->user()->teacher;

        $data = [
            'classes' => $teacher ? $teacher->classes()->with('students')->get() : collect(),
            'subjects' => $teacher ? $teacher->subjects : collect(),
            'todayClasses' => $teacher ? $this->getTeacherTodayClasses($teacher->id) : collect(),
            'upcomingExams' => $this->getUpcomingExams(),
            'recentActivities' => $teacher ? $this->getTeacherActivities($teacher->id) : collect(),
        ];

        return view('dashboards.teacher', $data);
    }

    public function studentDashboard()
    {
        $student = auth()->user()->student;

        $data = [
            'attendance' => $student ? $this->getStudentAttendance($student->id) : [],
            'upcomingExams' => $this->getUpcomingExams(),
            'recentResults' => $student ? $this->getStudentRecentResults($student->id) : collect(),
            'feeStatus' => $student ? $this->getStudentFeeStatus($student->id) : [],
            'events' => $this->getUpcomingEvents(),
        ];

        return view('dashboards.student', $data);
    }

    public function parentDashboard()
    {
        // Redirect to the new Parent Portal
        return redirect()->route('parent.dashboard');
    }

    public function accountantDashboard()
    {
        $currentMonth = now();
        
        // Key Metrics
        $metrics = [
            'total_collection_today' => \App\Models\FeeCollection::whereDate('payment_date', today())->sum('paid_amount'),
            'total_collection_month' => \App\Models\FeeCollection::whereMonth('payment_date', $currentMonth->month)
                ->whereYear('payment_date', $currentMonth->year)
                ->sum('paid_amount'),
            'total_expenses_month' => \App\Models\Expense::where('status', 'approved')
                ->whereMonth('expense_date', $currentMonth->month)
                ->whereYear('expense_date', $currentMonth->year)
                ->sum('amount'),
            'pending_fees' => \App\Models\Student::where('status', 'active')->count() * 1000, // Estimate
            'pending_expense_approvals' => \App\Models\Expense::where('status', 'pending')->count(),
            'total_students' => \App\Models\Student::where('status', 'active')->count(),
        ];
        
        // Fee Collection Status
        $feeStatus = $this->getFeeCollectionStatus();
        
        // Recent Collections
        $recentCollections = \App\Models\FeeCollection::with(['student', 'feeStructure', 'paymentMethod'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Recent Expenses
        $recentExpenses = \App\Models\Expense::with(['paymentMethod', 'creator'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Pending Approvals
        $pendingExpenses = \App\Models\Expense::with(['creator', 'paymentMethod'])
            ->where('status', 'pending')
            ->latest()
            ->get();
        
        // Payment Method Breakdown
        $paymentMethodStats = \App\Models\FeeCollection::with('paymentMethod')
            ->whereMonth('payment_date', $currentMonth->month)
            ->whereYear('payment_date', $currentMonth->year)
            ->selectRaw('payment_method_id, SUM(paid_amount) as total')
            ->groupBy('payment_method_id')
            ->get();
        
        // Defaulters Count
        $defaultersCount = \App\Models\Student::where('status', 'active')->count() - 
            \App\Models\FeeCollection::whereMonth('payment_date', $currentMonth->month)
                ->whereYear('payment_date', $currentMonth->year)
                ->distinct('student_id')
                ->count('student_id');
        
        $data = [
            'metrics' => $metrics,
            'feeStatus' => $feeStatus,
            'recentCollections' => $recentCollections,
            'recentExpenses' => $recentExpenses,
            'pendingExpenses' => $pendingExpenses,
            'paymentMethodStats' => $paymentMethodStats,
            'defaultersCount' => $defaultersCount,
            'charts' => [
                'feeCollection' => $this->chartService->getMonthlyFeeCollection(),
                'incomeExpense' => $this->chartService->getIncomeVsExpense(6),
                'expenseBreakdown' => $this->chartService->getExpenseBreakdown(),
            ],
        ];

        return view('dashboards.accountant', $data);
    }

    public function librarianDashboard()
    {
        $data = [
            'charts' => [
                'circulation' => $this->chartService->getLibraryCirculation(6),
            ],
        ];

        return view('dashboards.librarian', $data);
    }

    // Dashboard API Methods
    public function getKeyMetrics()
    {
        return [
            'total_students' => Student::where('status', 'active')->count(),
            'total_teachers' => Teacher::where('status', 'active')->count(),
            'total_revenue' => FeeCollection::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('paid_amount'),
            'pending_fees' => DB::table('fee_invoices')
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->sum('total_amount'),
        ];
    }

    public function getTodayAttendance()
    {
        $total = Student::where('status', 'active')->count();
        $present = Attendance::where('attendable_type', 'App\\Models\\Student')
            ->whereDate('date', today())
            ->where('status', 'present')
            ->count();
        $absent = Attendance::where('attendable_type', 'App\\Models\\Student')
            ->whereDate('date', today())
            ->where('status', 'absent')
            ->count();
        $late = Attendance::where('attendable_type', 'App\\Models\\Student')
            ->whereDate('date', today())
            ->where('status', 'late')
            ->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
        ];
    }

    public function getFeeCollectionStatus()
    {
        $currentMonth = now();
        
        $thisMonth = FeeCollection::whereMonth('payment_date', $currentMonth->month)
            ->whereYear('payment_date', $currentMonth->year)
            ->sum('paid_amount');

        $lastMonth = FeeCollection::whereMonth('payment_date', $currentMonth->copy()->subMonth()->month)
            ->whereYear('payment_date', $currentMonth->copy()->subMonth()->year)
            ->sum('paid_amount');

        $change = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;

        return [
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'change_percentage' => round($change, 2),
            'total_pending' => DB::table('fee_invoices')
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->sum('total_amount'),
        ];
    }

    public function getUpcomingExams($limit = 5)
    {
        return Exam::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit($limit)
            ->get();
    }

    public function getUpcomingEvents($limit = 5)
    {
        return Event::where('start_date', '>=', now())
            ->where('status', 'published')
            ->orderBy('start_date')
            ->limit($limit)
            ->get();
    }

    public function getRecentActivities($limit = 10)
    {
        $activities = [];

        // Recent students
        $recentStudents = Student::latest()->limit(3)->get();
        foreach ($recentStudents as $student) {
            $activities[] = [
                'type' => 'student',
                'icon' => 'user-plus',
                'message' => "New student {$student->first_name} {$student->last_name} admitted",
                'time' => $student->created_at,
            ];
        }

        // Recent fee payments
        $recentPayments = FeeCollection::with('student')->latest()->limit(3)->get();
        foreach ($recentPayments as $payment) {
            $activities[] = [
                'type' => 'fee',
                'icon' => 'dollar-sign',
                'message' => "{$payment->student->first_name} paid fees: " . number_format($payment->paid_amount),
                'time' => $payment->created_at,
            ];
        }

        // Sort by time
        usort($activities, function ($a, $b) {
            return $b['time'] <=> $a['time'];
        });

        return array_slice($activities, 0, $limit);
    }

    // Helper methods
    private function getTeacherTodayClasses($teacherId)
    {
        return DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.id')
            ->join('subjects', 'schedules.subject_id', '=', 'subjects.id')
            ->where('schedules.teacher_id', $teacherId)
            ->where('schedules.day', now()->format('l'))
            ->select('schedules.*', 'classes.name as class_name', 'subjects.name as subject_name')
            ->orderBy('schedules.start_time')
            ->get();
    }

    private function getTeacherActivities($teacherId)
    {
        return collect();
    }

    private function getStudentAttendance($studentId)
    {
        $thisMonth = Attendance::where('attendable_type', 'App\\Models\\Student')
            ->where('attendable_id', $studentId)
            ->whereMonth('date', now()->month)
            ->get();

        return [
            'total' => $thisMonth->count(),
            'present' => $thisMonth->where('status', 'present')->count(),
            'absent' => $thisMonth->where('status', 'absent')->count(),
            'late' => $thisMonth->where('status', 'late')->count(),
            'percentage' => $thisMonth->count() > 0 
                ? round(($thisMonth->where('status', 'present')->count() / $thisMonth->count()) * 100, 2) 
                : 0,
        ];
    }

    private function getStudentRecentResults($studentId)
    {
        return DB::table('marks')
            ->join('exams', 'marks.exam_id', '=', 'exams.id')
            ->join('subjects', 'marks.subject_id', '=', 'subjects.id')
            ->where('marks.student_id', $studentId)
            ->select('exams.name as exam_name', 'subjects.name as subject_name', 
                     'marks.marks_obtained', 'marks.total_marks', 'marks.grade')
            ->orderBy('exams.start_date', 'desc')
            ->limit(10)
            ->get();
    }

    private function getStudentFeeStatus($studentId)
    {
        $paid = DB::table('fee_payments')
            ->where('student_id', $studentId)
            ->sum('amount_paid');
            
        $total = DB::table('fee_invoices')
            ->where('student_id', $studentId)
            ->sum('total_amount');
            
        return [
            'total' => $total,
            'paid' => $paid,
            'pending' => $total - $paid,
        ];
    }

    private function getChildrenAttendance($studentIds)
    {
        return $studentIds->mapWithKeys(function ($studentId) {
            $thisMonth = Attendance::where('attendable_type', 'App\\Models\\Student')
                ->where('attendable_id', $studentId)
                ->whereMonth('date', now()->month)
                ->get();

            return [
                $studentId => [
                    'total' => $thisMonth->count(),
                    'present' => $thisMonth->where('status', 'present')->count(),
                    'percentage' => $thisMonth->count() > 0 
                        ? round(($thisMonth->where('status', 'present')->count() / $thisMonth->count()) * 100, 2) 
                        : 0,
                ]
            ];
        });
    }

    private function getChildrenFeeStatus($studentIds)
    {
        $invoices = DB::table('fee_invoices')
            ->whereIn('student_id', $studentIds)
            ->selectRaw('student_id, SUM(total_amount) as total')
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');
            
        $payments = DB::table('fee_payments')
            ->whereIn('student_id', $studentIds)
            ->selectRaw('student_id, SUM(amount_paid) as paid')
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');
            
        return $studentIds->mapWithKeys(function($studentId) use ($invoices, $payments) {
            $total = $invoices->get($studentId)->total ?? 0;
            $paid = $payments->get($studentId)->paid ?? 0;
            return [$studentId => (object)[
                'student_id' => $studentId,
                'total' => $total,
                'paid' => $paid
            ]];
        });
    }

    private function getChildrenRecentResults($studentIds)
    {
        return DB::table('marks')
            ->join('exams', 'marks.exam_id', '=', 'exams.id')
            ->join('subjects', 'marks.subject_id', '=', 'subjects.id')
            ->whereIn('marks.student_id', $studentIds)
            ->select('marks.student_id', 'exams.name as exam_name', 
                     'subjects.name as subject_name', 'marks.marks_obtained', 
                     'marks.total_marks', 'marks.grade')
            ->orderBy('exams.start_date', 'desc')
            ->limit(20)
            ->get()
            ->groupBy('student_id');
    }
}
