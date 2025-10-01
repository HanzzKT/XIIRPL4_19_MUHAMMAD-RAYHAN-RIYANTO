@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Komplain</h1>
            <p class="text-gray-600 mt-1">Edit detail komplain pelanggan</p>
        </div>
        <a href="{{ route('complaints.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('complaints.update', $complaint) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Customer -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Pelanggan</label>
                <select name="customer_id" id="customer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('customer_id') border-red-300 @enderror">
                    <option value="">Pilih Pelanggan</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id', $complaint->customer_id) == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }} ({{ $customer->user->email ?? 'No Email' }})
                    </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="complaint_category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori Komplain</label>
                <select name="complaint_category_id" id="complaint_category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('complaint_category_id') border-red-300 @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('complaint_category_id', $complaint->complaint_category_id) == $category->id ? 'selected' : '' }}>
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
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Komplain</label>
                <textarea name="description" id="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror" placeholder="Jelaskan detail komplain...">{{ old('description', $complaint->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 @enderror">
                    <option value="baru" {{ old('status', $complaint->status) == 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ old('status', $complaint->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ old('status', $complaint->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Notes -->
            <div>
                <label for="action_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tindakan</label>
                <textarea name="action_notes" id="action_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('action_notes') border-red-300 @enderror" placeholder="Catatan tindakan yang diambil...">{{ old('action_notes', $complaint->action_notes) }}</textarea>
                @error('action_notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('complaints.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Komplain
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
