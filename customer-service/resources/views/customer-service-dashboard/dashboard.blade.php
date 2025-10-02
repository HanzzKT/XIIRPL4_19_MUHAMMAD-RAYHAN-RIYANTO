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
                labels: ['Total', 'Baru', 'Diproses', 'Selesai'],
                datasets: [{
                    label: 'Jumlah Komplain',
                    data: [
                        {{ (int)($stats['totalComplaints'] ?? 0) }},
                        {{ (int)($stats['newComplaints'] ?? 0) }},
                        {{ (int)($stats['processingComplaints'] ?? 0) }},
                        {{ (int)($stats['completedComplaints'] ?? 0) }}
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
                        <i class="fas fa-inbox text-lg text-gray-400"></i>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-1">Belum ada komplain</h3>
                    <p class="text-xs text-gray-600 mb-4">Komplain yang masuk akan ditampilkan di sini</p>
                    <a href="{{ route('complaints.index') }}" class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        <i class="fas fa-eye mr-1"></i>
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
