@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Profile</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View and update your personal information</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="text-center">
                    @if(Auth::user()->avatar)
                        <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Profile" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-200 dark:border-gray-600">
                    @else
                        <div class="w-32 h-32 rounded-full mx-auto bg-blue-600 flex items-center justify-center text-white text-4xl font-bold">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}
                        </div>
                    @endif
                    <h2 class="mt-4 text-xl font-semibold text-gray-800 dark:text-white">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $student->roll_number }}</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                        {{ $student->class->name ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Information -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">First Name</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $student->first_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">Last Name</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $student->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">Email</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $student->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">Date of Birth</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $student->date_of_birth ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">Gender</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ ucfirst($student->gender ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">Blood Group</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $student->blood_group ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">Religion</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $student->religion ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-400">Admission Date</label>
                        <p class="text-gray-800 dark:text-white font-medium">{{ $student->admission_date ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Editable Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Update Contact Information</h3>
                <form action="{{ route('student.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Parent Phone</label>
                            <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('parent_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Blood Group</label>
                        <select name="blood_group" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
