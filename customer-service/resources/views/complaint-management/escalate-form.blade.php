@extends('layouts.sidebar')

@section('title', 'Eskalasi Komplain - PT Karunia Laris Abadi')
@section('page-title', 'Eskalasi Komplain')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Eskalasi Komplain ke Manager</h1>
        <p class="text-sm text-gray-500">Komplain {{ $complaint->id }} - {{ $complaint->category->name }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form method="POST" action="{{ route('complaints.escalate', $complaint) }}">
                @csrf
                
                <!-- Info Komplain -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-blue-900 mb-2">Informasi Komplain</h3>
                    <div class="text-sm text-blue-800">
                        <p><strong>Customer:</strong> {{ $complaint->customer->name }}</p>
                        <p><strong>Kategori:</strong> {{ $complaint->category->name }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($complaint->status) }}</p>
                        <p><strong>Deskripsi:</strong> {{ $complaint->description }}</p>
                    </div>
                </div>

                <!-- Alasan Eskalasi -->
                <div class="mb-6">
                    <label for="escalation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Eskalasi <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="escalation_reason" 
                        name="escalation_reason"
                        rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                        placeholder="Jelaskan mengapa komplain ini perlu dieskalasi ke Manager..."
                        required
                        minlength="10"
                        maxlength="500"
                    >{{ old('escalation_reason') }}</textarea>
                    @error('escalation_reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-2 text-xs text-gray-500">
                        Minimal 10 karakter, maksimal 500 karakter
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('complaints.show', $complaint) }}" class="px-6 py-2 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3l-6.928-12c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Eskalasi ke Manager
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
