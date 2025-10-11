@extends('layouts.admin')

@section('title', 'Financial Reports')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Financial Reports</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Generate and view financial reports</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Income Report -->
                <a href="{{ route('finance.reports.income') }}" class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-chart-line text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">View</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Income Report</h3>
                    <p class="text-sm opacity-90">View detailed income reports with fee collections analysis</p>
                </a>

                <!-- Expense Report -->
                <a href="{{ route('finance.reports.expenses') }}" class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-chart-pie text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">View</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Expense Report</h3>
                    <p class="text-sm opacity-90">Track all expenses by category and period</p>
                </a>

                <!-- Balance Sheet -->
                <a href="{{ route('finance.reports.balance') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-balance-scale text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">View</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Balance Sheet</h3>
                    <p class="text-sm opacity-90">View income vs expenses balance report</p>
                </a>

                <!-- Student Ledger -->
                <a href="{{ route('finance.reports.student-ledger') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-user-graduate text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">View</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Student Ledger</h3>
                    <p class="text-sm opacity-90">View individual student fee payment history</p>
                </a>

                <!-- Daily Collection -->
                <a href="{{ route('finance.reports.daily-collection') }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-calendar-day text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">View</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Daily Collection</h3>
                    <p class="text-sm opacity-90">View daily fee collection summary</p>
                </a>

                <!-- Payment Methods Analysis -->
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <i class="fas fa-credit-card text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">Coming Soon</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Payment Methods</h3>
                    <p class="text-sm opacity-90">Analyze payments by method (Cash, bKash, Nagad, etc.)</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Income (This Month)</div>
                    <div class="text-2xl font-bold text-green-600">৳0.00</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Expenses (This Month)</div>
                    <div class="text-2xl font-bold text-red-600">৳0.00</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Net Balance</div>
                    <div class="text-2xl font-bold text-blue-600">৳0.00</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pending Invoices</div>
                    <div class="text-2xl font-bold text-yellow-600">0</div>
                </div>
            </div>
    </div>
@endsection
