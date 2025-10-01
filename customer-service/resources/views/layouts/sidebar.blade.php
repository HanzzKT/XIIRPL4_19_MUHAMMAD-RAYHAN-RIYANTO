<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Service System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fafafa] font-body">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 sidebar-container text-white flex flex-col shadow-xl">
            <!-- Logo -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-display font-semibold">PT Karunia Laris</h1>
                        <p class="text-white/70 text-xs">Customer Service</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <!-- Admin Menu -->
                        <div class="mb-6">
                            <p class="text-white/50 text-xs uppercase tracking-wider mb-3 px-3 font-medium">Admin Panel</p>
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('users.index') }}" class="sidebar-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <span>User Management</span>
                            </a>
                            <a href="{{ route('customers.index') }}" class="sidebar-nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Customer Management</span>
                            </a>
                            <a href="{{ route('complaint-categories.index') }}" class="sidebar-nav-link {{ request()->routeIs('complaint-categories.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span>Category Management</span>
                            </a>
                            <a href="{{ route('complaints.index') }}" class="sidebar-nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Complaint Management</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->role === 'cs')
                        <!-- CS Menu -->
                        <div class="mb-6">
                            <p class="text-white/50 text-xs uppercase tracking-wider mb-3 px-3 font-medium">Customer Service</p>
                            <a href="{{ route('cs.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('cs.dashboard') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>CS Dashboard</span>
                            </a>
                            <a href="{{ route('complaints.index') }}" class="sidebar-nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Handle Complaints</span>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->role === 'manager')
                        <!-- Manager Menu -->
                        <div class="mb-6">
                            <p class="text-white/50 text-xs uppercase tracking-wider mb-3 px-3 font-medium">Manager Panel</p>
                            <a href="{{ route('manager.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Manager Dashboard</span>
                            </a>
                            <a href="{{ route('complaints.index') }}" class="sidebar-nav-link {{ request()->routeIs('complaints*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Review Complaints</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-white/10">
                @auth
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                        <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-white/60">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full sidebar-nav-link">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </form>
                @endauth
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden bg-[#fafafa]">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-[#e4e4e7]">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <div class="relative">
                                <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 mx-6 mt-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 mx-6 mt-4 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-6 py-4 mx-6 mt-4 rounded-lg">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
