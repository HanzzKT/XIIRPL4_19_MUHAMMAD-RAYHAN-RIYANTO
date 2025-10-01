<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up - Customer Service</title>
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
                    Create account
                </h1>
                <p class="text-[#71717a] text-sm">
                    Sign up to get started with our service
                </p>
            </div>

            <!-- Register Form -->
            <div class="vercel-card">
                <form class="space-y-5" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#171717] mb-2">Full Name</label>
                        <input id="name" name="name" type="text" required 
                               value="{{ old('name') }}"
                               class="vercel-input @error('name') border-red-500 @enderror"
                               placeholder="Enter your full name">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#171717] mb-2">Email</label>
                        <input id="email" name="email" type="email" required 
                               value="{{ old('email') }}"
                               class="vercel-input @error('email') border-red-500 @enderror"
                               placeholder="Enter your email">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-[#171717] mb-2">Phone Number</label>
                        <input id="phone" name="phone" type="text" required 
                               value="{{ old('phone') }}"
                               class="vercel-input @error('phone') border-red-500 @enderror"
                               placeholder="Enter your phone number">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-[#171717] mb-2">Address</label>
                        <textarea id="address" name="address" rows="3" required 
                                  class="vercel-input @error('address') border-red-500 @enderror"
                                  placeholder="Enter your address">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#171717] mb-2">Password</label>
                        <input id="password" name="password" type="password" required
                               class="vercel-input @error('password') border-red-500 @enderror"
                               placeholder="Minimum 8 characters">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-[#171717] mb-2">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="vercel-input"
                               placeholder="Confirm your password">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="vercel-button vercel-button-primary w-full">
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-[#71717a]">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-[#171717] hover:text-[#71717a] transition-colors font-medium">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-xs text-[#71717a]">
                    © {{ date('Y') }} PT Karunia Laris Abadi. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
