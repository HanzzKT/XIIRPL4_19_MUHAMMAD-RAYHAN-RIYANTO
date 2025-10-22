@extends('layouts.sidebar')

@section('title', 'Instruksi Manager - PT Karunia Laris Abadi')
@section('page-title', 'Instruksi Manager')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Berikan Instruksi</h1>
        <p class="text-sm text-gray-500">Komplain {{ $complaint->id }} - {{ $complaint->category->name }}</p>
        <p class="text-xs text-gray-400 mt-1">Manager memberikan instruksi, CS yang menyelesaikan dan memberikan feedback ke customer</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form method="POST" action="{{ route('complaints.manager-action', $complaint) }}">
                @csrf
                @method('PATCH')
                
                <!-- Info Komplain -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-blue-900 mb-2">Informasi Komplain</h3>
                    <div class="text-sm text-blue-800">
                        <p><strong>Customer:</strong> {{ $complaint->customer->name }}</p>
                        <p><strong>Kategori:</strong> {{ $complaint->category->name }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($complaint->status) }}</p>
                        <p><strong>Deskripsi:</strong> {{ $complaint->description }}</p>
                        @if($complaint->escalation_reason)
                            <p><strong>Alasan Eskalasi:</strong> {{ $complaint->escalation_reason }}</p>
                        @endif
                    </div>
                </div>

                <!-- Catatan Penting -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <strong>Catatan:</strong> Manager memberikan instruksi penanganan, CS yang melaksanakan dan memberikan feedback ke customer.
                        </div>
                    </div>
                </div>

                <!-- Pilih Instruksi -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Pilih Instruksi <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-start p-4 border-2 border-gray-300 rounded-lg hover:bg-purple-50 hover:border-purple-300 cursor-pointer transition-all">
                            <input type="radio" name="manager_action" value="resolved" class="mt-1 mr-4 text-purple-600 focus:ring-purple-500" {{ old('manager_action') === 'resolved' ? 'checked' : '' }} required>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Masalah Sudah Ditangani
                                </div>
                                <div class="text-gray-600 mt-1">Masalah sudah diselesaikan secara internal. CS dapat memberikan feedback final ke customer dan menyelesaikan komplain.</div>
                            </div>
                        </label>
                        <label class="flex items-start p-4 border-2 border-gray-300 rounded-lg hover:bg-orange-50 hover:border-orange-300 cursor-pointer transition-all">
                            <input type="radio" name="manager_action" value="return_to_cs" class="mt-1 mr-4 text-orange-600 focus:ring-orange-500" {{ old('manager_action') === 'return_to_cs' ? 'checked' : '' }} required>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    Kembalikan ke CS
                                </div>
                                <div class="text-gray-600 mt-1">Komplain dikembalikan ke CS untuk ditangani lebih lanjut. Eskalasi akan dihapus.</div>
                            </div>
                        </label>
                    </div>
                    @error('manager_action')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan Manager -->
                <div class="mb-6">
                    <label for="manager_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Manager (Opsional)
                    </label>
                    <textarea 
                        id="manager_notes" 
                        name="manager_notes"
                        rows="3" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none"
                        placeholder="Berikan catatan tentang tindakan yang diambil untuk membantu CS..."
                        maxlength="1000"
                    >{{ old('manager_notes') }}</textarea>
                    @error('manager_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('complaints.show', $complaint) }}" class="px-6 py-2 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Kirim Instruksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
