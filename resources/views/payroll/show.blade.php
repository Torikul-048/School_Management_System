<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Payroll Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('payroll.salary-slip', $payroll) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    View Salary Slip
                </a>
                <a href="{{ route('payroll.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Employee Info -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="flex items-center mb-4">
                    @if($payroll->teacher->photo)
                        <img src="{{ Storage::url($payroll->teacher->photo) }}" alt="{{ $payroll->teacher->full_name }}" class="w-20 h-20 rounded-full object-cover mr-4">
                    @else
                        <div class="w-20 h-20 rounded-full bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold mr-4">
                            {{ substr($payroll->teacher->first_name, 0, 1) }}{{ substr($payroll->teacher->last_name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $payroll->teacher->full_name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payroll->teacher->employee_id }} • {{ $payroll->teacher->designation }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payroll->teacher->department }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Payroll Month:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100 ml-2">{{ \Carbon\Carbon::parse($payroll->month)->format('F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                        @if($payroll->status == 'paid')
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                        @elseif($payroll->status == 'processed')
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Processed</span>
                        @else
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Salary Breakdown</h3>
                
                <!-- Basic Salary -->
                <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-gray-700 dark:text-gray-300">Basic Salary</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">৳{{ number_format($payroll->basic_salary, 2) }}</span>
                </div>

                <!-- Allowances -->
                @if($payroll->items->where('type', 'allowance')->count() > 0)
                <div class="mt-4">
                    <h4 class="text-md font-semibold text-green-600 mb-2">Allowances</h4>
                    @foreach($payroll->items->where('type', 'allowance') as $item)
                    <div class="flex justify-between py-2 pl-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $item->component_name }}</span>
                        <span class="text-sm text-green-600 font-semibold">+৳{{ number_format($item->amount, 2) }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between py-2 pl-4 border-t border-gray-200 dark:border-gray-700 mt-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total Allowances</span>
                        <span class="text-sm font-bold text-green-600">+৳{{ number_format($payroll->total_allowances, 2) }}</span>
                    </div>
                </div>
                @endif

                <!-- Deductions -->
                @if($payroll->items->where('type', 'deduction')->count() > 0)
                <div class="mt-4">
                    <h4 class="text-md font-semibold text-red-600 mb-2">Deductions</h4>
                    @foreach($payroll->items->where('type', 'deduction') as $item)
                    <div class="flex justify-between py-2 pl-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $item->component_name }}</span>
                        <span class="text-sm text-red-600 font-semibold">-৳{{ number_format($item->amount, 2) }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between py-2 pl-4 border-t border-gray-200 dark:border-gray-700 mt-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total Deductions</span>
                        <span class="text-sm font-bold text-red-600">-৳{{ number_format($payroll->total_deductions, 2) }}</span>
                    </div>
                </div>
                @endif

                <!-- Net Salary -->
                <div class="flex justify-between py-4 mt-4 border-t-2 border-indigo-600">
                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Net Salary</span>
                    <span class="text-2xl font-bold text-indigo-600">৳{{ number_format($payroll->net_salary, 2) }}</span>
                </div>
            </div>

            <!-- Remarks -->
            @if($payroll->remarks)
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Remarks</h3>
                <p class="text-gray-700 dark:text-gray-300">{{ $payroll->remarks }}</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
