<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - School Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .credential-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .credential-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl grid md:grid-cols-2 gap-8">
        <!-- Left Side - Login Form -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl mb-4">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back!</h1>
                <p class="text-gray-600">School Management System</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus 
                            autocomplete="username"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="admin@school.com"
                        >
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="Enter your password"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                            <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-slash-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transform hover:scale-[1.02] transition-all shadow-lg">
                    Sign In
                </button>
            </form>
        </div>

        <!-- Right Side - Demo Credentials -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">🔐 Demo Credentials</h2>
                <p class="text-gray-600">Click any credential below to auto-fill the login form</p>
            </div>

            <div class="space-y-3">
                <!-- Admin -->
                <div class="credential-card bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-4 rounded-lg" onclick="fillCredentials('admin@school.com', 'password123')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">School Admin</p>
                            <p class="text-sm text-gray-600">admin@school.com</p>
                        </div>
                        <div class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Full Access
                        </div>
                    </div>
                </div>

                <!-- Teacher -->
                <div class="credential-card bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 p-4 rounded-lg" onclick="fillCredentials('teacher@school.com', 'password123')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Teacher</p>
                            <p class="text-sm text-gray-600">teacher@school.com</p>
                        </div>
                        <div class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Teacher
                        </div>
                    </div>
                </div>

                <!-- Student -->
                <div class="credential-card bg-gradient-to-r from-purple-50 to-purple-100 border-l-4 border-purple-500 p-4 rounded-lg" onclick="fillCredentials('student@school.com', 'password123')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Student</p>
                            <p class="text-sm text-gray-600">student@school.com</p>
                        </div>
                        <div class="bg-purple-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Student
                        </div>
                    </div>
                </div>

                <!-- Parent -->
                <div class="credential-card bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-500 p-4 rounded-lg" onclick="fillCredentials('parent@school.com', 'password123')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Parent</p>
                            <p class="text-sm text-gray-600">parent@school.com</p>
                        </div>
                        <div class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Parent
                        </div>
                    </div>
                </div>

                <!-- Accountant -->
                <div class="credential-card bg-gradient-to-r from-indigo-50 to-indigo-100 border-l-4 border-indigo-500 p-4 rounded-lg" onclick="fillCredentials('accountant@school.com', 'password123')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Accountant</p>
                            <p class="text-sm text-gray-600">accountant@school.com</p>
                        </div>
                        <div class="bg-indigo-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Accountant
                        </div>
                    </div>
                </div>

                <!-- Librarian -->
                <div class="credential-card bg-gradient-to-r from-pink-50 to-pink-100 border-l-4 border-pink-500 p-4 rounded-lg" onclick="fillCredentials('librarian@school.com', 'password123')">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Librarian</p>
                            <p class="text-sm text-gray-600">librarian@school.com</p>
                        </div>
                        <div class="bg-pink-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Librarian
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-lg">
                <p class="text-sm text-amber-800">
                    <span class="font-semibold">🔑 Default Password:</span> password123
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('email').focus();
        }
    </script>
</body>
</html>
