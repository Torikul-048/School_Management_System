<?php

namespace App\Http\Controllers;

use App\Models\FeeCollection;
use App\Models\Student;
use App\Models\FeeStructure;
use App\Models\PaymentMethod;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FeeCollectionController extends Controller
{
    public function index()
    {
        $collections = FeeCollection::with(['student', 'feeStructure', 'paymentMethod', 'collector'])
            ->latest()
            ->paginate(20);
        
        $totalCollected = FeeCollection::paid()->sum('paid_amount');
        $todayCollection = FeeCollection::paid()->whereDate('payment_date', today())->sum('paid_amount');
        
        return view('finance.fee-collections.index', compact('collections', 'totalCollected', 'todayCollection'));
    }

    public function create()
    {
        $students = Student::with('class')->active()->get();
        $feeStructures = FeeStructure::active()->get();
        $paymentMethods = PaymentMethod::active()->get();
        $scholarships = Scholarship::active()->get();
        
        return view('finance.fee-collections.create', compact('students', 'feeStructures', 'paymentMethods', 'scholarships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'fee_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'fine_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'due_date' => 'nullable|date',
            'month' => 'nullable|string',
            'year' => 'nullable|integer',
            'transaction_id' => 'nullable|string',
            'cheque_number' => 'nullable|string',
            'cheque_date' => 'nullable|date',
            'bank_name' => 'nullable|string',
            'scholarship_id' => 'nullable|exists:scholarships,id',
            'remarks' => 'nullable|string',
        ]);

        $validated['collected_by'] = auth()->id();
        $validated['status'] = 'paid';

        $collection = FeeCollection::create($validated);

        return redirect()->route('fee-collections.receipt', $collection->id)
            ->with('success', 'Fee collected successfully.');
    }

    public function show(FeeCollection $feeCollection)
    {
        $feeCollection->load(['student', 'feeStructure', 'paymentMethod', 'scholarship', 'collector']);
        return view('finance.fee-collections.show', compact('feeCollection'));
    }

    public function receipt($id)
    {
        $collection = FeeCollection::with(['student', 'feeStructure', 'paymentMethod', 'collector'])
            ->findOrFail($id);
        return view('finance.fee-collections.receipt', compact('collection'));
    }

    public function printReceipt($id)
    {
        $collection = FeeCollection::with(['student', 'feeStructure', 'paymentMethod', 'collector'])
            ->findOrFail($id);
        
        $pdf = Pdf::loadView('finance.fee-collections.receipt-pdf', compact('collection'));
        return $pdf->download('receipt-' . $collection->receipt_number . '.pdf');
    }

    public function search(Request $request)
    {
        $query = FeeCollection::with(['student', 'feeStructure', 'paymentMethod']);

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->month && $request->year) {
            $query->where('month', $request->month)->where('year', $request->year);
        }

        if ($request->payment_method_id) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        $collections = $query->latest()->paginate(20);
        $students = Student::active()->get();
        $paymentMethods = PaymentMethod::active()->get();

        return view('finance.fee-collections.search', compact('collections', 'students', 'paymentMethods'));
    }

    public function defaulters()
    {
        $currentMonth = now()->format('F');
        $currentYear = now()->year;
        
        $paidStudents = FeeCollection::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->pluck('student_id')
            ->unique();
        
        $defaulters = Student::with('class')
            ->whereNotIn('id', $paidStudents)
            ->active()
            ->paginate(20);
        
        return view('finance.fee-collections.defaulters', compact('defaulters', 'currentMonth', 'currentYear'));
    }

    public function destroy(FeeCollection $feeCollection)
    {
        $feeCollection->delete();
        return redirect()->route('fee-collections.index')->with('success', 'Fee collection deleted successfully.');
    }
}
