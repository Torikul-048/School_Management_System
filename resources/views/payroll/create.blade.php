<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Process Payroll') }}
            </h2>
            <a href="{{ route('payroll.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('payroll.store') }}">
                        @csrf

                        <!-- Month Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payroll Month <span class="text-red-500">*</span></label>
                            <input type="month" name="month" value="{{ old('month') }}" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            @error('month')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teacher Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Teacher <span class="text-red-500">*</span></label>
                            <select name="teacher_id" id="teacher_id" required class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">-- Select Teacher --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" data-salary="{{ $teacher->salary }}">
                                        {{ $teacher->full_name }} ({{ $teacher->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Basic Salary -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Basic Salary <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="basic_salary" id="basic_salary" value="{{ old('basic_salary') }}" required readonly class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 bg-gray-100">
                        </div>

                        <!-- Allowances -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Allowances</h3>
                            <div class="space-y-3">
                                @foreach($allowances as $allowance)
                                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded">
                                    <div>
                                        <input type="checkbox" name="allowances[{{ $allowance->id }}]" value="{{ $allowance->amount }}" id="allowance_{{ $allowance->id }}" class="rounded allowance-check">
                                        <label for="allowance_{{ $allowance->id }}" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $allowance->component_name }}
                                        </label>
                                    </div>
                                    <span class="text-sm text-green-600 font-semibold">+₹{{ number_format($allowance->amount, 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Deductions -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Deductions</h3>
                            <div class="space-y-3">
                                @foreach($deductions as $deduction)
                                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded">
                                    <div>
                                        <input type="checkbox" name="deductions[{{ $deduction->id }}]" value="{{ $deduction->amount }}" id="deduction_{{ $deduction->id }}" class="rounded deduction-check">
                                        <label for="deduction_{{ $deduction->id }}" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $deduction->component_name }}
                                        </label>
                                    </div>
                                    <span class="text-sm text-red-600 font-semibold">-₹{{ number_format($deduction->amount, 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Salary Summary -->
                        <div class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Basic Salary:</span>
                                <span class="text-sm font-semibold" id="summary_basic">₹0.00</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-green-600">Total Allowances:</span>
                                <span class="text-sm font-semibold text-green-600" id="summary_allowances">₹0.00</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-red-600">Total Deductions:</span>
                                <span class="text-sm font-semibold text-red-600" id="summary_deductions">₹0.00</span>
                            </div>
                            <hr class="my-2 border-gray-300 dark:border-gray-600">
                            <div class="flex justify-between">
                                <span class="text-base font-bold text-gray-900 dark:text-gray-100">Net Salary:</span>
                                <span class="text-base font-bold text-indigo-600" id="summary_net">₹0.00</span>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks</label>
                            <textarea name="remarks" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('remarks') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition">
                                Process Payroll
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('teacher_id').addEventListener('change', function() {
            const salary = this.options[this.selectedIndex].dataset.salary;
            document.getElementById('basic_salary').value = salary;
            calculateSalary();
        });

        document.querySelectorAll('.allowance-check, .deduction-check').forEach(el => {
            el.addEventListener('change', calculateSalary);
        });

        function calculateSalary() {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            
            let allowances = 0;
            document.querySelectorAll('.allowance-check:checked').forEach(el => {
                allowances += parseFloat(el.value);
            });
            
            let deductions = 0;
            document.querySelectorAll('.deduction-check:checked').forEach(el => {
                deductions += parseFloat(el.value);
            });
            
            const net = basic + allowances - deductions;
            
            document.getElementById('summary_basic').textContent = '₹' + basic.toFixed(2);
            document.getElementById('summary_allowances').textContent = '₹' + allowances.toFixed(2);
            document.getElementById('summary_deductions').textContent = '₹' + deductions.toFixed(2);
            document.getElementById('summary_net').textContent = '₹' + net.toFixed(2);
        }
    </script>
</x-app-layout>
