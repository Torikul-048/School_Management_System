<?php

namespace App\Exports;

use App\Models\FeeCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FeeCollectionExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $filters;

    public function __construct($startDate = null, $endDate = null, $filters = [])
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = FeeCollection::with(['student', 'feeStructure', 'paymentMethod']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('payment_date', [$this->startDate, $this->endDate]);
        }

        if (!empty($this->filters['student_id'])) {
            $query->where('student_id', $this->filters['student_id']);
        }

        if (!empty($this->filters['payment_method_id'])) {
            $query->where('payment_method_id', $this->filters['payment_method_id']);
        }

        $collections = $query->get();

        return view('exports.fee-collections', [
            'collections' => $collections,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function title(): string
    {
        return 'Fee Collections';
    }
}
