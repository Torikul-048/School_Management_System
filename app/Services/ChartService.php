<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\FeeCollection;
use App\Models\Exam;
use App\Models\Event;
use App\Models\BookIssue;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChartService
{
    /**
     * Get student enrollment trend (last 12 months)
     */
    public function getStudentEnrollmentTrend()
    {
        $dbDriver = DB::connection()->getDriverName();
        
        if ($dbDriver === 'sqlite') {
            $data = DB::table('students')
                ->selectRaw('strftime("%Y-%m", admission_date) as month, COUNT(*) as count')
                ->where('admission_date', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('strftime("%Y-%m", admission_date)'))
                ->orderBy('month')
                ->get();
        } else {
            $data = DB::table('students')
                ->selectRaw('DATE_FORMAT(admission_date, "%Y-%m") as month, COUNT(*) as count')
                ->where('admission_date', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('DATE_FORMAT(admission_date, "%Y-%m")'))
                ->orderBy('month')
                ->get();
        }

        return [
            'labels' => $data->pluck('month')->toArray(),
            'datasets' => [
                [
                    'label' => 'New Students',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ]
            ]
        ];
    }

    /**
     * Get attendance statistics (last 30 days)
     */
    public function getAttendanceTrend($days = 30)
    {
        $startDate = now()->subDays($days);
        
        $data = DB::table('attendances')
            ->selectRaw('DATE(date) as date, 
                         SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present,
                         SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent,
                         SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
            ->where('date', '>=', $startDate)
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $data->pluck('present')->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
                [
                    'label' => 'Absent',
                    'data' => $data->pluck('absent')->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                ],
                [
                    'label' => 'Late',
                    'data' => $data->pluck('late')->toArray(),
                    'backgroundColor' => 'rgba(255, 206, 86, 0.6)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                ]
            ]
        ];
    }

    /**
     * Get monthly fee collection (last 12 months)
     */
    public function getMonthlyFeeCollection()
    {
        $dbDriver = DB::connection()->getDriverName();
        
        if ($dbDriver === 'sqlite') {
            $data = DB::table('fee_collections')
                ->selectRaw('strftime("%Y-%m", payment_date) as month, 
                             SUM(paid_amount) as total')
                ->where('payment_date', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('strftime("%Y-%m", payment_date)'))
                ->orderBy('month')
                ->get();
        } else {
            $data = DB::table('fee_collections')
                ->selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as month, 
                             SUM(paid_amount) as total')
                ->where('payment_date', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('DATE_FORMAT(payment_date, "%Y-%m")'))
                ->orderBy('month')
                ->get();
        }

        return [
            'labels' => $data->pluck('month')->toArray(),
            'datasets' => [
                [
                    'label' => 'Fee Collection (৳)',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
    }

    /**
     * Get expense breakdown by category
     */
    public function getExpenseBreakdown($startDate = null, $endDate = null)
    {
        $query = Expense::selectRaw('category, SUM(amount) as total')
            ->where('status', 'approved')
            ->groupBy('category');

        if ($startDate) {
            $query->whereDate('expense_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('expense_date', '<=', $endDate);
        }

        $data = $query->get();

        return [
            'labels' => $data->pluck('category')->map(fn($c) => ucfirst($c))->toArray(),
            'datasets' => [
                [
                    'label' => 'Expenses (৳)',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                    ],
                ]
            ]
        ];
    }

    /**
     * Get student performance by class
     */
    public function getStudentPerformanceByClass($examId = null)
    {
        $query = DB::table('marks')
            ->join('students', 'marks.student_id', '=', 'students.id')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->selectRaw('classes.name as class_name, AVG(marks.marks_obtained) as avg_marks')
            ->groupBy('classes.id', 'classes.name')
            ->orderBy('classes.name');

        if ($examId) {
            $query->where('marks.exam_id', $examId);
        }

        $data = $query->get();

        return [
            'labels' => $data->pluck('class_name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Average Marks',
                    'data' => $data->pluck('avg_marks')->map(fn($m) => round($m, 2))->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
    }

    /**
     * Get teacher workload distribution
     */
    public function getTeacherWorkload()
    {
        $dbDriver = DB::connection()->getDriverName();
        
        if ($dbDriver === 'sqlite') {
            $data = DB::table('subject_assignments')
                ->join('teachers', 'subject_assignments.teacher_id', '=', 'teachers.id')
                ->selectRaw('(teachers.first_name || " " || teachers.last_name) as teacher_name, 
                             COUNT(*) as classes_count')
                ->where('teachers.status', 'active')
                ->groupBy('teachers.id', 'teacher_name')
                ->orderByDesc('classes_count')
                ->limit(10)
                ->get();
        } else {
            $data = DB::table('subject_assignments')
                ->join('teachers', 'subject_assignments.teacher_id', '=', 'teachers.id')
                ->selectRaw('CONCAT(teachers.first_name, " ", teachers.last_name) as teacher_name, 
                             COUNT(*) as classes_count')
                ->where('teachers.status', 'active')
                ->groupBy('teachers.id', 'teacher_name')
                ->orderByDesc('classes_count')
                ->limit(10)
                ->get();
        }

        return [
            'labels' => $data->pluck('teacher_name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Classes Assigned',
                    'data' => $data->pluck('classes_count')->toArray(),
                    'backgroundColor' => 'rgba(153, 102, 255, 0.6)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
    }

    /**
     * Get library book circulation
     */
    public function getLibraryCirculation($months = 6)
    {
        $dbDriver = DB::connection()->getDriverName();
        
        if ($dbDriver === 'sqlite') {
            $data = DB::table('book_issues')
                ->selectRaw('strftime("%Y-%m", issue_date) as month, 
                             COUNT(*) as issues')
                ->where('issue_date', '>=', now()->subMonths($months))
                ->groupBy(DB::raw('strftime("%Y-%m", issue_date)'))
                ->orderBy('month')
                ->get();
        } else {
            $data = DB::table('book_issues')
                ->selectRaw('DATE_FORMAT(issue_date, "%Y-%m") as month, 
                             COUNT(*) as issues')
                ->where('issue_date', '>=', now()->subMonths($months))
                ->groupBy(DB::raw('DATE_FORMAT(issue_date, "%Y-%m")'))
                ->orderBy('month')
                ->get();
        }

        return [
            'labels' => $data->pluck('month')->toArray(),
            'datasets' => [
                [
                    'label' => 'Books Issued',
                    'data' => $data->pluck('issues')->toArray(),
                    'backgroundColor' => 'rgba(255, 159, 64, 0.6)',
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
    }

    /**
     * Get gender distribution
     */
    public function getGenderDistribution()
    {
        $data = Student::selectRaw('gender, COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('gender')
            ->get();

        return [
            'labels' => $data->pluck('gender')->map(fn($g) => ucfirst($g))->toArray(),
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                    ],
                ]
            ]
        ];
    }

    /**
     * Get income vs expense comparison
     */
    public function getIncomeVsExpense($months = 12)
    {
        $startDate = now()->subMonths($months)->startOfMonth();
        $dbDriver = DB::connection()->getDriverName();
        
        if ($dbDriver === 'sqlite') {
            $income = DB::table('fee_collections')
                ->selectRaw('strftime("%Y-%m", payment_date) as month, 
                             SUM(paid_amount) as total')
                ->where('payment_date', '>=', $startDate)
                ->groupBy(DB::raw('strftime("%Y-%m", payment_date)'))
                ->orderBy('month')
                ->pluck('total', 'month');

            $expenses = DB::table('expenses')
                ->selectRaw('strftime("%Y-%m", expense_date) as month, 
                             SUM(amount) as total')
                ->where('expense_date', '>=', $startDate)
                ->where('status', 'approved')
                ->groupBy(DB::raw('strftime("%Y-%m", expense_date)'))
                ->orderBy('month')
                ->pluck('total', 'month');
        } else {
            $income = DB::table('fee_collections')
                ->selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as month, 
                             SUM(paid_amount) as total')
                ->where('payment_date', '>=', $startDate)
                ->groupBy(DB::raw('DATE_FORMAT(payment_date, "%Y-%m")'))
                ->orderBy('month')
                ->pluck('total', 'month');

            $expenses = DB::table('expenses')
                ->selectRaw('DATE_FORMAT(expense_date, "%Y-%m") as month, 
                             SUM(amount) as total')
                ->where('expense_date', '>=', $startDate)
                ->where('status', 'approved')
                ->groupBy(DB::raw('DATE_FORMAT(expense_date, "%Y-%m")'))
                ->orderBy('month')
                ->pluck('total', 'month');
        }

        // Merge months
        $allMonths = $income->keys()->merge($expenses->keys())->unique()->sort()->values();

        return [
            'labels' => $allMonths->toArray(),
            'datasets' => [
                [
                    'label' => 'Income (৳)',
                    'data' => $allMonths->map(fn($m) => $income->get($m, 0))->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Expense (৳)',
                    'data' => $allMonths->map(fn($m) => $expenses->get($m, 0))->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
    }

    /**
     * Get class-wise student distribution
     */
    public function getClassDistribution()
    {
        $data = DB::table('students')
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->selectRaw('classes.name as class_name, COUNT(*) as count')
            ->where('students.status', 'active')
            ->groupBy('classes.id', 'classes.name')
            ->orderBy('classes.name')
            ->get();

        return [
            'labels' => $data->pluck('class_name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => 'rgba(153, 102, 255, 0.6)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 2,
                ]
            ]
        ];
    }
}
