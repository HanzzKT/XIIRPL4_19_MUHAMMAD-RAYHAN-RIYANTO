@extends('layouts.sidebar')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
            <p class="text-gray-600 mt-1">Edit kategori komplain</p>
        </div>
        <a href="{{ route('complaint-categories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('complaint-categories.update', $complaintCategory) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $complaintCategory->name) }}" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                       placeholder="Contoh: Tabung Bocor">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror"
                          placeholder="Jelaskan kategori komplain ini...">{{ old('description', $complaintCategory->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $complaintCategory->is_active) ? 'checked' : '' }} 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Kategori aktif</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('complaint-categories.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 transform hover:scale-105">
                    Update Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
