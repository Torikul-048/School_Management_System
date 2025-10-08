<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Librarian Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">You are logged in as <span class="font-semibold text-purple-600">Librarian</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-blue-100 text-sm">Total Books</p>
                    <h3 class="text-3xl font-bold mt-2">0</h3>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-purple-100 text-sm">Books Issued</p>
                    <h3 class="text-3xl font-bold mt-2">0</h3>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-red-100 text-sm">Overdue Books</p>
                    <h3 class="text-3xl font-bold mt-2">0</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Library Management</h3>
                    <p class="text-gray-500 text-center py-8">No books in the library yet.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
