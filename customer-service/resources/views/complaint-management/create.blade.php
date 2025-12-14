@extends('layouts.sidebar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Buat Komplain Baru</h1>
            <p class="text-gray-600 mt-1">Sampaikan keluhan atau masalah Anda</p>
        </div>
        <a href="{{ route('my-complaints') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
            Kembali
        </a>
    </div>

    <!-- User Info Display -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="text-sm font-medium text-blue-900 mb-2">Informasi Anda</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
            <div>
                <span class="font-medium">Nama:</span> {{ auth()->user()->name }}
            </div>
            <div>
                <span class="font-medium">Email:</span> {{ auth()->user()->email }}
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('complaints.store.authenticated') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Category -->
            <div>
                <label for="complaint_category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori Komplain</label>
                <select name="complaint_category_id" id="complaint_category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('complaint_category_id') border-red-300 @enderror" x-placement="bottom">
                    <option value="">Pilih Kategori</option>
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
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

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="Contoh: Gudang A, Jalan Merdeka No. 10" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-300 @enderror">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image (optional) -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Foto (opsional)</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-300 @enderror">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Video (optional) -->
            <div>
                <label for="video" class="block text-sm font-medium text-gray-700 mb-2">Video (opsional)</label>
                <input type="file" name="video" id="video" accept="video/mp4,video/quicktime,video/webm"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('video') border-red-300 @enderror">
                @error('video')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('my-complaints') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <span id="submitText">Buat Komplain</span>
                    <span id="loadingText" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mengirim...
                    </span>
                </button>
            </div>
        </form>
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

// Prevent double form submission
function preventDoubleSubmission() {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingText = document.getElementById('loadingText');
    let isSubmitting = false;

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // If already submitting, prevent the form submission
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            // Check if form is valid before proceeding
            if (!form.checkValidity()) {
                return true; // Let the browser handle validation
            }

            // Mark as submitting
            isSubmitting = true;
            
            // Disable the submit button
            submitBtn.disabled = true;
            
            // Show loading state
            submitText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            
            // Optional: Re-enable after a timeout as a fallback (in case of network issues)
            setTimeout(function() {
                if (isSubmitting) {
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                }
            }, 10000); // 10 seconds timeout
        });

        // Handle page unload to reset state
        window.addEventListener('beforeunload', function() {
            isSubmitting = false;
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            loadingText.classList.add('hidden');
        });
    }
}

// Initialize character count and form protection on page load
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('description');
    if (textarea) {
        updateCharCount(textarea);
    }
    
    // Initialize double submission prevention
    preventDoubleSubmission();
});
</script>
@endsection
