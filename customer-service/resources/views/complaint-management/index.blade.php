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
            @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                <button onclick="showExportModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    📄 Ekspor PDF
                </button>
            @endif
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
                    @if(auth()->user()->role === 'cs')
                        <option value="">Semua Status</option>
                        <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    @else
                        <option value="">Semua Status</option>
                        <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    @endif
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
                                @php
                                    // Cek action notes terbaru (setelah || terakhir jika ada)
                                    $latestActionNotes = $complaint->action_notes;
                                    if ($latestActionNotes && str_contains($latestActionNotes, ' || ')) {
                                        $allActions = explode(' || ', $latestActionNotes);
                                        $latestActionNotes = end($allActions);
                                    }
                                @endphp
                                @if($latestActionNotes && str_contains($latestActionNotes, 'Manager Action:'))
                                    @if(str_contains($latestActionNotes, 'resolved'))
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>MANAGER SELESAI
                                        </span>
                                    @else
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-arrow-left mr-1"></i>DIKEMBALIKAN
                                        </span>
                                    @endif
                                @else
                                    @if($complaint->manager_claimed_by)
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-user-tie mr-1"></i>DITANGANI {{ strtoupper($complaint->managerClaimedBy->name) }}
                                        </span>
                                    @else
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>PERLU MANAGER
                                        </span>
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ $complaint->handledBy?->name ?? 'Belum diambil' }}
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-sm font-medium space-x-2">
                            <!-- Tombol Detail untuk semua komplain -->
                            <a href="{{ route('complaints.show', $complaint) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">Detail</a>
                            
                            @if(auth()->user()->role === 'manager')
                                <!-- Manager Claim System untuk Eskalasi -->
                                @if($complaint->escalation_to && $complaint->status !== 'selesai')
                                    @if(!$complaint->manager_claimed_by)
                                        <!-- Eskalasi belum diklaim, Manager bisa ambil -->
                                        <form method="POST" action="{{ route('complaints.claim-escalation', $complaint) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center">
                                                <i class="fas fa-hand-paper mr-1"></i>Ambil
                                            </button>
                                        </form>
                                    @elseif($complaint->manager_claimed_by === auth()->id())
                                        <!-- Diklaim oleh Manager ini -->
                                        <div class="inline-flex space-x-1">
                                            @php
                                                // Cek apakah manager sudah beri instruksi terbaru (bukan history)
                                                $hasCurrentAction = false;
                                                if ($complaint->action_notes) {
                                                    $latestAction = $complaint->action_notes;
                                                    if (str_contains($latestAction, ' || ')) {
                                                        $allActions = explode(' || ', $latestAction);
                                                        $latestAction = end($allActions);
                                                    }
                                                    // Jika latest action bukan history dan ada Manager Action, berarti sudah beri instruksi
                                                    if (!str_contains($latestAction, '[HISTORY]') && str_contains($latestAction, 'Manager Action:')) {
                                                        $hasCurrentAction = true;
                                                    }
                                                }
                                            @endphp
                                            @if(!$hasCurrentAction)
                                                <!-- Manager belum beri instruksi -->
                                                <a href="{{ route('complaints.manager-action-form', $complaint) }}" class="text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center">
                                                    <i class="fas fa-clipboard-list mr-1"></i>Instruksi
                                                </a>
                                                <form method="POST" action="{{ route('complaints.release-escalation', $complaint) }}" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-white bg-gray-600 hover:bg-gray-700 transition-colors duration-200 px-3 py-1 rounded text-xs" onclick="return confirm('Yakin ingin melepas eskalasi ini? CS akan menangani sendiri.')">
                                                        <i class="fas fa-hand-point-left mr-1"></i>Lepas
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Manager sudah beri instruksi - CS yang harus follow up -->
                                                <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded">
                                                    <i class="fas fa-check mr-1"></i>Instruksi Selesai
                                                </span>
                                                <span class="text-blue-600 text-xs bg-blue-100 px-2 py-1 rounded ml-1">
                                                    <i class="fas fa-arrow-right mr-1"></i>Menunggu CS Follow Up
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Diklaim oleh Manager lain -->
                                        <span class="text-orange-600 text-xs bg-orange-100 px-2 py-1 rounded">
                                            <i class="fas fa-user mr-1"></i>{{ $complaint->managerClaimedBy->name }}
                                        </span>
                                    @endif
                                @endif
                                
                                <!-- Status setelah Manager Action - CS yang menyelesaikan -->
                                @if($complaint->status === 'selesai')
                                    <!-- Komplain sudah selesai - hanya tampilkan status -->
                                    <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded">
                                        ✓ Selesai
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
                                    @if(isset($csHasActiveComplaint) && $csHasActiveComplaint)
                                        <!-- CS sudah memiliki komplain aktif - button disabled -->
                                        <button disabled class="text-gray-400 bg-gray-100 px-2 py-1 rounded cursor-not-allowed" title="Anda masih memiliki komplain yang belum selesai">
                                            Ambil
                                        </button>
                                    @else
                                        <!-- CS belum memiliki komplain aktif - bisa ambil -->
                                        <form method="POST" action="{{ route('complaints.take', $complaint) }}" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 transition-colors duration-200 bg-green-50 px-2 py-1 rounded" onclick="return confirm('Ambil komplain ini?')">Ambil</button>
                                        </form>
                                    @endif
                                @elseif($complaint->status === 'diproses' && $complaint->handled_by)
                                    <!-- Complaint sedang diproses - tombol Selesai dan Eskalasi -->
                                    <div class="flex items-center space-x-2">
                                        @if(!$complaint->escalation_to)
                                            <!-- CS bisa menyelesaikan atau eskalasi hanya jika mereka yang handle -->
                                            @if($complaint->handled_by === auth()->id() || auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                                                <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="selesai">
                                                    <button type="submit" class="text-white bg-green-600 hover:bg-green-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center" onclick="return confirm('Tandai komplain ini sebagai selesai?')">
                                                        <i class="fas fa-check mr-1"></i>Selesai
                                                    </button>
                                                </form>
                                                <a href="{{ route('complaints.escalate-form', $complaint) }}" class="text-white bg-red-600 hover:bg-red-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Eskalasi
                                                </a>
                                                @if($complaint->handled_by === auth()->id() && auth()->user()->role === 'cs')
                                                    <form method="POST" action="{{ route('complaints.release', $complaint) }}" class="inline-block">
                                                        @csrf
                                                        <button type="submit" class="text-white bg-gray-600 hover:bg-gray-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center" onclick="return confirm('Kembalikan komplain ini agar bisa diambil CS lain?')">
                                                            <i class="fas fa-undo mr-1"></i>Kembalikan
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <!-- CS lain hanya bisa lihat, tidak bisa action -->
                                                <span class="text-gray-500 text-xs">Ditangani {{ $complaint->handledBy->name ?? 'CS lain' }}</span>
                                            @endif
                                        @else
                                            <!-- Jika sudah dieskalasi, cek status manager action -->
                                            @if($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))
                                                <!-- Manager sudah selesaikan masalah, CS perlu follow up ke customer -->
                                                @if($complaint->handled_by === auth()->id() || auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                                                    <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center" onclick="return confirm('Konfirmasi bahwa customer sudah diberi feedback dan komplain selesai?')">
                                                            <i class="fas fa-check mr-1"></i>Konfirmasi Selesai
                                                        </button>
                                                    </form>
                                                    <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded ml-2">
                                                        <i class="fas fa-info-circle mr-1"></i>Manager Sudah Tangani
                                                    </span>
                                                @else
                                                    <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded font-medium">
                                                        <i class="fas fa-check-circle mr-1"></i>Manager Selesai - Menunggu CS Follow Up
                                                    </span>
                                                @endif
                                            @elseif($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: return_to_cs'))
                                                <!-- Manager kembalikan ke CS untuk ditangani lebih lanjut -->
                                                @if($complaint->handled_by === auth()->id() || auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                                                    <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 transition-colors duration-200 px-3 py-1 rounded text-xs inline-flex items-center" onclick="return confirm('Tandai komplain ini sebagai selesai?')">
                                                            <i class="fas fa-check mr-1"></i>Selesai
                                                        </button>
                                                    </form>
                                                    <span class="text-blue-600 text-xs bg-blue-100 px-2 py-1 rounded ml-2">
                                                        <i class="fas fa-arrow-left mr-1"></i>Dikembalikan Manager
                                                    </span>
                                                @else
                                                    <span class="text-blue-600 text-xs bg-blue-100 px-2 py-1 rounded font-medium">
                                                        <i class="fas fa-arrow-left mr-1"></i>Dikembalikan - CS {{ $complaint->handledBy->name ?? 'Lain' }} Menangani
                                                    </span>
                                                @endif
                                            @else
                                                <!-- Masih menunggu manager action -->
                                                <span class="text-orange-600 text-xs bg-orange-100 px-2 py-1 rounded font-medium">
                                                    <i class="fas fa-clock mr-1"></i>Menunggu Instruksi Manager
                                                </span>
                                            @endif
                                    @endif
                                </div>
                                @elseif($complaint->status === 'selesai')
                                    <!-- Complaint sudah selesai -->
                                    @if($complaint->escalation_to)
                                        <!-- Jika dieskalasi dan selesai, tampilkan status eskalasi -->
                                        @if($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))
                                            <span class="text-green-600 text-xs bg-green-100 px-2 py-1 rounded font-medium mr-2">
                                                ✓ Sudah Ditangani
                                            </span>
                                        @else
                                            <span class="text-green-600 text-xs mr-2">✓ Selesai</span>
                                        @endif
                                    @else
                                        <span class="text-green-600 text-xs mr-2">✓ Selesai</span>
                                    @endif
                                    
                                    @if(auth()->user()->role === 'admin')
                                        <form method="POST" action="{{ route('complaints.destroy', $complaint) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komplain ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200 text-xs">Hapus</button>
                                        </form>
                                    @endif
                                @endif
                                
                                @if($complaint->handled_by && $complaint->status === 'baru' && (auth()->user()->role === 'cs' || auth()->user()->role === 'admin' || auth()->user()->role === 'manager'))
                                    <!-- Edit button untuk CS/Admin/Manager pada complaint status baru yang sudah diambil -->
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

<!-- Custom CSS for Modal Dropdowns -->
<style>
/* Force dropdown to open downward */
#exportModal select {
    position: relative !important;
}

/* Custom dropdown arrow */
#exportModal .dropdown-arrow::after {
    content: '';
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 4px solid #666;
    pointer-events: none;
}

/* Ensure modal content has enough space */
#exportModal .modal-content {
    max-height: 90vh;
    overflow-y: auto;
}

/* Fix select dropdown positioning */
#exportModal select option {
    padding: 8px 12px;
    background: white;
    color: #333;
}

/* Prevent dropdown from going upward */
#exportModal .relative {
    overflow: visible;
}
</style>

<!-- Export PDF Modal - Vercel Style -->
<div id="exportModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity" onclick="hideExportModal()"></div>
    
    <!-- Modal Container -->
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-start justify-center p-4 pt-16">
            <!-- Modal Content -->
            <div class="relative w-full max-w-md transform overflow-visible rounded-xl bg-white shadow-2xl transition-all modal-content">
                <!-- Header -->
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Filter Export PDF</h3>
                            <p class="text-sm text-gray-500 mt-1">Pilih filter untuk laporan komplain</p>
                        </div>
                        <button onclick="hideExportModal()" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Form -->
                <form method="GET" action="{{ route('complaints.export-pdf') }}" id="exportForm">
                    <div class="px-6 py-4 space-y-5">
                        <!-- Date Range -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" 
                                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all hover:border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                                <input type="date" name="end_date" 
                                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all hover:border-gray-300">
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="relative">
                                <select name="status" 
                                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all hover:border-gray-300 bg-white appearance-none pr-8"
                                        style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 4 5&quot;><path fill=&quot;%23666&quot; d=&quot;M2 0L0 2h4zm0 5L0 3h4z&quot;/></svg>'); background-repeat: no-repeat; background-position: right 8px center; background-size: 12px;">
                                    @if(auth()->user()->role === 'cs')
                                        <option value="">Semua Status</option>
                                        <option value="diproses">Diproses</option>
                                        <option value="selesai">Selesai</option>
                                    @else
                                        <option value="">Semua Status</option>
                                        <option value="baru">Baru</option>
                                        <option value="diproses">Diproses</option>
                                        <option value="selesai">Selesai</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <div class="relative">
                                <select name="category" 
                                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all hover:border-gray-300 bg-white appearance-none pr-8"
                                        style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 4 5&quot;><path fill=&quot;%23666&quot; d=&quot;M2 0L0 2h4zm0 5L0 3h4z&quot;/></svg>'); background-repeat: no-repeat; background-position: right 8px center; background-size: 12px;">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        @if(auth()->user()->role === 'manager' || auth()->user()->role === 'admin')
                        <!-- CS Handler Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Customer Service</label>
                            <div class="relative">
                                <input type="text" 
                                       name="cs_search" 
                                       placeholder="Cari nama CS atau kosongkan untuk semua"
                                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all hover:border-gray-300"
                                       autocomplete="off">
                                <div class="absolute right-3 top-2.5 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Contoh: "Staff CS", "John", "CS 1"</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Footer -->
                    <div class="border-t border-gray-100 px-6 py-4">
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="hideExportModal()" 
                                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all focus:ring-2 focus:ring-gray-200">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2.5 text-sm font-medium text-white bg-black rounded-lg hover:bg-gray-800 transition-all focus:ring-2 focus:ring-gray-400 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download PDF
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showExportModal() {
    const modal = document.getElementById('exportModal');
    modal.classList.remove('hidden');
    
    // Add smooth animation
    requestAnimationFrame(() => {
        modal.querySelector('.fixed.inset-0.bg-black\\/20').style.opacity = '1';
        modal.querySelector('.relative.w-full').style.transform = 'scale(1)';
        modal.querySelector('.relative.w-full').style.opacity = '1';
    });
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function hideExportModal() {
    const modal = document.getElementById('exportModal');
    
    // Animate out
    modal.querySelector('.fixed.inset-0.bg-black\\/20').style.opacity = '0';
    modal.querySelector('.relative.w-full').style.transform = 'scale(0.95)';
    modal.querySelector('.relative.w-full').style.opacity = '0';
    
    // Hide after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 150);
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('exportModal');
        if (!modal.classList.contains('hidden')) {
            hideExportModal();
        }
    }
});

// Initialize modal styles
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('exportModal');
    const backdrop = modal.querySelector('.fixed.inset-0.bg-black\\/20');
    const content = modal.querySelector('.relative.w-full');
    
    // Set initial styles
    backdrop.style.opacity = '0';
    backdrop.style.transition = 'opacity 150ms ease-out';
    
    content.style.opacity = '0';
    content.style.transform = 'scale(0.95)';
    content.style.transition = 'all 150ms ease-out';
});
</script>

@endsection
