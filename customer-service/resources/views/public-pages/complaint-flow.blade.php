@extends('public-pages.layout')

@section('title', 'Alur Proses Komplain')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Alur Proses Komplain</h1>
            <p class="text-xl text-blue-100">Langkah-langkah penanganan keluhan pelanggan</p>
        </div>
    </div>
</section>

<!-- Process Flow -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Bagaimana Kami Menangani Komplain Anda</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Proses yang terstruktur dan transparan untuk memastikan setiap keluhan ditangani dengan profesional
            </p>
        </div>

        <div class="relative">
            <!-- Progress Line -->
            <div class="hidden md:block absolute top-24 left-0 w-full h-1 bg-gray-200">
                <div class="h-full bg-blue-600 w-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                @foreach($steps as $step)
                <div class="relative">
                    <!-- Step Circle -->
                    <div class="flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-full font-bold text-xl mx-auto mb-4 relative z-10 shadow-lg">
                        {{ $step['step'] }}
                    </div>
                    
                    <!-- Step Content -->
                    <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-shadow duration-300">
                        <!-- Icon -->
                        <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center">
                            @if($step['icon'] == 'user')
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            @elseif($step['icon'] == 'chat')
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            @elseif($step['icon'] == 'edit')
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            @elseif($step['icon'] == 'cog')
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            @elseif($step['icon'] == 'check')
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $step['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- SLA Information -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Komitmen Waktu Penyelesaian</h2>
                <p class="text-gray-600">Kami berkomitmen menyelesaikan komplain Anda dalam waktu yang telah ditentukan</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Kasus Darurat</h3>
                            <p class="text-sm text-gray-600">Kebocoran gas, tabung rusak parah</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl font-bold text-red-600">24 Jam</span>
                        <p class="text-sm text-gray-600 mt-1">Respons maksimal</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Kasus Normal</h3>
                            <p class="text-sm text-gray-600">Galon kotor, keterlambatan pengiriman</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl font-bold text-blue-600">3x24 Jam</span>
                        <p class="text-sm text-gray-600 mt-1">Penyelesaian maksimal</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Siap Menyampaikan Keluhan?</h2>
        <p class="text-lg text-gray-600 mb-8">
            Daftar akun untuk buat komplain online atau hubungi WhatsApp kami untuk bantuan langsung
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                📝 Daftar & Buat Komplain
            </a>
            <a href="https://wa.me/081234567890" target="_blank" 
               class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                📱 WhatsApp: 081234567890
            </a>
        </div>
        
        <div class="mt-8">
            <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat informasi kontak lengkap →
            </a>
        </div>
    </div>
</section>
@endsection
