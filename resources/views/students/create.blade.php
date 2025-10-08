@extends('layouts.admin')

@section('title', 'Add New Student')

@section('content')
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
            <a href="{{ route('students.index') }}" class="hover:text-blue-600">Students</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span>Add New Student</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Student Admission Form</h1>
        <p class="text-gray-600 mt-1">Fill in the student information to complete admission</p>
    </div>

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Personal Information -->
        <x-card class="mb-6">
            <x-slot name="title">Personal Information</x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-form.input 
                    label="First Name *" 
                    name="first_name" 
                    :value="old('first_name')" 
                    required 
                />
                
                <x-form.input 
                    label="Last Name *" 
                    name="last_name" 
                    :value="old('last_name')" 
                    required 
                />
                
                <x-form.input 
                    label="Date of Birth *" 
                    name="date_of_birth" 
                    type="date" 
                    :value="old('date_of_birth')" 
                    required 
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <x-form.select label="Gender *" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </x-form.select>

                <x-form.select label="Blood Group" name="blood_group">
                    <option value="">Select Blood Group</option>
                    <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                    <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                </x-form.select>

                <x-form.input 
                    label="Religion" 
                    name="religion" 
                    :value="old('religion')" 
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <x-form.input 
                    label="Caste" 
                    name="caste" 
                    :value="old('caste')" 
                />

                <x-form.input 
                    label="Category" 
                    name="category" 
                    placeholder="e.g., General, OBC, SC, ST" 
                    :value="old('category')" 
                />

                <x-form.input 
                    label="Mother Tongue" 
                    name="mother_tongue" 
                    :value="old('mother_tongue')" 
                />
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Student Photo</label>
                <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-sm text-gray-500">Upload student photo (Max: 2MB, Format: JPG, PNG)</p>
            </div>
        </x-card>

        <!-- Contact Information -->
        <x-card class="mb-6">
            <x-slot name="title">Contact Information</x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input 
                    label="Email Address *" 
                    name="email" 
                    type="email" 
                    :value="old('email')" 
                    required 
                />
                
                <x-form.input 
                    label="Phone Number *" 
                    name="phone" 
                    :value="old('phone')" 
                    required 
                />
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                <textarea name="address" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <x-form.input 
                    label="City *" 
                    name="city" 
                    :value="old('city')" 
                    required 
                />
                
                <x-form.input 
                    label="State *" 
                    name="state" 
                    :value="old('state')" 
                    required 
                />
                
                <x-form.input 
                    label="Country *" 
                    name="country" 
                    :value="old('country', 'India')" 
                    required 
                />
                
                <x-form.input 
                    label="Postal Code *" 
                    name="postal_code" 
                    :value="old('postal_code')" 
                    required 
                />
            </div>
        </x-card>

        <!-- Academic Information -->
        <x-card class="mb-6">
            <x-slot name="title">Academic Information</x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-form.select label="Class *" name="class_id" required>
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.select label="Section *" name="section_id" required>
                    <option value="">Select Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.select label="Academic Year *" name="academic_year_id" required>
                    <option value="">Select Academic Year</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </x-form.select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-form.input 
                    label="Admission Date *" 
                    name="admission_date" 
                    type="date" 
                    :value="old('admission_date', date('Y-m-d'))" 
                    required 
                />

                <x-form.input 
                    label="Previous School" 
                    name="previous_school" 
                    :value="old('previous_school')" 
                />
            </div>
        </x-card>

        <!-- Guardian Information -->
        <x-card class="mb-6">
            <x-slot name="title">Guardian/Parent Information</x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input 
                    label="Guardian Name *" 
                    name="guardian_name" 
                    :value="old('guardian_name')" 
                    required 
                />
                
                <x-form.select label="Relation *" name="guardian_relation" required>
                    <option value="">Select Relation</option>
                    <option value="Father" {{ old('guardian_relation') == 'Father' ? 'selected' : '' }}>Father</option>
                    <option value="Mother" {{ old('guardian_relation') == 'Mother' ? 'selected' : '' }}>Mother</option>
                    <option value="Guardian" {{ old('guardian_relation') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                    <option value="Other" {{ old('guardian_relation') == 'Other' ? 'selected' : '' }}>Other</option>
                </x-form.select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-form.input 
                    label="Guardian Phone *" 
                    name="guardian_phone" 
                    :value="old('guardian_phone')" 
                    required 
                />
                
                <x-form.input 
                    label="Guardian Email" 
                    name="guardian_email" 
                    type="email" 
                    :value="old('guardian_email')" 
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <x-form.input 
                    label="Guardian Occupation" 
                    name="guardian_occupation" 
                    :value="old('guardian_occupation')" 
                />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guardian Address</label>
                    <textarea name="guardian_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('guardian_address') }}</textarea>
                </div>
            </div>
        </x-card>

        <!-- Documents -->
        <x-card class="mb-6">
            <x-slot name="title">Documents Upload</x-slot>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Birth Certificate</label>
                    <input type="file" name="birth_certificate" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">Upload birth certificate (Max: 5MB)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transfer Certificate</label>
                    <input type="file" name="transfer_certificate" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">Upload transfer certificate (Max: 5MB)</p>
                </div>
            </div>
        </x-card>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('students.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <x-button type="submit" color="primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Admit Student
            </x-button>
        </div>
    </form>
@endsection
