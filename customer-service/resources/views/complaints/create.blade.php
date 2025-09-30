@extends('layouts.navbar')

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
        <form method="POST" action="{{ route('complaints.store.authenticated') }}" class="space-y-6">
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
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Detail Komplain</label>
                <textarea name="description" id="description" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                          placeholder="Jelaskan detail keluhan atau masalah Anda...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('my-complaints') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 transform hover:scale-105">
                    Buat Komplain
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
