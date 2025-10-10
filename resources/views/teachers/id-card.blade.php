<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('ID Card - ') }} {{ $teacher->full_name }}
            </h2>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Print Card
                </button>
                <a href="{{ route('teachers.show', $teacher) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Back to Profile
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- ID Card Front -->
            <div class="mb-8 id-card">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-4 border-indigo-600" style="width: 400px; height: 250px; margin: 0 auto;">
                    <div class="bg-indigo-600 text-white p-4 text-center">
                        <h3 class="text-lg font-bold">{{ config('app.name', 'School Name') }}</h3>
                        <p class="text-xs">Faculty ID Card</p>
                    </div>
                    
                    <div class="p-4 flex">
                        <div class="mr-4">
                            @if($teacher->photo)
                                <img src="{{ Storage::url($teacher->photo) }}" alt="{{ $teacher->full_name }}" class="w-24 h-24 rounded object-cover border-2 border-indigo-600">
                            @else
                                <div class="w-24 h-24 rounded bg-gray-300 flex items-center justify-center border-2 border-indigo-600">
                                    <span class="text-3xl text-gray-600">{{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">{{ $teacher->full_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $teacher->designation }}</p>
                            <p class="text-sm text-gray-600">{{ $teacher->department }}</p>
                            <div class="mt-2 text-xs">
                                <p><strong>ID:</strong> {{ $teacher->employee_id }}</p>
                                <p><strong>Blood:</strong> {{ $teacher->blood_group ?? 'N/A' }}</p>
                                <p><strong>Phone:</strong> {{ $teacher->phone }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-100 px-4 py-2 text-center">
                        <p class="text-xs text-gray-600">Valid until: {{ now()->addYear()->format('M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- ID Card Back -->
            <div class="id-card">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-4 border-indigo-600" style="width: 400px; height: 250px; margin: 0 auto;">
                    <div class="bg-indigo-600 text-white p-4 text-center">
                        <h3 class="text-lg font-bold">Important Information</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Emergency Contact:</p>
                            <p class="text-xs text-gray-600">{{ $teacher->emergency_contact_name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-600">{{ $teacher->emergency_contact_phone ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-700 mb-2">Address:</p>
                            <p class="text-xs text-gray-600">{{ $teacher->address }}</p>
                            <p class="text-xs text-gray-600">{{ $teacher->city }}, {{ $teacher->state }} {{ $teacher->zip_code }}</p>
                        </div>
                        
                        <div class="text-center mt-4">
                            <svg class="mx-auto" width="100" height="30"></svg>
                            <p class="text-xs text-gray-500 mt-1">{{ $teacher->employee_id }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-100 px-4 py-2 text-center">
                        <p class="text-xs text-gray-600">If found, please return to school office</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .id-card, .id-card * {
                visibility: visible;
            }
            .id-card {
                position: absolute;
                left: 0;
                top: 0;
                page-break-after: always;
            }
        }
    </style>
</x-app-layout>
