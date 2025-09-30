@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Komplain</h1>
            <p class="text-gray-600 mt-1">Kelola semua komplain pelanggan</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="showExportModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                📄 Ekspor PDF
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama pelanggan atau judul komplain..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" x-placement="bottom">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" x-placement="bottom">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2 md:justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm">
                    Filter
                </button>
                <a href="{{ route('complaints.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
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
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">CS</th>
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
                            <div class="text-sm font-medium text-gray-900">{{ $complaint->customer->name ?? 'Pelanggan tidak ditemukan' }}</div>
                            <div class="text-sm text-gray-500">{{ $complaint->customer->phone ?? 'Tidak ada telepon' }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $complaint->category->name }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($complaint->description, 60) }}</div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $complaint->status === 'baru' ? 'bg-red-100 text-red-800' : ($complaint->status === 'diproses' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                            @if($complaint->escalation_to)
                                <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>DIESKALASI
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ $complaint->handledBy?->name ?? 'Belum diambil' }}
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm font-medium space-x-2">
                            <!-- Tombol Detail untuk semua komplain -->
                            <a href="{{ route('complaints.show', $complaint) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">Detail</a>
                            
                            @if(auth()->user()->role === 'manager')
                                <!-- Manager bisa memberikan tindakan dan menyelesaikan komplain -->
                                @if($complaint->escalation_to && !$complaint->manager_action)
                                    <a href="{{ route('complaints.manager-action-form', $complaint) }}" class="text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center">
                                        <i class="fas fa-cog mr-1"></i>Tindakan Manager
                                    </a>
                                @elseif($complaint->manager_action === 'resolved' && $complaint->status !== 'selesai')
                                    <!-- Manager bisa menyelesaikan complaint setelah resolved -->
                                    <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 transition-colors duration-200 px-3 py-1 rounded text-xs">Selesai</button>
                                    </form>
                                @elseif($complaint->manager_action)
                                    <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded">
                                        ✓ {{ ucfirst(str_replace('_', ' ', $complaint->manager_action)) }}
                                    </span>
                                @endif
                            @elseif(auth()->user()->role === 'admin')
                                <!-- Admin hanya bisa menghapus complaint yang sudah selesai -->
                                @if($complaint->status === 'selesai')
                                    <span class="text-green-600 text-xs mr-2">✓ Selesai</span>
                                    <form method="POST" action="{{ route('complaints.destroy', $complaint) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komplain ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200 text-xs">Hapus</button>
                                    </form>
                                @else
                                    <!-- Admin hanya memantau - tidak ada aksi -->
                                    <span class="text-gray-500 text-xs">Memantau</span>
                                @endif
                            @else
                                <!-- Untuk CS -->
                                @if($complaint->status === 'baru' && !$complaint->handled_by)
                                    <!-- Complaint baru belum diambil - tombol Ambil -->
                                    <form method="POST" action="{{ route('complaints.take', $complaint) }}" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 transition-colors duration-200 bg-green-50 px-2 py-1 rounded" onclick="return confirm('Ambil komplain ini?')">Ambil</button>
                                    </form>
                                @elseif($complaint->status === 'diproses' && $complaint->handled_by)
                                    <!-- Complaint sedang diproses - tombol Selesai dan Eskalasi -->
                                    <div class="flex items-center space-x-2">
                                        @if(!$complaint->escalation_to)
                                            <!-- CS hanya bisa eskalasi, tidak bisa menyelesaikan langsung -->
                                            <a href="{{ route('complaints.escalate-form', $complaint) }}" class="text-white bg-red-600 hover:bg-red-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Eskalasi
                                            </a>
                                        @else
                                            <!-- Jika sudah dieskalasi, CS tidak bisa menyelesaikan - hanya manager yang approve -->
                                        
                                        <span class="text-red-600 text-xs bg-red-100 px-2 py-1 rounded">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Dieskalasi ke Manager
                                        </span>
                                        
                                        @if($complaint->manager_action === 'resolved')
                                            <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded">
                                                <i class="fas fa-check-circle mr-1"></i>Manager Sudah Tangani - Berikan Feedback
                                            </span>
                                        @elseif($complaint->manager_action === 'return_to_cs')
                                            <span class="text-blue-600 text-xs bg-blue-100 px-2 py-1 rounded">
                                                <i class="fas fa-arrow-left mr-1"></i>Dikembalikan ke CS
                                            </span>
                                        @else
                                            <span class="text-orange-600 text-xs bg-orange-100 px-2 py-1 rounded">
                                                <i class="fas fa-hand-paper mr-1"></i>Menunggu Manager
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                @elseif($complaint->status === 'selesai')
                                    <!-- Complaint sudah selesai - hanya tanda centang dan tombol hapus -->
                                    <span class="text-green-600 text-xs mr-2">✓ Selesai</span>
                                    <form method="POST" action="{{ route('complaints.destroy', $complaint) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komplain ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200 text-xs">Hapus</button>
                                    </form>
                                @endif
                                
                                @if($complaint->handled_by && $complaint->status === 'baru')
                                    <!-- Edit button hanya untuk complaint status baru yang sudah diambil -->
                                    <a href="{{ route('complaints.edit', $complaint) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">Edit</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada komplain</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan komplain baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($complaints->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $complaints->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Export PDF Modal -->
<div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Filter Export PDF</h3>
                <button onclick="hideExportModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form method="GET" action="{{ route('complaints.export-pdf') }}" id="exportForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="baru">Baru</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="hideExportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                        📄 Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function hideExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideExportModal();
    }
});
</script>

@endsection
