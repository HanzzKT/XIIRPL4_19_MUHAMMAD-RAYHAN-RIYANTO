<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PT Karunia Laris Abadi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-blue-600">PT Karunia Laris Abadi</h1>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('faq') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('faq') ? 'text-blue-600 bg-blue-50' : '' }}">
                        FAQ
                    </a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('contact') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Kontak
                    </a>
                    <a href="{{ route('complaint-flow') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('complaint-flow') ? 'text-blue-600 bg-blue-50' : '' }}">
                        Alur Komplain
                    </a>
                    <a href="{{ url('/#buat-komplain') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                        Buat Komplain
                    </a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Login</a>
                    @endauth
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="mobile-menu-button text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : '' }}">Beranda</a>
                <a href="{{ route('faq') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('faq') ? 'text-blue-600 bg-blue-50' : '' }}">FAQ</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('contact') ? 'text-blue-600 bg-blue-50' : '' }}">Kontak</a>
                <a href="{{ route('complaint-flow') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600 {{ request()->routeIs('complaint-flow') ? 'text-blue-600 bg-blue-50' : '' }}">Alur Komplain</a>
                <a href="{{ url('/#buat-komplain') }}" class="block px-3 py-2 bg-blue-600 text-white rounded-lg">Buat Komplain</a>
                @auth
                <form method="POST" action="{{ route('logout') }}" class="px-2 pb-3">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-gray-700 hover:text-blue-600">Logout</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">PT Karunia Laris Abadi</h3>
                    <p class="text-gray-300 mb-4">Layanan customer service terpercaya untuk produk gas LPG dan air galon. Kami siap menangani keluhan dan memberikan solusi terbaik.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <div class="space-y-2 text-gray-300">
                        <p>📱 081234567890</p>
                        <p>✉️ cs@karunialaris.com</p>
                        <p>📍 Jl. Raya Industri No. 123, Jakarta Timur</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Jam Layanan</h3>
                    <div class="space-y-2 text-gray-300">
                        <p>Senin - Jumat: 08:00 - 17:00</p>
                        <p>Sabtu: 08:00 - 15:00</p>
                        <p>Minggu: Tutup</p>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-300">
                <p>&copy; 2024 PT Karunia Laris Abadi. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
