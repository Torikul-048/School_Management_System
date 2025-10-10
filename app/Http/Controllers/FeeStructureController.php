<?php

namespace App\Http\Controllers;

use App\Models\FeeStructure;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with('class')->latest()->paginate(15);
        return view('finance.fee-structures.index', compact('feeStructures'));
    }

    public function create()
    {
        $classes = ClassRoom::active()->get();
        return view('finance.fee-structures.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|in:tuition,transport,hostel,library,lab,sports,examination,admission,other',
            'frequency' => 'required|in:monthly,quarterly,half-yearly,yearly,one-time',
            'class_id' => 'nullable|exists:classes,id',
            'applicable_from' => 'required|date',
            'applicable_to' => 'nullable|date|after:applicable_from',
            'is_mandatory' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        FeeStructure::create($validated);

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure created successfully.');
    }

    public function show(FeeStructure $feeStructure)
    {
        $feeStructure->load('class', 'feeCollections');
        return view('finance.fee-structures.show', compact('feeStructure'));
    }

    public function edit(FeeStructure $feeStructure)
    {
        $classes = ClassRoom::active()->get();
        return view('finance.fee-structures.edit', compact('feeStructure', 'classes'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|in:tuition,transport,hostel,library,lab,sports,examination,admission,other',
            'frequency' => 'required|in:monthly,quarterly,half-yearly,yearly,one-time',
            'class_id' => 'nullable|exists:classes,id',
            'applicable_from' => 'required|date',
            'applicable_to' => 'nullable|date|after:applicable_from',
            'is_mandatory' => 'boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $feeStructure->update($validated);

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();
        return redirect()->route('fee-structures.index')->with('success', 'Fee structure deleted successfully.');
    }
}
