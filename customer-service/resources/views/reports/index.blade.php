@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            @if(auth()->user()->role === 'manager')
                <h1 class="text-2xl font-bold text-gray-900">Komplain Eskalasi</h1>
                <p class="text-gray-600 mt-1">Kelola komplain yang dieskalasi ke Manager</p>
            @else
                <h1 class="text-2xl font-bold text-gray-900">Laporan Komplain</h1>
                <p class="text-gray-600 mt-1">Laporan data komplain pelanggan</p>
            @endif
        </div>
        <div>
            <form method="GET" action="{{ route('reports.export-pdf') }}" class="inline-block">
                <!-- Preserve current filters -->
                @if(request('start_date'))
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                @endif
                @if(request('end_date'))
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                @endif
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 shadow-lg font-medium text-lg">
                    <svg class="w-6 h-6 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    📄 Download PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('reports.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Complaints Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Kategori & Detail</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        @if(auth()->user()->role === 'manager')
                            <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Eskalasi</th>
                        @endif
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">CS Handler</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($complaints as $complaint)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ $complaint->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $complaint->customer->name ?? 'Customer tidak ditemukan' }}</div>
                            <div class="text-sm text-gray-500">{{ $complaint->customer_phone ?? $complaint->customer->phone ?? 'No phone' }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $complaint->category->name }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($complaint->description, 50) }}</div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $complaint->status === 'baru' ? 'bg-red-100 text-red-800' : ($complaint->status === 'diproses' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </td>
                        @if(auth()->user()->role === 'manager')
                            <td class="px-5 py-3 whitespace-nowrap text-sm">
                                @if($complaint->escalated_at)
                                    <div class="text-gray-900 font-medium">{{ $complaint->escalated_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-gray-500 text-xs">oleh {{ $complaint->escalatedBy?->name ?? 'CS' }}</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        @endif
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ $complaint->handledBy?->name ?? 'Belum diambil' }}
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('complaints.show', $complaint) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'manager' ? '7' : '6' }}" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                            <p class="mt-1 text-sm text-gray-500">Tidak ada komplain yang sesuai dengan filter yang dipilih.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($complaints->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $complaints->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
