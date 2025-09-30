<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Service') - PT Karunia Laris Abadi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left side - Logo and Navigation -->
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-gray-900">PT Karunia Laris Abadi</h1>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        @if(auth()->user()->isCs() || auth()->user()->isAdmin())
                            <a href="{{ route('cs.dashboard') }}" 
                               class="nav-link {{ request()->routeIs('cs.dashboard') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                            
                            <a href="{{ route('complaints.index') }}" 
                               class="nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h4a2 2 0 002-2V3a2 2 0 012 2v6h-3a2 2 0 100 4h3v2a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                Kelola Komplain
                            </a>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" 
                               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                </svg>
                                Admin
                            </a>

                            <a href="{{ route('users.index') }}" 
                               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                                Pengguna
                            </a>

                            <a href="{{ route('customers.index') }}" 
                               class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                Pelanggan
                            </a>

                            <a href="{{ route('complaint-categories.index') }}" 
                               class="nav-link {{ request()->routeIs('complaint-categories.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                                Kategori
                            </a>
                        @endif

                        @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                            <a href="{{ route('manager.dashboard') }}" 
                               class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                                </svg>
                                Analytics
                            </a>

                            <a href="{{ route('complaints.index') }}" 
                               class="nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h4a2 2 0 002-2V3a2 2 0 012 2v6h-3a2 2 0 100 4h3v2a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                Komplain
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Right side - Real Time Clock -->
                <div class="flex items-center">
                    <!-- Real Time Clock -->
                    <div class="mr-6">
                        <div id="realTimeClock" class="text-sm font-medium text-gray-700 bg-gray-100 px-4 py-2 rounded-lg">
                            <i class="fas fa-clock mr-2 text-blue-600"></i>
                            <span id="currentDateTime">22 Sep 2025, 22:00</span>
                        </div>
                    </div>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <img class="w-8 h-8 rounded-full border-2 border-gray-200" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=ffffff&size=128" 
                                 alt="{{ auth()->user()->name }}">
                            <div class="ml-3 text-left hidden sm:block">
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                            </div>
                            <svg class="ml-2 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden ml-4">
                        <button id="mobile-menu-button" class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @if(auth()->user()->isCs() || auth()->user()->isAdmin())
                    <a href="{{ route('cs.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('cs.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('complaints.index') }}" class="mobile-nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">Kelola Komplain</a>
                @endif

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Admin</a>
                    <a href="{{ route('users.index') }}" class="mobile-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">Pengguna</a>
                    <a href="{{ route('customers.index') }}" class="mobile-nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">Pelanggan</a>
                    <a href="{{ route('complaint-categories.index') }}" class="mobile-nav-link {{ request()->routeIs('complaint-categories.*') ? 'active' : '' }}">Kategori</a>
                @endif

                @if(auth()->user()->isManager() || auth()->user()->isAdmin())
                    <a href="{{ route('manager.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">Analytics</a>
                    <a href="{{ route('complaints.index') }}" class="mobile-nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">Komplain</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg animate-fade-in-down">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg animate-fade-in-down">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg animate-fade-in-down">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                {{ session('info') }}
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <style>
        .nav-link {
            @apply inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all duration-200;
        }

        .nav-link.active {
            @apply text-blue-600 bg-blue-50;
        }

        .mobile-nav-link {
            @apply block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors duration-200;
        }

        .mobile-nav-link.active {
            @apply text-blue-600 bg-blue-50;
        }

        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out;
        }
    </style>

    <script>
        // User menu dropdown
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userMenuButton = document.getElementById('user-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');

            if (!userMenuButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }

            if (!mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        @if(auth()->user()->role === 'customer')
        // Update navbar badge based on localStorage
        document.addEventListener('DOMContentLoaded', function() {
            updateNavbarBadge();
        });

        function updateNavbarBadge() {
            let unreadCount = 0;
            @if(isset($complaintsWithResponse))
                @foreach($complaintsWithResponse as $complaint)
                    if (localStorage.getItem('complaint_{{ $complaint->id }}_read') !== 'true') {
                        unreadCount++;
                    }
                @endforeach
            @endif

            const badge = document.getElementById('navbar-feedback-badge');
            if (badge) {
                if (unreadCount === 0) {
                    badge.remove();
                } else {
                    badge.textContent = unreadCount;
                }
            }
        }
        @endif

        // Real-time clock function
        function updateRealTimeClock() {
            const now = new Date();
            
            // Format tanggal dalam bahasa Indonesia
            const months = [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ];
            
            const day = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            
            const formattedDateTime = `${day} ${month} ${year}, ${hours}:${minutes}:${seconds}`;
            
            const clockElement = document.getElementById('currentDateTime');
            if (clockElement) {
                clockElement.textContent = formattedDateTime;
            }
        }

        // Update clock immediately and then every second
        updateRealTimeClock();
        setInterval(updateRealTimeClock, 1000);
    </script>
    
    @stack('scripts')
</body>
</html>
