@extends('layouts.admin')

@section('title', 'Fee Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('parent.dashboard') }}" class="text-blue-600 hover:underline">← Back to Dashboard</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Fee Invoices & Payments</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $child->user->name ?? 'Student' }}</p>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <div class="text-blue-600 dark:text-blue-400 text-sm font-medium">Total Amount</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white mt-2">${{ number_format($summary['total'], 2) }}</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                <div class="text-green-600 dark:text-green-400 text-sm font-medium">Amount Paid</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white mt-2">${{ number_format($summary['paid'], 2) }}</div>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                <div class="text-red-600 dark:text-red-400 text-sm font-medium">Pending Amount</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white mt-2">${{ number_format($summary['pending'], 2) }}</div>
            </div>
        </div>

        <!-- Fee Invoices -->
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Fee Invoices</h2>
        <div class="overflow-x-auto mb-8">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $invoice->feeCategory->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">${{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($invoice->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->status !== 'paid')
                            <form action="{{ route('parent.pay-online', $invoice->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Pay Now</button>
                            </form>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No invoices found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Payment History -->
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Payment History</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Transaction ID</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payments as $payment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payment->feeInvoice->invoice_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-green-600 font-semibold">${{ number_format($payment->amount_paid, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white capitalize">{{ $payment->payment_method }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->transaction_id ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No payment history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
