<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Management System - Excellence in Education</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.svg') }}">
    <meta name="msapplication-TileImage" content="{{ asset('images/logo.svg') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes scroll-left {
            0% { transform: translateX(35%); }
            100% { transform: translateX(-100%); }
        }
        .animate-scroll {
            animation: scroll-left 40s linear infinite;
            display: inline-block;
            will-change: transform;
        }
        .animate-scroll:hover {
            animation-play-state: paused;
        }
        
        /* Custom Scrollbar Styles */
        .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    
    <!-- News Ticker -->
    <div class="bg-blue-700 text-white py-2.5 overflow-hidden">
        <div class="flex items-center">
            <div class="bg-red-600 px-6 py-1.5 font-bold text-sm uppercase flex-shrink-0">
                <span class="inline-block">📢 NEWS</span>
            </div>
            <div class="flex-1 overflow-hidden ml-4">
                <div class="animate-scroll whitespace-nowrap">
                    @if(isset($latestNews) && $latestNews->count() > 0)
                        @foreach($latestNews as $news)
                            <span class="inline-block">{{ $news->title }} • </span>
                        @endforeach
                        {{-- Repeat for continuous scroll --}}
                        @foreach($latestNews as $news)
                            <span class="inline-block">{{ $news->title }} • </span>
                        @endforeach
                    @else
                        <span class="inline-block">Welcome to School Management System - Excellence in Education • Admissions Open for Class 1 to Class 10 (Academic Year 2024-25) • Annual Sports Day on December 20th • Parent-Teacher Meeting Next Week • New Computer Lab Inaugurated • Science Fair Registration Open • Excellence in Education is the foundation of success • </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-gray-50">
        
        <!-- Navigation -->
        <nav class="bg-white shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.svg') }}" alt="School Logo" class="w-14 h-14 rounded-lg shadow-lg">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 uppercase tracking-wide">School Management System</h1>
                            <p class="text-xs text-gray-600 font-medium">Excellence in Education</p>
                        </div>
                    </div>
                    
                    <!-- Desktop Navigation Links -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <a href="#home" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Home</a>
                        <a href="#about" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">About Us</a>
                        <a href="#academics" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Academics</a>
                        <a href="#admission" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Admission</a>
                        <a href="#gallery" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Gallery</a>
                        <a href="#notice" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Notice Board</a>
                        <a href="#contact" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Contact Us</a>
                    </div>
                    
                    <!-- Login Button & Mobile Menu Toggle -->
                    <div class="flex items-center space-x-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:shadow-lg transition-all">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-lg hover:shadow-lg transition-all">
                                    Login
                                </a>
                            @endauth
                        @endif
                        
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-button" class="lg:hidden p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Navigation Menu -->
                <div id="mobile-menu" class="hidden lg:hidden pb-4">
                    <div class="flex flex-col space-y-2">
                        <a href="#home" class="text-gray-700 hover:text-blue-600 hover:bg-gray-100 font-medium px-4 py-2 rounded-md transition-colors">Home</a>
                        <a href="#about" class="text-gray-700 hover:text-blue-600 hover:bg-gray-100 font-medium px-4 py-2 rounded-md transition-colors">About Us</a>
                        <a href="#academics" class="text-gray-700 hover:text-blue-600 hover:bg-gray-100 font-medium px-4 py-2 rounded-md transition-colors">Academics</a>
                        <a href="#admission" class="text-gray-700 hover:text-blue-600 hover:bg-gray-100 font-medium px-4 py-2 rounded-md transition-colors">Admission</a>
                        <a href="#gallery" class="text-gray-700 hover:text-blue-600 hover:bg-gray-100 font-medium px-4 py-2 rounded-md transition-colors">Gallery</a>
                        <a href="#notice" class="text-gray-700 hover:text-blue-600 hover:bg-gray-100 font-medium px-4 py-2 rounded-md transition-colors">Notice Board</a>
                        <a href="#contact" class="text-gray-700 hover:text-blue-600 hover:bg-gray-100 font-medium px-4 py-2 rounded-md transition-colors">Contact Us</a>
                    </div>
                </div>
            </div>
        </nav>
        
        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-button').addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            });
            
            // Close mobile menu when clicking on a link
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    document.getElementById('mobile-menu').classList.add('hidden');
                });
            });
        </script>

        <!-- Hero Section with Building Image -->
        <section id="home" class="relative h-[500px] bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=2048&auto=format&fit=crop');">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white px-4">
                    <h1 class="text-5xl md:text-6xl font-bold mb-3 drop-shadow-2xl">MY SCHOOL</h1>
                    <p class="text-2xl md:text-3xl mb-4 drop-shadow-lg">Excellence in Education</p>
                    <div class="h-1 w-48 bg-gradient-to-r from-yellow-400 to-orange-500 mx-auto my-4"></div>
                    <p class="text-lg md:text-xl drop-shadow-lg">Nurturing Young Minds from Class 1 to Class 10</p>
                </div>
            </div>
        </section>

        <!-- Latest News & Upcoming Events Section -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 gap-6">
                    
                    <!-- Latest News -->
                    <div class="lg:col-span-1 flex flex-col">
                        <div class="bg-blue-600 text-white px-5 py-3 rounded-t-lg">
                            <h2 class="text-xl font-bold">Latest News</h2>
                        </div>
                        <div class="bg-white border-x border-b border-gray-200 rounded-b-lg shadow-md flex-1 flex flex-col">
                            <div class="divide-y divide-gray-200 overflow-y-auto max-h-96" style="scrollbar-width: thin; scrollbar-color: #3b82f6 #e5e7eb;">
                                @forelse($latestNews as $news)
                                    <a href="#" class="block p-4 hover:bg-blue-50 transition">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 bg-blue-600 text-white rounded-lg w-12 h-12 flex items-center justify-center font-bold">
                                                <div class="text-center leading-tight">
                                                    <div class="text-xs">{{ $news->date->format('M') }}</div>
                                                    <div class="text-lg">{{ $news->date->format('d') }}</div>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-900 font-medium">{{ $news->title }}</p>
                                                <span class="text-xs text-orange-600">⏱ {{ $news->time_ago }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                        <p class="mt-2 text-sm">No news available</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="lg:col-span-2 flex flex-col">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-3 rounded-t-lg">
                            <h2 class="text-xl font-bold">Upcoming Events</h2>
                        </div>
                        <div class="bg-white border-x border-b border-gray-200 rounded-b-lg shadow-md flex-1">
                            <div class="divide-y divide-gray-200">
                                <div class="p-4 hover:bg-blue-50 transition cursor-pointer">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center">
                                            <div class="text-center leading-tight">
                                                <div class="text-xs font-semibold">APR</div>
                                                <div class="text-xl font-bold">28</div>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 text-sm mb-1">Parent-Teacher Meeting (Class 1-5)</h3>
                                            <p class="text-xs text-gray-600">Discussion about student progress and academic performance for primary classes.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 hover:bg-blue-50 transition cursor-pointer">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 bg-blue-600 text-white rounded-lg w-14 h-14 flex items-center justify-center">
                                            <div class="text-center leading-tight">
                                                <div class="text-xs font-semibold">APR</div>
                                                <div class="text-xl font-bold">30</div>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 text-sm mb-1">Inter-Class Quiz Competition</h3>
                                            <p class="text-xs text-gray-600">Academic quiz competition for Class 6 to Class 10 students.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 hover:bg-blue-50 transition cursor-pointer">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 bg-purple-600 text-white rounded-lg w-14 h-14 flex items-center justify-center">
                                            <div class="text-center leading-tight">
                                                <div class="text-xs font-semibold">MAY</div>
                                                <div class="text-xl font-bold">02</div>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 text-sm mb-1">Annual Cultural Fest 2025</h3>
                                            <p class="text-xs text-gray-600">Students showcase their talents in music, dance, drama, and art.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 hover:bg-blue-50 transition cursor-pointer">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 bg-green-600 text-white rounded-lg w-14 h-14 flex items-center justify-center">
                                            <div class="text-center leading-tight">
                                                <div class="text-xs font-semibold">MAY</div>
                                                <div class="text-xl font-bold">15</div>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 text-sm mb-1">Field Trip to Science Museum (Class 8-10)</h3>
                                            <p class="text-xs text-gray-600">Educational field trip to enhance scientific knowledge and practical learning.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- About Us Section -->
        <section id="about" class="py-16 bg-gradient-to-b from-blue-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">About Our School</h2>
                    <div class="h-1 w-24 bg-blue-600 mx-auto"></div>
                </div>

                <!-- Introduction -->
                <div class="grid lg:grid-cols-2 gap-8 items-center mb-16">
                    <div>
                        <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?q=80&w=800&auto=format&fit=crop" alt="School Building" class="rounded-lg shadow-xl">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Welcome to My School</h3>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Established in 2010, My School has been a beacon of quality education, nurturing young minds from Class 1 to Class 10. We are committed to providing holistic education that balances academic excellence with character development, creativity, and critical thinking.
                        </p>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Our school is equipped with modern infrastructure including smart classrooms, well-equipped science laboratories, a comprehensive library, sports facilities, and a computer lab with the latest technology. We believe in creating an environment where every student can discover their potential and excel.
                        </p>
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Quality Education</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Experienced Faculty</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Our Mission & Vision -->
                <div class="grid md:grid-cols-2 gap-8 mb-16">
                    <div class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-blue-600">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Our Vision</h3>
                        <p class="text-gray-600 leading-relaxed">
                            To be a leading educational institution that empowers students with knowledge, skills, and values to become responsible global citizens and future leaders who contribute positively to society.
                        </p>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-8 border-t-4 border-green-600">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Our Mission</h3>
                        <p class="text-gray-600 leading-relaxed">
                            To provide a nurturing and stimulating learning environment that fosters academic excellence, character building, and all-round development through innovative teaching methods and state-of-the-art facilities.
                        </p>
                    </div>
                </div>

                <!-- Key Achievements -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-6 text-center">Our Key Achievements</h3>
                    <div class="grid md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">15+</div>
                            <p class="text-blue-100">Years of Excellence</p>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">100%</div>
                            <p class="text-blue-100">Board Pass Rate</p>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">25+</div>
                            <p class="text-blue-100">National Awards</p>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold mb-2">500+</div>
                            <p class="text-blue-100">Happy Students</p>
                        </div>
                    </div>
                </div>

                <!-- Core Values -->
                <div class="mt-16">
                    <h3 class="text-2xl font-bold text-center text-gray-900 mb-8">Our Core Values</h3>
                    <div class="grid md:grid-cols-3 lg:grid-cols-5 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-800">Excellence</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-800">Integrity</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-800">Respect</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-800">Innovation</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-800">Responsibility</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Notice Board Section -->
        <section id="notice" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-blue-600 text-white px-5 py-3 rounded-t-lg">
                    <h2 class="text-2xl font-bold">Notice Board</h2>
                </div>
                <div class="bg-white border-x border-b border-gray-200 rounded-b-lg shadow-md p-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        <a href="#" class="flex items-start space-x-3 p-3 border-l-4 border-blue-500 hover:bg-blue-50 transition rounded bg-gray-50">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm">Class 10 Board Exam Time Table 2024-25</h3>
                                <p class="text-xs text-gray-600 mt-1">Important notification for Class 10 students</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-start space-x-3 p-3 border-l-4 border-green-500 hover:bg-green-50 transition rounded bg-gray-50">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm">Inter-School Sports Tournament Registration</h3>
                                <p class="text-xs text-gray-600 mt-1">Register for athletics, basketball, and cricket</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-start space-x-3 p-3 border-l-4 border-purple-500 hover:bg-purple-50 transition rounded bg-gray-50">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm">Admission Form for New Session (Class 1 to 10)</h3>
                                <p class="text-xs text-gray-600 mt-1">Admissions open for academic year 2024-25</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-start space-x-3 p-3 border-l-4 border-yellow-500 hover:bg-yellow-50 transition rounded bg-gray-50">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm">Fee Payment Deadline Extended</h3>
                                <p class="text-xs text-gray-600 mt-1">Last date for quarterly fee payment</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-start space-x-3 p-3 border-l-4 border-red-500 hover:bg-red-50 transition rounded bg-gray-50">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm">Science Fair Project Submission Guidelines</h3>
                                <p class="text-xs text-gray-600 mt-1">Submit your project proposals by next week</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-start space-x-3 p-3 border-l-4 border-indigo-500 hover:bg-indigo-50 transition rounded bg-gray-50">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm">Computer Lab Inauguration - All Classes</h3>
                                <p class="text-xs text-gray-600 mt-1">New computer lab with latest technology</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Academics Section -->
        <section id="academics" class="py-16 bg-gradient-to-b from-white to-blue-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Academics</h2>
                    <div class="h-1 w-24 bg-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-600 max-w-3xl mx-auto">
                        We offer comprehensive education from Class 1 to Class 10 following the NCTB curriculum with a focus on holistic development and academic excellence.
                    </p>
                </div>

                <!-- Curriculum Overview - Primary Level -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Primary Level</h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Class 1-2 -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                                <h3 class="text-xl font-bold">Primary Junior</h3>
                                <p class="text-blue-100 text-sm">Class 1 - Class 2</p>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-500 mb-4 font-semibold">3 Core Subjects:</p>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-sm">Bangla</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-sm">English</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-sm">Mathematics</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Class 3-5 -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                                <h3 class="text-xl font-bold">Primary Senior</h3>
                                <p class="text-green-100 text-sm">Class 3 - Class 5</p>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-500 mb-4 font-semibold">6 Subjects:</p>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-sm">Bangla, English, Mathematics</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-sm">Bangladesh & Global Studies</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-sm">Science</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-sm">Religion & Moral Education</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Junior Secondary Level -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Junior Secondary Level</h3>
                    <div class="max-w-3xl mx-auto">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 text-white">
                                <h3 class="text-xl font-bold">Junior Secondary</h3>
                                <p class="text-purple-100 text-sm">Class 6 - Class 8</p>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-500 mb-4 font-semibold">8 Subjects:</p>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm">Bangla</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm">English</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm">Mathematics</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm">Science</span>
                                        </li>
                                    </ul>
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm">Bangladesh & Global Studies</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm">Religion & Moral Education</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm">ICT</span>
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700 text-sm">Agriculture / Home Science</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Level - Class 9-10 Groups -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Secondary Level (Class 9-10)</h3>
                    <p class="text-center text-gray-600 mb-6">Students can choose from three academic groups based on their interests and career goals</p>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Science Group -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                                <h3 class="text-xl font-bold">Science Group</h3>
                                <p class="text-blue-100 text-sm">For aspiring scientists & engineers</p>
                            </div>
                            <div class="p-6">
                                <p class="text-xs text-gray-500 mb-3 font-semibold uppercase">Compulsory Subjects:</p>
                                <ul class="space-y-2 mb-4">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Bangla, English, Math</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">ICT, Religion, BGS</span>
                                    </li>
                                </ul>
                                <p class="text-xs text-gray-500 mb-3 font-semibold uppercase">Group Subjects:</p>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Physics</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Chemistry</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Biology</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Higher Mathematics</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Humanities/Arts Group -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 text-white">
                                <h3 class="text-xl font-bold">Humanities Group</h3>
                                <p class="text-green-100 text-sm">For future leaders & thinkers</p>
                            </div>
                            <div class="p-6">
                                <p class="text-xs text-gray-500 mb-3 font-semibold uppercase">Compulsory Subjects:</p>
                                <ul class="space-y-2 mb-4">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Bangla, English, Math</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">ICT, Religion, BGS</span>
                                    </li>
                                </ul>
                                <p class="text-xs text-gray-500 mb-3 font-semibold uppercase">Group Subjects:</p>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">History of Bangladesh & World</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Civics & Citizenship</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Geography & Environment</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Economics</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Business Studies Group -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="bg-gradient-to-r from-orange-600 to-orange-700 p-6 text-white">
                                <h3 class="text-xl font-bold">Business Studies</h3>
                                <p class="text-orange-100 text-sm">For future entrepreneurs</p>
                            </div>
                            <div class="p-6">
                                <p class="text-xs text-gray-500 mb-3 font-semibold uppercase">Compulsory Subjects:</p>
                                <ul class="space-y-2 mb-4">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-orange-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Bangla, English, Math</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-orange-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">ICT, Religion, BGS</span>
                                    </li>
                                </ul>
                                <p class="text-xs text-gray-500 mb-3 font-semibold uppercase">Group Subjects:</p>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Accounting</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Finance & Banking</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Business Entrepreneurship</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs">Economics</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teaching Methodology & Features -->
                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                            </svg>
                            Teaching Methodology
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="text-blue-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Interactive and activity-based learning</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Smart classrooms with digital content</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Regular assessments and feedback</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Project-based learning approach</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Individual attention to every student</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Co-Curricular Activities
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <span class="text-green-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Sports (Cricket, Football, Badminton, Athletics)</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Music, Dance, and Drama classes</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Science and Math Olympiads</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Art, Craft, and Robotics clubs</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-green-600 font-bold mr-2">•</span>
                                <span class="text-gray-600">Annual cultural fest and sports day</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Facilities -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-6 text-center">Academic Facilities</h3>
                    <div class="grid md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="font-semibold">Computer Lab</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="font-semibold">Science Lab</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            </div>
                            <p class="font-semibold">Library</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="font-semibold">Smart Classes</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Useful Information Section -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">Useful Information</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg transition hover:border-blue-500">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm">Student Section</h3>
                        </div>
                        <ul class="space-y-1.5 text-sm text-gray-600">
                            <li><a href="{{ route('login') }}" class="hover:text-blue-600 hover:pl-1 transition-all">→ Result</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-blue-600 hover:pl-1 transition-all">→ Pay Fee</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-blue-600 hover:pl-1 transition-all">→ Assignments</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-blue-600 hover:pl-1 transition-all">→ Attendance</a></li>
                        </ul>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg transition hover:border-green-500">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm">Information Link</h3>
                        </div>
                        <ul class="space-y-1.5 text-sm text-gray-600">
                            <li><a href="#" class="hover:text-green-600 hover:pl-1 transition-all">→ News</a></li>
                            <li><a href="#" class="hover:text-green-600 hover:pl-1 transition-all">→ Achievements</a></li>
                            <li><a href="#" class="hover:text-green-600 hover:pl-1 transition-all">→ Events</a></li>
                            <li><a href="#" class="hover:text-green-600 hover:pl-1 transition-all">→ Library</a></li>
                        </ul>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg transition hover:border-purple-500">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm">Online Service</h3>
                        </div>
                        <ul class="space-y-1.5 text-sm text-gray-600">
                            <li><a href="{{ route('login') }}" class="hover:text-purple-600 hover:pl-1 transition-all">→ Online Results</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-purple-600 hover:pl-1 transition-all">→ Time Table</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-purple-600 hover:pl-1 transition-all">→ Syllabus</a></li>
                        </ul>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg transition hover:border-yellow-500">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm">School Certificate</h3>
                        </div>
                        <div class="flex items-center justify-center mt-4">
                            <div class="w-32 h-24 bg-gradient-to-br from-blue-100 to-green-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Admission Section -->
        <section id="admission" class="py-16 bg-gradient-to-b from-blue-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Admissions</h2>
                    <div class="h-1 w-24 bg-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-600 max-w-3xl mx-auto">
                        We welcome students to join our institution for quality education. Admissions are open for Class 1 to Class 10 throughout the academic year.
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-8 mb-12">
                    <!-- Admission Process -->
                    <div class="bg-white rounded-lg shadow-xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Admission Process
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">1</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Submit Application</h4>
                                    <p class="text-gray-600 text-sm">Fill out the admission form with required details and documents</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">2</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Document Verification</h4>
                                    <p class="text-gray-600 text-sm">Admin will verify all submitted documents and credentials</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">3</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Interaction & Assessment</h4>
                                    <p class="text-gray-600 text-sm">Student interaction session and basic assessment (for Class 2+)</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">4</div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Admission Confirmation</h4>
                                    <p class="text-gray-600 text-sm">Receive login credentials and admission confirmation from admin</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Required Documents & Eligibility -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow-xl p-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                Required Documents
                            </h3>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span class="text-gray-600 text-sm">Birth certificate (original & photocopy)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span class="text-gray-600 text-sm">Transfer certificate (if applicable)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span class="text-gray-600 text-sm">Previous class report card</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span class="text-gray-600 text-sm">Aadhar card of student & parents</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span class="text-gray-600 text-sm">Recent passport-size photographs (4)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span class="text-gray-600 text-sm">Address proof (electricity bill/rent agreement)</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-xl p-8 text-white">
                            <h3 class="text-xl font-bold mb-4">Age Criteria</h3>
                            <div class="space-y-2">
                                <p class="text-blue-100 text-sm"><strong>Class 1:</strong> Minimum 6 years (as of March 31)</p>
                                <p class="text-blue-100 text-sm"><strong>Class 2-10:</strong> Age appropriate to class level</p>
                                <p class="text-blue-100 text-sm"><strong>Note:</strong> Previous academic records required for Class 2+</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admission Form CTA -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-2xl p-8 text-center text-white">
                    <h3 class="text-2xl font-bold mb-3">Ready to Apply?</h3>
                    <p class="text-green-100 mb-6 max-w-2xl mx-auto">
                        Fill out our online admission form to start your journey with us. Our administration team will review your application and contact you within 2-3 business days.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('admissions.apply') }}" class="inline-flex items-center px-8 py-4 bg-white text-green-600 font-bold rounded-lg hover:bg-gray-100 transition-all shadow-lg transform hover:scale-105">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            Apply Online Now
                        </a>
                        <a href="tel:+8801712345678" class="inline-flex items-center px-6 py-3 bg-white text-green-600 font-semibold rounded-lg hover:bg-gray-100 transition-all shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            Call Admin Office
                        </a>
                        <a href="mailto:admission@myschool.edu" class="inline-flex items-center px-6 py-3 bg-white text-green-600 font-semibold rounded-lg hover:bg-gray-100 transition-all shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Email for Inquiry
                        </a>
                        @auth
                            @if(auth()->user()->hasRole(['Super Admin', 'Admin']))
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Go to Dashboard (Admin)
                                </a>
                            @endif
                        @endauth
                    </div>
                    <div class="mt-6 pt-6 border-t border-green-500">
                        <p class="text-sm text-green-100">
                            <strong>Note:</strong> Fill the online form and our administration team will review your application. Login credentials will be provided after successful verification and fee payment.
                        </p>
                    </div>
                </div>

                <!-- Fee Structure Info -->
                <div class="mt-12 grid md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Affordable Fee Structure</h4>
                        <p class="text-gray-600 text-sm">Transparent and reasonable fee structure with flexible payment options</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">Scholarship Programs</h4>
                        <p class="text-gray-600 text-sm">Merit-based scholarships available for deserving students</p>
                    </div>
                    <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2">School Tour Available</h4>
                        <p class="text-gray-600 text-sm">Visit our campus and interact with faculty before admission</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gallery Section -->
        <section id="gallery" class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Gallery</h2>
                    <p class="text-gray-600">Capturing moments of excellence and achievement</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=800&auto=format&fit=crop" alt="Annual Day Event" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm">Annual Day Celebration</h3>
                            <p class="text-xs text-gray-600 mt-1">Celebrating student achievements</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=800&auto=format&fit=crop" alt="Event" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm">Cultural Festival 2024</h3>
                            <p class="text-xs text-gray-600 mt-1">Students showcasing talents</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?q=80&w=800&auto=format&fit=crop" alt="Sports" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm">Sports Day Activities</h3>
                            <p class="text-xs text-gray-600 mt-1">Promoting physical fitness</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=800&auto=format&fit=crop" alt="Seminar" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm">Educational Workshop</h3>
                            <p class="text-xs text-gray-600 mt-1">Expert speakers and learning</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=800&auto=format&fit=crop" alt="Science Fair" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm">Science Exhibition</h3>
                            <p class="text-xs text-gray-600 mt-1">Innovation and creativity</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=800&auto=format&fit=crop" alt="Campus" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm">Campus Life</h3>
                            <p class="text-xs text-gray-600 mt-1">Modern facilities & environment</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Student Achievements Section -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">Student Achievements</h2>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Achievement 1 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition border-t-4 border-blue-600">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-blue-600 mb-2">100%</h3>
                        <p class="text-gray-700 font-medium">Board Exam Results</p>
                        <p class="text-sm text-gray-500 mt-1">Class 10 - 2024</p>
                    </div>

                    <!-- Achievement 2 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition border-t-4 border-green-600">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-green-600 mb-2">25+</h3>
                        <p class="text-gray-700 font-medium">National Awards</p>
                        <p class="text-sm text-gray-500 mt-1">Science & Arts</p>
                    </div>

                    <!-- Achievement 3 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition border-t-4 border-purple-600">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-purple-600 mb-2">500+</h3>
                        <p class="text-gray-700 font-medium">Happy Students</p>
                        <p class="text-sm text-gray-500 mt-1">Class 1 to 10</p>
                    </div>

                    <!-- Achievement 4 -->
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition border-t-4 border-orange-600">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                <path d="M3.5 9.289a1 1 0 011.357-.393l1.38.589 1.373.587a1 1 0 00.788 0l7-3a1 1 0 00.393-1.357l-1.016-2.374a1 1 0 00-1.746-.096L9.49 7.79a1 1 0 01-.788.393H7.39a1 1 0 01-.788-.393L3.116 3.116a1 1 0 00-1.746.096L.354 5.586a1 1 0 00.393 1.357l7 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-orange-600 mb-2">40+</h3>
                        <p class="text-gray-700 font-medium">Qualified Teachers</p>
                        <p class="text-sm text-gray-500 mt-1">Experienced Staff</p>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="mt-8 grid md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-4 text-white text-center">
                        <p class="text-2xl font-bold">98.5%</p>
                        <p class="text-sm mt-1">Average Attendance</p>
                    </div>
                    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-4 text-white text-center">
                        <p class="text-2xl font-bold">50+</p>
                        <p class="text-sm mt-1">Co-curricular Activities</p>
                    </div>
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-4 text-white text-center">
                        <p class="text-2xl font-bold">15+</p>
                        <p class="text-sm mt-1">Years of Excellence</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Us Section -->
        <section id="contact" class="py-16 bg-gradient-to-b from-white to-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Contact Us</h2>
                    <div class="h-1 w-24 bg-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-600 max-w-3xl mx-auto">
                        Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Contact Form -->
                    <div class="bg-white rounded-xl shadow-xl p-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>
                        <form action="#" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" id="name" name="name" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="Enter your name">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" id="email" name="email" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="your@email.com">
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="+880 1XXXXXXXXX">
                                </div>
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                    <select id="subject" name="subject" required 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">Select a subject</option>
                                        <option value="admission">Admission Inquiry</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="academics">Academic Information</option>
                                        <option value="complaint">Complaint/Feedback</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                                <textarea id="message" name="message" rows="5" required 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                          placeholder="Type your message here..."></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                    </svg>
                                    Send Message
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-6">
                        <!-- Contact Details Card -->
                        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-xl p-8 text-white">
                            <h3 class="text-2xl font-bold mb-6">Get in Touch</h3>
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-lg mb-1">Our Address</h4>
                                        <p class="text-blue-100">123 School Street, Education City<br>District - Kushtia, Khulna, Bangladesh</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-lg mb-1">Phone</h4>
                                        <p class="text-blue-100">+880 1980 732978<br>+880 1758 805840</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-lg mb-1">Email</h4>
                                        <p class="text-blue-100">info@myschool.com<br>admission@myschool.com</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-lg mb-1">Office Hours</h4>
                                        <p class="text-blue-100">Saturday - Thursday: 8:00 AM - 5:00 PM<br>Friday: Closed</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="mt-8 pt-8 border-t border-blue-500">
                                <h4 class="font-semibold text-lg mb-4">Follow Us</h4>
                                <div class="flex space-x-3">
                                    <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center hover:bg-opacity-30 transition-all">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gradient-to-b from-gray-800 to-gray-900 text-white">
            <!-- Quick Links -->
            <div class="bg-gray-800 py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div>
                            <h4 class="text-lg font-bold mb-4 text-blue-400">Quick Links</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="#home" class="hover:text-blue-400 transition">Home</a></li>
                                <li><a href="#about" class="hover:text-blue-400 transition">About Us</a></li>
                                <li><a href="#admission" class="hover:text-blue-400 transition">Admission</a></li>
                                <li><a href="#contact" class="hover:text-blue-400 transition">Contact</a></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-bold mb-4 text-blue-400">Student Section</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Results</a></li>
                                <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Pay Fee</a></li>
                                <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Assignments</a></li>
                                <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Attendance</a></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-bold mb-4 text-blue-400">Information</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="#" class="hover:text-blue-400 transition">News & Events</a></li>
                                <li><a href="#gallery" class="hover:text-blue-400 transition">Gallery</a></li>
                                <li><a href="#notice" class="hover:text-blue-400 transition">Notice Board</a></li>
                                <li><a href="#" class="hover:text-blue-400 transition">Achievements</a></li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-lg font-bold mb-4 text-blue-400">GET IN TOUCH</h4>
                            <ul class="space-y-3 text-sm">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-1 flex-shrink-0 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    <span>info@myschool.com</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-1 flex-shrink-0 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    <span>+91 - (123) - 4567890</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-1 flex-shrink-0 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>123 School Street, Education City</span>
                                </li>
                            </ul>
                            
                            <!-- Social Media -->
                            <div class="flex space-x-3 mt-6">
                                <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center hover:bg-blue-500 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center hover:bg-blue-800 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center hover:bg-red-700 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 0 1 .042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 0 1 4.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 0 1 .14-.197.35.35 0 0 1 .238-.042l2.906.617a1.214 1.214 0 0 1 1.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 0 0-.231.094.33.33 0 0 0 0 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 0 0 .029-.463.33.33 0 0 0-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 0 0-.232-.095z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="bg-gray-900 py-6 border-t border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-400">
                        Copyright © {{ date('Y') }}, School. All Rights Reserved
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // News Ticker - Start immediately on page load
        (function() {
            const ticker = document.querySelector('.animate-scroll');
            if (ticker) {
                // Clone content for seamless continuous loop
                const tickerContent = ticker.innerHTML;
                ticker.innerHTML = tickerContent + ' ' + tickerContent;
            }
        })();
    </script>
</body>
</html>
