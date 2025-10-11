@extends('layouts.admin')

@section('title', 'Profile Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Profile Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Manage your account information and preferences</p>
    </div>

    <div class="max-w-4xl space-y-6">
        <!-- Update Profile Information -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Profile Information</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update your account's profile information and email address.</p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Update Password</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Delete Account</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
