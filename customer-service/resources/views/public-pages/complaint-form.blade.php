@extends('public-pages.layout')

@section('title', 'Buat Komplain - PT Karunia Laris Abadi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Komplain</h1>
            <p class="text-gray-600">Sampaikan keluhan Anda dan kami akan segera menindaklanjutinya</p>
        </div>

        <!-- Complaint Form -->
        <div class="bg-white rounded-xl shadow-xl p-8 border border-gray-100">
            <form action="{{ route('complaints.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pribadi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-300 @enderror"
                                   placeholder="Masukkan nama lengkap Anda">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" required 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-300 @enderror"
                                   placeholder="contoh@email.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="phone" id="phone" required 
                                   value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('phone') border-red-300 @enderror"
                                   placeholder="081234567890">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea name="address" id="address" rows="3" required 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('address') border-red-300 @enderror"
                                      placeholder="Alamat lengkap Anda">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Complaint Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Komplain</h3>
                    
                    <div class="space-y-6">
                        <!-- Category -->
                        <div>
                            <label for="complaint_category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori Komplain</label>
                            <select name="complaint_category_id" id="complaint_category_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('complaint_category_id') border-red-300 @enderror" x-placement="bottom">
                                <option value="">Pilih kategori komplain...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('complaint_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('complaint_category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Detail Komplain 
                                <span class="text-xs text-gray-500">(Maksimal 200 karakter)</span>
                            </label>
                            <textarea name="description" id="description" rows="4" required
                                      maxlength="200"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('description') border-red-300 @enderror"
                                      placeholder="Jelaskan masalah Anda secara singkat dan jelas (maksimal 200 karakter)..."
                                      oninput="updateCharCount(this)">{{ old('description') }}</textarea>
                            <div class="flex justify-between items-center mt-1">
                                <div>
                                    @error('description')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <span id="charCount" class="text-xs text-gray-500">0/200</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800 font-medium transition-colors duration-200">
                        ← Kembali ke Beranda
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-3 px-8 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                        Kirim Komplain
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="h-6 w-6 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-900 mb-1">Informasi Penting</h4>
                    <p class="text-sm text-blue-700">
                        Setelah mengirim komplain, tim Customer Service kami akan menghubungi Anda dalam waktu 1x24 jam. 
                        Pastikan nomor telepon yang Anda berikan aktif dan dapat dihubungi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateCharCount(textarea) {
    const charCount = document.getElementById('charCount');
    const currentLength = textarea.value.length;
    const maxLength = 200;
    
    charCount.textContent = `${currentLength}/${maxLength}`;
    
    // Change color based on character count
    if (currentLength > maxLength * 0.9) {
        charCount.className = 'text-xs text-red-500';
    } else if (currentLength > maxLength * 0.7) {
        charCount.className = 'text-xs text-yellow-500';
    } else {
        charCount.className = 'text-xs text-gray-500';
    }
}

// Initialize character count on page load
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('description');
    if (textarea) {
        updateCharCount(textarea);
    }
});
</script>
@endsection
