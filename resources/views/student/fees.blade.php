@extends('layouts.admin')

@section('title', 'Fee Payments')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Fee Payments</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View your fee invoices and payment history</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg p-6">
            <p class="text-sm text-red-600 dark:text-red-400">Total Due</p>
            <p class="text-3xl font-bold text-red-700 dark:text-red-300">৳{{ number_format($totalDue, 2) }}</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-lg p-6">
            <p class="text-sm text-green-600 dark:text-green-400">Total Paid</p>
            <p class="text-3xl font-bold text-green-700 dark:text-green-300">৳{{ number_format($totalPaid, 2) }}</p>
        </div>
    </div>

    <!-- Invoices -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Fee Invoices</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Invoice No</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Due Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($feeInvoices as $invoice)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">{{ $invoice->invoice_number }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">৳{{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">No invoices found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment History -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Payment History</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Transaction ID</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Amount</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Method</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">{{ $payment->transaction_id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">৳{{ number_format($payment->amount_paid, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-white">{{ ucfirst($payment->payment_method) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
