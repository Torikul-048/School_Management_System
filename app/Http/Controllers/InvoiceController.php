<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['student', 'creator'])
            ->latest()
            ->paginate(20);
        
        $totalInvoiced = Invoice::sum('total_amount');
        $totalPaid = Invoice::sum('paid_amount');
        $overdueCount = Invoice::overdue()->count();
        
        return view('finance.invoices.index', compact('invoices', 'totalInvoiced', 'totalPaid', 'overdueCount'));
    }

    public function create()
    {
        $students = Student::with('class')->active()->get();
        $feeStructures = FeeStructure::active()->get();
        
        return view('finance.invoices.create', compact('students', 'feeStructures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.fee_structure_id' => 'required|exists:fee_structures,id',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate subtotal
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $discount = $validated['discount'] ?? 0;
        $tax = $validated['tax'] ?? 0;
        $totalAmount = $subtotal - $discount + $tax;

        $invoice = Invoice::create([
            'student_id' => $validated['student_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'balance' => $totalAmount,
            'notes' => $validated['notes'] ?? null,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // Create invoice items
        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'fee_structure_id' => $item['fee_structure_id'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['student.class', 'items.feeStructure', 'creator']);
        return view('finance.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot edit paid invoice.');
        }

        $invoice->load('items');
        $students = Student::with('class')->active()->get();
        $feeStructures = FeeStructure::active()->get();
        
        return view('finance.invoices.edit', compact('invoice', 'students', 'feeStructures'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot update paid invoice.');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.fee_structure_id' => 'required|exists:fee_structures,id',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate subtotal
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $discount = $validated['discount'] ?? 0;
        $tax = $validated['tax'] ?? 0;
        $totalAmount = $subtotal - $discount + $tax;

        $invoice->update([
            'student_id' => $validated['student_id'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'balance' => $totalAmount - $invoice->paid_amount,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Delete old items and create new ones
        $invoice->items()->delete();
        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'fee_structure_id' => $item['fee_structure_id'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'amount' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot delete paid invoice.');
        }

        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function send($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'sent']);

        return redirect()->back()->with('success', 'Invoice sent successfully.');
    }

    public function cancel($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Invoice cancelled.');
    }

    public function printInvoice($id)
    {
        $invoice = Invoice::with(['student.class', 'items.feeStructure', 'creator'])
            ->findOrFail($id);
        
        $pdf = Pdf::loadView('finance.invoices.invoice-pdf', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
