@extends('layouts.admin')

@section('title', 'Collect Fee')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('fee-collections.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Collect Fee</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Record fee payment from student</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="max-w-4xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <form action="{{ route('fee-collections.store') }}" method="POST" id="feeCollectionForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Student *</label>
                            <select name="student_id" id="student_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->roll_number }} ({{ $student->class->name }})</option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fee Structure -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fee Type *</label>
                            <select name="fee_structure_id" id="fee_structure_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700">
                                <option value="">Select Fee Type</option>
                                @foreach($feeStructures as $fee)
                                    <option value="{{ $fee->id }}" data-amount="{{ $fee->amount }}">
                                        {{ $fee->name }} - ৳{{ number_format($fee->amount, 2) }} ({{ ucfirst($fee->frequency) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('fee_structure_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fee Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fee Amount (৳) *</label>
                            <input type="number" name="fee_amount" id="fee_amount" value="{{ old('fee_amount') }}" step="0.01" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('fee_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Discount Amount (৳)</label>
                            <input type="number" name="discount_amount" id="discount_amount" value="0" step="0.01" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('discount_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fine Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fine/Late Fee (৳)</label>
                            <input type="number" name="fine_amount" id="fine_amount" value="0" step="0.01" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('fine_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Paid Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Paid Amount (৳) *</label>
                            <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount') }}" step="0.01" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white bg-yellow-50">
                            @error('paid_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method *</label>
                            <select name="payment_method_id" id="payment_method_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700">
                                <option value="">Select Method</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" data-code="{{ $method->code }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Date *</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('payment_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Month -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                            <select name="month"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700">
                                <option value="">Select Month</option>
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                    <option value="{{ $month }}" {{ date('F') == $month ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                            <input type="number" name="year" value="{{ date('Y') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Online Payment Fields -->
                        <div id="online_payment_fields" class="md:col-span-2 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-blue-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction ID</label>
                                    <input type="text" name="transaction_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white">
                                </div>
                            </div>
                        </div>

                        <!-- Cheque Fields -->
                        <div id="cheque_fields" class="md:col-span-2 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-yellow-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cheque Number</label>
                                    <input type="text" name="cheque_number"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cheque Date</label>
                                    <input type="date" name="cheque_date"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bank Name</label>
                                    <input type="text" name="bank_name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white">
                                </div>
                            </div>
                        </div>

                        <!-- Scholarship -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Applied Scholarship (Optional)</label>
                            <select name="scholarship_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700">
                                <option value="">No Scholarship</option>
                                @foreach($scholarships as $scholarship)
                                    <option value="{{ $scholarship->id }}">
                                        {{ $scholarship->name }} - {{ $scholarship->discount_type == 'percentage' ? $scholarship->discount_value . '%' : '৳' . $scholarship->discount_value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Remarks -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks</label>
                            <textarea name="remarks" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">{{ old('remarks') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('fee-collections.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Collect Fee & Generate Receipt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill fee amount when fee structure is selected
        document.getElementById('fee_structure_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const amount = selectedOption.getAttribute('data-amount');
            if (amount) {
                document.getElementById('fee_amount').value = amount;
                calculatePaidAmount();
            }
        });

        // Calculate paid amount automatically
        function calculatePaidAmount() {
            const feeAmount = parseFloat(document.getElementById('fee_amount').value) || 0;
            const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
            const fine = parseFloat(document.getElementById('fine_amount').value) || 0;
            const paidAmount = feeAmount - discount + fine;
            document.getElementById('paid_amount').value = paidAmount.toFixed(2);
        }

        document.getElementById('fee_amount').addEventListener('input', calculatePaidAmount);
        document.getElementById('discount_amount').addEventListener('input', calculatePaidAmount);
        document.getElementById('fine_amount').addEventListener('input', calculatePaidAmount);

        // Show/hide payment method specific fields
        document.getElementById('payment_method_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const code = selectedOption.getAttribute('data-code');
            
            document.getElementById('online_payment_fields').classList.add('hidden');
            document.getElementById('cheque_fields').classList.add('hidden');
            
            if (code === 'bkash' || code === 'nagad' || code === 'card') {
                document.getElementById('online_payment_fields').classList.remove('hidden');
            } else if (code === 'cheque') {
                document.getElementById('cheque_fields').classList.remove('hidden');
            }
        });
    </script>
@endsection
