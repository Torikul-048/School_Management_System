<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExpenseExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $category;

    public function __construct($startDate = null, $endDate = null, $category = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->category = $category;
    }

    public function view(): View
    {
        $query = Expense::with(['paymentMethod', 'creator', 'approver']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('expense_date', [$this->startDate, $this->endDate]);
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $expenses = $query->get();

        return view('exports.expenses', [
            'expenses' => $expenses,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function title(): string
    {
        return 'Expenses';
    }
}
