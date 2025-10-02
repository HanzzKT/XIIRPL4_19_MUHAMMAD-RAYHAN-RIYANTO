<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sign In - Customer Service</title>

    <!-- Fonts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fafafa] font-body min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-sm w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-lg bg-[#171717] mb-6">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-display font-semibold text-[#171717] tracking-tight mb-2">
                    Welcome back
                </h1>
                <p class="text-[#71717a] text-sm">
                    Sign in to your account to continue
                </p>
            </div>

            <!-- Login Form -->
            <div class="vercel-card">
                <!-- Show message if redirected from complaint creation -->
                @if(session('message'))
                <div class="mb-6 p-4 rounded-lg bg-[#f0f9ff] border border-[#0ea5e9]/20">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-[#0ea5e9] mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-[#0ea5e9]">{{ session('message') }}</p>
                    </div>
                </div>
                @endif

                <!-- Show success message if redirected from registration -->
                @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-[#f0fdf4] border border-[#22c55e]/20">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-[#22c55e] mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-[#22c55e]">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <form class="space-y-5" action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#171717] mb-2">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="vercel-input @error('email') border-red-500 @enderror" 
                               placeholder="Enter your email" value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#171717] mb-2">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="vercel-input @error('password') border-red-500 @enderror" 
                               placeholder="Enter your password">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="w-4 h-4 text-[#171717] bg-white border-[#e4e4e7] rounded focus:ring-[#171717] focus:ring-2">
                            <label for="remember" class="ml-2 text-sm text-[#71717a]">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-[#71717a] hover:text-[#171717] transition-colors" 
                               href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="vercel-button vercel-button-primary w-full">
                        Sign In
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-[#71717a]">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-[#171717] hover:text-[#71717a] transition-colors font-medium">
                            Sign up
                        </a>
                    </p>
                </div>

            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-xs text-[#71717a]">
                    &copy; {{ date('Y') }} PT Karunia Laris Abadi. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
