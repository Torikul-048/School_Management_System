<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Successful - School Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <!-- Success Message -->
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Application Submitted!</h2>
                <p class="text-gray-600 mb-6">
                    Your admission application has been successfully submitted. 
                </p>

                <!-- Admission Number -->
                @if(session('admission_number'))
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600 mb-1">Your Admission Number</p>
                    <p class="text-2xl font-bold text-blue-600">{{ session('admission_number') }}</p>
                    <p class="text-xs text-gray-500 mt-2">Please save this number for future reference</p>
                </div>
                @endif

                <!-- Next Steps -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 text-left">
                    <h3 class="font-semibold text-yellow-900 mb-2">📋 What's Next?</h3>
                    <ul class="text-sm text-yellow-800 space-y-1">
                        <li>✓ Your application is under review</li>
                        <li>✓ You will receive an email notification</li>
                        <li>✓ Admin will contact you within 2-3 business days</li>
                        <li>✓ Keep checking your email regularly</li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <a href="{{ url('/') }}" 
                        class="block w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition font-medium">
                        Back to Home
                    </a>
                    <a href="{{ route('admissions.apply') }}" 
                        class="block w-full px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:border-gray-400 transition font-medium">
                        Submit Another Application
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
