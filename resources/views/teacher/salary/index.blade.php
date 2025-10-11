@extends('layouts.admin')

@section('title', 'Salary Slips')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Salary Slips</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View and download your salary slips</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Month/Year</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Basic Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Allowances</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Deductions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Net Pay</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($salarySlips ?? [] as $slip)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $slip->month }} {{ $slip->year }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${{ number_format($slip->basic_salary, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-green-600 dark:text-green-400">${{ number_format($slip->allowances, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-red-600 dark:text-red-400">${{ number_format($slip->deductions, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($slip->net_pay, 2) }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <a href="{{ route('teacher.salary.slip', $slip->id) }}" class="text-blue-600 hover:text-blue-700 mr-3">View</a>
                        <a href="{{ route('teacher.salary.slip', $slip->id) }}?download=1" class="text-green-600 hover:text-green-700">Download</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No salary slips available yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
