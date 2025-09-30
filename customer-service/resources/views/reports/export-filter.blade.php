@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Filter Export PDF</h1>
            <p class="text-gray-600 mt-1">Pilih filter untuk export laporan komplain ke PDF</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('complaints.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <form method="GET" action="{{ route('reports.export-pdf') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status Komplain
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                            <span class="ml-3 text-sm text-gray-700">Semua Status</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="baru" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-3 text-sm text-gray-700">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Baru</span>
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="diproses" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-3 text-sm text-gray-700">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Diproses</span>
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="selesai" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-3 text-sm text-gray-700">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Selesai</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Rentang Tanggal
                    </label>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ date('Y-m-01') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Filters -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Tambahan (Opsional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Kategori
                        </label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

          

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('complaints.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    📄 Export PDF
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Set default date values if not set
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');
    
    // Set start date to first day of current month if empty
    if (!startDate.value) {
        const now = new Date();
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        startDate.value = firstDay.toISOString().split('T')[0];
    }
    
    // Set end date to today if empty
    if (!endDate.value) {
        const today = new Date();
        endDate.value = today.toISOString().split('T')[0];
    }
});
</script>
@endsection
