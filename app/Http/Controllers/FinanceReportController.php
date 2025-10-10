<?php

namespace App\Http\Controllers;

use App\Models\FeeCollection;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FinanceReportController extends Controller
{
    public function index()
    {
        return view('finance.reports.index');
    }

    public function income(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $collections = FeeCollection::with(['student', 'feeStructure', 'paymentMethod'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->paid()
            ->get();

        $totalIncome = $collections->sum('paid_amount');
        $byFeeType = $collections->groupBy('feeStructure.fee_type')->map->sum('paid_amount');
        $byPaymentMethod = $collections->groupBy('paymentMethod.name')->map->sum('paid_amount');

        return view('finance.reports.income', compact('collections', 'totalIncome', 'byFeeType', 'byPaymentMethod', 'startDate', 'endDate'));
    }

    public function expenses(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $expenses = Expense::with(['paymentMethod', 'creator'])
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->approved()
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $byCategory = $expenses->groupBy('category')->map->sum('amount');

        return view('finance.reports.expenses', compact('expenses', 'totalExpenses', 'byCategory', 'startDate', 'endDate'));
    }

    public function balance(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $income = FeeCollection::whereBetween('payment_date', [$startDate, $endDate])
            ->paid()
            ->sum('paid_amount');

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->approved()
            ->sum('amount');

        $balance = $income - $expenses;

        // Monthly trend data
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyIncome = FeeCollection::whereBetween('payment_date', [$monthStart, $monthEnd])
                ->paid()
                ->sum('paid_amount');

            $monthlyExpense = Expense::whereBetween('expense_date', [$monthStart, $monthEnd])
                ->approved()
                ->sum('amount');

            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'income' => $monthlyIncome,
                'expense' => $monthlyExpense,
                'balance' => $monthlyIncome - $monthlyExpense,
            ];
        }

        return view('finance.reports.balance', compact('income', 'expenses', 'balance', 'monthlyData', 'startDate', 'endDate'));
    }

    public function studentLedger($studentId)
    {
        $student = Student::with('class')->findOrFail($studentId);

        $collections = FeeCollection::with('feeStructure')
            ->where('student_id', $studentId)
            ->latest()
            ->get();

        $invoices = Invoice::with('items')
            ->where('student_id', $studentId)
            ->latest()
            ->get();

        $totalPaid = $collections->sum('paid_amount');
        $totalInvoiced = $invoices->sum('total_amount');
        $balance = $totalInvoiced - $totalPaid;

        return view('finance.reports.student-ledger', compact('student', 'collections', 'invoices', 'totalPaid', 'totalInvoiced', 'balance'));
    }

    public function studentLedgerList()
    {
        $students = Student::with('class')->active()->get();
        return view('finance.reports.student-ledger-list', compact('students'));
    }

    public function dailyCollection(Request $request)
    {
        $date = $request->date ?? today();

        $collections = FeeCollection::with(['student', 'feeStructure', 'paymentMethod', 'collector'])
            ->whereDate('payment_date', $date)
            ->paid()
            ->get();

        $totalCollection = $collections->sum('paid_amount');
        $byCollector = $collections->groupBy('collector.name')->map->sum('paid_amount');

        return view('finance.reports.daily-collection', compact('collections', 'totalCollection', 'byCollector', 'date'));
    }

    public function downloadIncomePdf(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $collections = FeeCollection::with(['student', 'feeStructure', 'paymentMethod'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->paid()
            ->get();

        $totalIncome = $collections->sum('paid_amount');

        $pdf = Pdf::loadView('finance.reports.income-pdf', compact('collections', 'totalIncome', 'startDate', 'endDate'));
        return $pdf->download('income-report-' . now()->format('Y-m-d') . '.pdf');
    }

    public function downloadExpensePdf(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth();
        $endDate = $request->end_date ?? now()->endOfMonth();

        $expenses = Expense::with(['paymentMethod', 'creator'])
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->approved()
            ->get();

        $totalExpenses = $expenses->sum('amount');

        $pdf = Pdf::loadView('finance.reports.expense-pdf', compact('expenses', 'totalExpenses', 'startDate', 'endDate'));
        return $pdf->download('expense-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
