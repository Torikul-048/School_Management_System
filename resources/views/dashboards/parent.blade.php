<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Parent Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">You are logged in as <span class="font-semibold text-indigo-600">Parent</span></p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">My Children</h3>
                    <p class="text-gray-500 text-center py-8">No children registered yet.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
