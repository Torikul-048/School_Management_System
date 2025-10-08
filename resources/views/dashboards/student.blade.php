<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">You are logged in as <span class="font-semibold text-blue-600">Student</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-blue-100 text-sm">Attendance</p>
                    <h3 class="text-3xl font-bold mt-2">0%</h3>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-green-100 text-sm">Average Grade</p>
                    <h3 class="text-3xl font-bold mt-2">N/A</h3>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-purple-100 text-sm">Pending Fees</p>
                    <h3 class="text-3xl font-bold mt-2">$0</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="#" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100">
                            <span class="text-sm font-medium text-gray-700">View Attendance</span>
                        </a>
                        <a href="#" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100">
                            <span class="text-sm font-medium text-gray-700">View Results</span>
                        </a>
                        <a href="#" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100">
                            <span class="text-sm font-medium text-gray-700">Class Schedule</span>
                        </a>
                        <a href="#" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100">
                            <span class="text-sm font-medium text-gray-700">Library</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
