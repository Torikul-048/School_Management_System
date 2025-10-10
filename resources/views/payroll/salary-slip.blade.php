<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Salary Slip') }}
            </h2>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    Print Slip
                </button>
                <a href="{{ route('payroll.show', $payroll) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden salary-slip">
                <!-- Header -->
                <div class="bg-indigo-600 text-white p-6 text-center">
                    <h1 class="text-2xl font-bold">{{ config('app.name', 'School Name') }}</h1>
                    <p class="text-sm">Salary Slip for {{ \Carbon\Carbon::parse($payroll->month)->format('F Y') }}</p>
                </div>

                <!-- Employee Details -->
                <div class="p-6 border-b">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Employee Name:</p>
                            <p class="font-semibold text-gray-900">{{ $payroll->teacher->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Employee ID:</p>
                            <p class="font-semibold text-gray-900">{{ $payroll->teacher->employee_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Designation:</p>
                            <p class="font-semibold text-gray-900">{{ $payroll->teacher->designation }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Department:</p>
                            <p class="font-semibold text-gray-900">{{ $payroll->teacher->department }}</p>
                        </div>
                    </div>
                </div>

                <!-- Salary Details -->
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-8">
                        <!-- Earnings -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Earnings</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-700">Basic Salary</span>
                                    <span class="font-semibold">₹{{ number_format($payroll->basic_salary, 2) }}</span>
                                </div>
                                @foreach($payroll->items->where('type', 'allowance') as $item)
                                <div class="flex justify-between">
                                    <span class="text-gray-700 text-sm">{{ $item->component_name }}</span>
                                    <span class="text-sm font-semibold">₹{{ number_format($item->amount, 2) }}</span>
                                </div>
                                @endforeach
                                <div class="flex justify-between pt-2 border-t font-bold">
                                    <span>Total Earnings</span>
                                    <span class="text-green-600">₹{{ number_format($payroll->basic_salary + $payroll->total_allowances, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Deductions -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Deductions</h3>
                            <div class="space-y-2">
                                @forelse($payroll->items->where('type', 'deduction') as $item)
                                <div class="flex justify-between">
                                    <span class="text-gray-700 text-sm">{{ $item->component_name }}</span>
                                    <span class="text-sm font-semibold">₹{{ number_format($item->amount, 2) }}</span>
                                </div>
                                @empty
                                <p class="text-sm text-gray-500">No deductions</p>
                                @endforelse
                                <div class="flex justify-between pt-2 border-t font-bold">
                                    <span>Total Deductions</span>
                                    <span class="text-red-600">₹{{ number_format($payroll->total_deductions, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Salary -->
                    <div class="mt-6 pt-6 border-t-2 border-indigo-600">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-900">Net Salary</span>
                            <span class="text-3xl font-bold text-indigo-600">₹{{ number_format($payroll->net_salary, 2) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            In Words: <span class="font-semibold">{{ ucwords(\NumberFormatter::create('en_IN', \NumberFormatter::SPELLOUT)->format($payroll->net_salary)) }} Rupees Only</span>
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-100 p-6 text-center">
                    <p class="text-xs text-gray-600">This is a computer-generated salary slip and does not require a signature.</p>
                    <p class="text-xs text-gray-600 mt-1">Generated on: {{ now()->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .salary-slip, .salary-slip * {
                visibility: visible;
            }
            .salary-slip {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</x-app-layout>
