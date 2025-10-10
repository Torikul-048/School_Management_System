<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Payroll Management') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('payroll.salary-structure') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Salary Structure
                </a>
                <a href="{{ route('payroll.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Process Payroll
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Payroll</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Processed</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['processed'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Pending</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Amount</div>
                    <div class="text-2xl font-bold text-indigo-600">₹{{ number_format($stats['total_amount'], 2) }}</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('payroll.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Employee ID or Name" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                        <input type="month" name="month" value="{{ request('month') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                        <select name="department" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">All Departments</option>
                            <option value="Science">Science</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="English">English</option>
                            <option value="Social Studies">Social Studies</option>
                            <option value="Arts">Arts</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Payroll Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Month</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Basic Salary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Allowances</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Deductions</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Net Salary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($payrolls as $payroll)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payroll->teacher->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $payroll->teacher->employee_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ \Carbon\Carbon::parse($payroll->month)->format('M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        ₹{{ number_format($payroll->basic_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                        +₹{{ number_format($payroll->total_allowances, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                        -₹{{ number_format($payroll->total_deductions, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                        ₹{{ number_format($payroll->net_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payroll->status == 'paid')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                        @elseif($payroll->status == 'processed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Processed</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('payroll.show', $payroll) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">View</a>
                                        <a href="{{ route('payroll.salary-slip', $payroll) }}" class="text-green-600 hover:text-green-900 dark:text-green-400">Slip</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No payroll records found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $payrolls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
