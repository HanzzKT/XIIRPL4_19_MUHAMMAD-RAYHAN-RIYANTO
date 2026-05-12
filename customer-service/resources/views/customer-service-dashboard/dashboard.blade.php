@extends('layouts.sidebar')

@section('title', 'CS Dashboard - PT Karunia Laris Abadi')
@section('page-title', 'Customer Service Dashboard')

@section('content')
<div class="space-y-8 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-display font-semibold text-[#171717] tracking-tight">Dashboard CS</h1>
            <p class="text-[#71717a] mt-1">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
        <div class="hidden md:flex items-center">
            <div id="realTimeClock" class="vercel-card px-4 py-2 text-sm">
                <svg class="w-4 h-4 mr-2 text-[#71717a] inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="currentDateTime" class="font-medium text-[#171717]">22 Sep 2025, 22:04</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions: dihapus sesuai permintaan -->

    <!-- Statistics Chart -->
    <div class="vercel-card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-display font-semibold text-[#171717]">Ringkasan Komplain</h3>
        </div>
        <div class="relative">
            <canvas id="csStatsChart" class="w-full" style="max-height: 280px"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            const ctx = document.getElementById('csStatsChart');
            if (!ctx) return;
            const data = {
                labels: ['Total Komplain', 'Baru', 'Sedang Diproses', 'Selesai'],
                datasets: [{
                    label: 'Jumlah Komplain',
                    data: [
                        {{ (int)($stats['totalComplaints'] ?? 0) }},
                        {{ (int)($stats['newComplaints'] ?? 0) }},
                        {{ (int)($stats['myActiveComplaints'] ?? 0) }},
                        {{ (int)($stats['myResolvedComplaints'] ?? 0) }}
                    ],
                    backgroundColor: ['#2563eb22','#ef444422','#f59e0b22','#22c55e22'],
                    borderColor: ['#2563eb','#ef4444','#f59e0b','#22c55e'],
                    borderWidth: 2,
                    borderRadius: 8,
                    maxBarThickness: 48,
                }]
            };
            new Chart(ctx, {
                type: 'bar',
                data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#111827', titleColor: '#fff', bodyColor: '#fff' }
                    }
                }
            });
        })();
    </script>

    {{-- ===== Skor Kinerja Saya ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        {{-- Skor Penyelesaian --}}
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Skor Penyelesaian</p>
                    <p class="text-2xl font-display font-semibold text-[#171717]">{{ $stats['myCompletionRate'] }}%</p>
                    <p class="text-xs text-[#71717a] mt-1">{{ $stats['myResolvedComplaints'] }} dari {{ $stats['myHandledComplaints'] }} selesai</p>
                </div>
                <div class="w-10 h-10 bg-[#f4f4f5] rounded-lg flex items-center justify-center group-hover:bg-[#e4e4e7] transition-colors">
                    <svg class="w-5 h-5 text-[#71717a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Komplain Baru --}}
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Komplain Baru</p>
                    <p class="text-2xl font-display font-semibold text-[#ef4444]">{{ $stats['newComplaints'] }}</p>
                    <p class="text-xs text-[#71717a] mt-1">Belum diambil CS</p>
                </div>
                <div class="w-10 h-10 bg-[#fef2f2] rounded-lg flex items-center justify-center group-hover:bg-[#fee2e2] transition-colors">
                    <svg class="w-5 h-5 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Sedang Ditangani --}}
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Sedang Ditangani</p>
                    <p class="text-2xl font-display font-semibold text-[#f59e0b]">{{ $stats['myActiveComplaints'] }}</p>
                    <p class="text-xs text-[#71717a] mt-1">Komplain aktif saya</p>
                </div>
                <div class="w-10 h-10 bg-[#fffbeb] rounded-lg flex items-center justify-center group-hover:bg-[#fef3c7] transition-colors">
                    <svg class="w-5 h-5 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Diselesaikan --}}
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Total Diselesaikan</p>
                    <p class="text-2xl font-display font-semibold text-[#22c55e]">{{ $stats['myResolvedComplaints'] }}</p>
                    <p class="text-xs text-[#71717a] mt-1">Komplain yang saya tutup</p>
                </div>
                <div class="w-10 h-10 bg-[#f0fdf4] rounded-lg flex items-center justify-center group-hover:bg-[#dcfce7] transition-colors">
                    <svg class="w-5 h-5 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Complaints (Compact) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Komplain untuk Anda</h2>
                <a href="{{ route('complaints.index') }}" class="text-purple-600 hover:text-purple-700 font-medium text-sm">Lihat Semua</a>
            </div>
            <p class="text-xs text-gray-600 mt-1">Komplain baru yang bisa diambil & komplain yang sedang Anda tangani</p>
        </div>
        <div class="p-2">
            @if($recentComplaints->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach($recentComplaints as $complaint)
                    <li class="py-2 px-2 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                    <span class="text-[10px] font-semibold text-indigo-700">{{ Str::of($complaint->customer->name ?? 'U')->substr(0,1) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $complaint->title }}</p>
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <span>{{ $complaint->customer->name ?? '-' }}</span>
                                        <span>•</span>
                                        <span>{{ $complaint->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ $complaint->category->name ?? 'Umum' }}</span>
                                @if($complaint->status === 'baru' && !$complaint->handled_by)
                                    <span class="inline-flex items-center gap-1 text-xs">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        <span class="text-red-700 font-medium">baru</span>
                                    </span>
                                    <form action="{{ route('complaints.take', $complaint) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengambil komplain ini?')" 
                                                class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                            Ambil
                                        </button>
                                    </form>
                                @elseif($complaint->status === 'diproses' && $complaint->handled_by === auth()->id())
                                    <span class="inline-flex items-center gap-1 text-xs mr-2">
                                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                        <span class="text-yellow-700 font-medium">sedang saya tangani</span>
                                    </span>
                                    @if(!$complaint->escalation_to)
                                        <form action="{{ route('complaints.release', $complaint) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Kembalikan komplain ini agar bisa diambil CS lain?')" 
                                                    class="px-2 py-1 text-xs bg-gray-500 text-white rounded hover:bg-gray-600">
                                                Kembalikan
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-1">Belum ada komplain</h3>
                    <p class="text-xs text-gray-600 mb-4">Komplain yang masuk akan ditampilkan di sini</p>
                    <a href="{{ route('complaints.index') }}" class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Semua Komplain
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Real-time clock function
function updateRealTimeClock() {
    const now = new Date();
    
    // Format tanggal dalam bahasa Indonesia
    const months = [
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    
    const day = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    
    const formattedDateTime = `${day} ${month} ${year}, ${hours}:${minutes}:${seconds}`;
    
    const clockElement = document.getElementById('currentDateTime');
    if (clockElement) {
        clockElement.textContent = formattedDateTime;
    }
}

// Update clock immediately and then every second
updateRealTimeClock();
setInterval(updateRealTimeClock, 1000);

</script>
@endsection
