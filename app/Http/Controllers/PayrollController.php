<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display a listing of payroll records
     */
    public function index(Request $request)
    {
        $query = Payroll::with(['teacher']);

        // Filter by month/year
        if ($request->filled('month')) {
            $month = $request->month;
            $query->whereYear('payment_date', '=', Carbon::parse($month)->year)
                  ->whereMonth('payment_date', '=', Carbon::parse($month)->month);
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->latest('payment_date')->paginate(15);

        $teachers = Teacher::active()->get();

        $stats = [
            'total' => Payroll::count(),
            'pending' => Payroll::where('status', 'pending')->count(),
            'paid' => Payroll::where('status', 'paid')->count(),
            'total_amount' => Payroll::where('status', 'paid')->sum('net_salary'),
        ];

        return view('payroll.index', compact('payrolls', 'teachers', 'stats'));
    }

    /**
     * Show salary structure
     */
    public function salaryStructure()
    {
        $teachers = Teacher::with(['salaryStructure'])->active()->get();
        
        return view('payroll.salary-structure', compact('teachers'));
    }

    /**
     * Update or create salary structure
     */
    public function updateSalaryStructure(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'other_allowance' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'professional_tax' => 'nullable|numeric|min:0',
            'income_tax' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $totalAllowances = ($validated['hra'] ?? 0) + 
                              ($validated['transport_allowance'] ?? 0) + 
                              ($validated['medical_allowance'] ?? 0) + 
                              ($validated['special_allowance'] ?? 0) + 
                              ($validated['other_allowance'] ?? 0);

            $totalDeductions = ($validated['provident_fund'] ?? 0) + 
                              ($validated['professional_tax'] ?? 0) + 
                              ($validated['income_tax'] ?? 0) + 
                              ($validated['other_deductions'] ?? 0);

            $grossSalary = $validated['basic_salary'] + $totalAllowances;
            $netSalary = $grossSalary - $totalDeductions;

            // Update or create salary structure
            SalaryStructure::updateOrCreate(
                ['teacher_id' => $teacher->id],
                array_merge($validated, [
                    'total_allowances' => $totalAllowances,
                    'total_deductions' => $totalDeductions,
                    'gross_salary' => $grossSalary,
                    'net_salary' => $netSalary,
                ])
            );

            // Update teacher's base salary
            $teacher->update(['salary' => $netSalary]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Salary structure updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update salary structure: ' . $e->getMessage());
        }
    }

    /**
     * Show process payroll form
     */
    public function create()
    {
        $teachers = Teacher::with(['salaryStructure'])->active()->get();
        $month = now()->format('Y-m');

        return view('payroll.create', compact('teachers', 'month'));
    }

    /**
     * Process payroll for selected teachers
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
            'teachers' => 'required|array',
            'teachers.*' => 'exists:teachers,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,cheque',
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $processedCount = 0;
            $errors = [];

            foreach ($validated['teachers'] as $teacherId) {
                $teacher = Teacher::with('salaryStructure')->find($teacherId);

                if (!$teacher || !$teacher->salaryStructure) {
                    $errors[] = "Salary structure not found for {$teacher->first_name} {$teacher->last_name}";
                    continue;
                }

                // Check if payroll already exists for this month
                $existingPayroll = Payroll::where('teacher_id', $teacherId)
                    ->whereYear('payment_date', Carbon::parse($validated['month'])->year)
                    ->whereMonth('payment_date', Carbon::parse($validated['month'])->month)
                    ->first();

                if ($existingPayroll) {
                    $errors[] = "Payroll already processed for {$teacher->first_name} {$teacher->last_name}";
                    continue;
                }

                $structure = $teacher->salaryStructure;

                // Calculate attendance-based deductions (optional)
                $attendances = $teacher->attendances()
                    ->whereYear('date', Carbon::parse($validated['month'])->year)
                    ->whereMonth('date', Carbon::parse($validated['month'])->month)
                    ->get();

                $workingDays = Carbon::parse($validated['month'])->daysInMonth;
                $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();
                $absentDays = $attendances->where('status', 'absent')->count();

                // Calculate salary based on attendance
                $perDaySalary = $structure->net_salary / $workingDays;
                $attendanceDeduction = $absentDays * $perDaySalary;
                $finalNetSalary = $structure->net_salary - $attendanceDeduction;

                // Create payroll record
                $payroll = Payroll::create([
                    'teacher_id' => $teacherId,
                    'month' => $validated['month'],
                    'payment_date' => $validated['payment_date'],
                    'basic_salary' => $structure->basic_salary,
                    'allowances' => $structure->total_allowances,
                    'deductions' => $structure->total_deductions + $attendanceDeduction,
                    'gross_salary' => $structure->gross_salary,
                    'net_salary' => $finalNetSalary,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'paid',
                    'remarks' => $validated['remarks'] ?? null,
                    'working_days' => $workingDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'attendance_deduction' => $attendanceDeduction,
                ]);

                // Create payroll items for breakdown
                $this->createPayrollItems($payroll, $structure, $attendanceDeduction);

                $processedCount++;
            }

            DB::commit();

            $message = "Payroll processed successfully for {$processedCount} teacher(s)";
            if (!empty($errors)) {
                $message .= ". Errors: " . implode(', ', $errors);
            }

            return redirect()->route('payroll.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to process payroll: ' . $e->getMessage());
        }
    }

    /**
     * Create detailed payroll items
     */
    private function createPayrollItems($payroll, $structure, $attendanceDeduction)
    {
        // Allowances
        $allowances = [
            'HRA' => $structure->hra ?? 0,
            'Transport Allowance' => $structure->transport_allowance ?? 0,
            'Medical Allowance' => $structure->medical_allowance ?? 0,
            'Special Allowance' => $structure->special_allowance ?? 0,
            'Other Allowance' => $structure->other_allowance ?? 0,
        ];

        foreach ($allowances as $name => $amount) {
            if ($amount > 0) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'item_type' => 'allowance',
                    'item_name' => $name,
                    'amount' => $amount,
                ]);
            }
        }

        // Deductions
        $deductions = [
            'Provident Fund' => $structure->provident_fund ?? 0,
            'Professional Tax' => $structure->professional_tax ?? 0,
            'Income Tax' => $structure->income_tax ?? 0,
            'Other Deductions' => $structure->other_deductions ?? 0,
            'Attendance Deduction' => $attendanceDeduction,
        ];

        foreach ($deductions as $name => $amount) {
            if ($amount > 0) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'item_type' => 'deduction',
                    'item_name' => $name,
                    'amount' => $amount,
                ]);
            }
        }
    }

    /**
     * Display payroll details
     */
    public function show(Payroll $payroll)
    {
        $payroll->load(['teacher', 'items']);

        return view('payroll.show', compact('payroll'));
    }

    /**
     * Generate salary slip
     */
    public function salarySlip(Payroll $payroll)
    {
        $payroll->load(['teacher', 'items']);

        return view('payroll.salary-slip', compact('payroll'));
    }

    /**
     * Download salary slip PDF
     */
    public function downloadSalarySlip(Payroll $payroll)
    {
        $payroll->load(['teacher', 'items']);

        $pdf = PDF::loadView('payroll.salary-slip-pdf', compact('payroll'));
        
        $filename = 'salary-slip-' . $payroll->teacher->employee_id . '-' . Carbon::parse($payroll->month)->format('Y-m') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Display salary history for a teacher
     */
    public function salaryHistory(Teacher $teacher)
    {
        $payrolls = Payroll::where('teacher_id', $teacher->id)
            ->with('items')
            ->latest('payment_date')
            ->paginate(12);

        $stats = [
            'total_paid' => $payrolls->where('status', 'paid')->sum('net_salary'),
            'average_salary' => $payrolls->where('status', 'paid')->avg('net_salary'),
            'months_paid' => $payrolls->where('status', 'paid')->count(),
        ];

        return view('payroll.salary-history', compact('teacher', 'payrolls', 'stats'));
    }

    /**
     * Generate payroll reports
     */
    public function reports(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');
        $reportType = $request->report_type ?? 'monthly';

        $reportData = null;

        switch ($reportType) {
            case 'monthly':
                $reportData = $this->getMonthlyReport($month);
                break;
            case 'department':
                $reportData = $this->getDepartmentReport($month);
                break;
            case 'summary':
                $reportData = $this->getSummaryReport($month);
                break;
        }

        return view('payroll.reports', compact('reportData', 'month', 'reportType'));
    }

    /**
     * Get monthly payroll report
     */
    private function getMonthlyReport($month)
    {
        $payrolls = Payroll::with(['teacher'])
            ->whereYear('payment_date', Carbon::parse($month)->year)
            ->whereMonth('payment_date', Carbon::parse($month)->month)
            ->get();

        return [
            'title' => 'Monthly Payroll Report - ' . Carbon::parse($month)->format('F Y'),
            'summary' => [
                'total_teachers' => $payrolls->count(),
                'total_gross_salary' => $payrolls->sum('gross_salary'),
                'total_deductions' => $payrolls->sum('deductions'),
                'total_net_salary' => $payrolls->sum('net_salary'),
                'total_allowances' => $payrolls->sum('allowances'),
            ],
            'columns' => ['Employee ID', 'Teacher Name', 'Basic Salary', 'Allowances', 'Deductions', 'Net Salary', 'Status'],
            'data' => $payrolls->map(function ($payroll) {
                return [
                    'employee_id' => $payroll->teacher->employee_id,
                    'name' => $payroll->teacher->full_name,
                    'basic_salary' => number_format($payroll->basic_salary, 2),
                    'allowances' => number_format($payroll->allowances, 2),
                    'deductions' => number_format($payroll->deductions, 2),
                    'net_salary' => number_format($payroll->net_salary, 2),
                    'status' => $payroll->status,
                ];
            })->toArray(),
        ];
    }

    /**
     * Get department-wise payroll report
     */
    private function getDepartmentReport($month)
    {
        $data = DB::table('payrolls')
            ->join('teachers', 'payrolls.teacher_id', '=', 'teachers.id')
            ->select(
                'teachers.department',
                DB::raw('COUNT(payrolls.id) as teacher_count'),
                DB::raw('SUM(payrolls.gross_salary) as total_gross'),
                DB::raw('SUM(payrolls.deductions) as total_deductions'),
                DB::raw('SUM(payrolls.net_salary) as total_net')
            )
            ->whereYear('payrolls.payment_date', Carbon::parse($month)->year)
            ->whereMonth('payrolls.payment_date', Carbon::parse($month)->month)
            ->groupBy('teachers.department')
            ->get();

        return [
            'title' => 'Department-wise Payroll Report - ' . Carbon::parse($month)->format('F Y'),
            'summary' => [
                'departments' => $data->count(),
                'total_gross' => $data->sum('total_gross'),
                'total_net' => $data->sum('total_net'),
            ],
            'columns' => ['Department', 'Teachers', 'Gross Salary', 'Deductions', 'Net Salary'],
            'data' => $data->map(function ($row) {
                return [
                    'department' => $row->department ?? 'Not Assigned',
                    'teacher_count' => $row->teacher_count,
                    'total_gross' => number_format($row->total_gross, 2),
                    'total_deductions' => number_format($row->total_deductions, 2),
                    'total_net' => number_format($row->total_net, 2),
                ];
            })->toArray(),
        ];
    }

    /**
     * Get summary report
     */
    private function getSummaryReport($month)
    {
        $yearStart = Carbon::parse($month)->startOfYear();
        $yearEnd = Carbon::parse($month)->endOfYear();

        $data = DB::table('payrolls')
            ->select(
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('COUNT(*) as teacher_count'),
                DB::raw('SUM(gross_salary) as total_gross'),
                DB::raw('SUM(net_salary) as total_net')
            )
            ->whereBetween('payment_date', [$yearStart, $yearEnd])
            ->groupBy(DB::raw('YEAR(payment_date)'), DB::raw('MONTH(payment_date)'))
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return [
            'title' => 'Yearly Payroll Summary - ' . Carbon::parse($month)->year,
            'summary' => [
                'total_paid' => $data->sum('total_net'),
                'average_monthly' => $data->avg('total_net'),
                'months_processed' => $data->count(),
            ],
            'columns' => ['Month', 'Teachers Paid', 'Gross Salary', 'Net Salary'],
            'data' => $data->map(function ($row) {
                return [
                    'month' => Carbon::create($row->year, $row->month)->format('F Y'),
                    'teacher_count' => $row->teacher_count,
                    'total_gross' => number_format($row->total_gross, 2),
                    'total_net' => number_format($row->total_net, 2),
                ];
            })->toArray(),
        ];
    }

    /**
     * Delete payroll record
     */
    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->back()
                ->with('error', 'Cannot delete paid payroll records!');
        }

        $payroll->delete();

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll record deleted successfully!');
    }
}
