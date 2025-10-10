<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['paymentMethod', 'creator', 'approver'])
            ->latest()
            ->paginate(20);
        
        $totalExpenses = Expense::approved()->sum('amount');
        $pendingExpenses = Expense::pending()->count();
        
        return view('finance.expenses.index', compact('expenses', 'totalExpenses', 'pendingExpenses'));
    }

    public function create()
    {
        $paymentMethods = PaymentMethod::active()->get();
        return view('finance.expenses.create', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:salary,utility,maintenance,stationery,transport,food,event,equipment,infrastructure,other',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'reference_number' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'remarks' => 'nullable|string',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('expenses', 'public');
        }

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'pending';

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully. Awaiting approval.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['paymentMethod', 'creator', 'approver']);
        return view('finance.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $paymentMethods = PaymentMethod::active()->get();
        return view('finance.expenses.edit', compact('expense', 'paymentMethods'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:salary,utility,maintenance,stationery,transport,food,event,equipment,infrastructure,other',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'reference_number' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'remarks' => 'nullable|string',
        ]);

        if ($request->hasFile('attachment')) {
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('expenses', 'public');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->attachment) {
            Storage::disk('public')->delete($expense->attachment);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function approve($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Expense approved successfully.');
    }

    public function reject($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Expense rejected.');
    }

    public function byCategory()
    {
        $categories = Expense::approved()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('finance.expenses.by-category', compact('categories'));
    }
}
