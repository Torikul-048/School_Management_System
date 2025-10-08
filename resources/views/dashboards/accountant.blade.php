<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accountant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">You are logged in as <span class="font-semibold text-green-600">Accountant</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-green-100 text-sm">Total Revenue</p>
                    <h3 class="text-3xl font-bold mt-2">$0</h3>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-red-100 text-sm">Total Expenses</p>
                    <h3 class="text-3xl font-bold mt-2">$0</h3>
                </div>
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-yellow-100 text-sm">Pending Fees</p>
                    <h3 class="text-3xl font-bold mt-2">$0</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Overview</h3>
                    <p class="text-gray-500 text-center py-8">No financial data available.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
