@extends('layouts.sidebar')

@section('title', 'Manager Dashboard - PT Karunia Laris Abadi')
@section('page-title', 'Manager Dashboard')

@section('content')
<div class="space-y-8 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-display font-semibold text-[#171717] tracking-tight">Manager Dashboard</h1>
            <p class="text-[#71717a] mt-1">Welcome back, {{ auth()->user()->name }}!</p>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Total Escalations</p>
                    <p class="text-2xl font-display font-semibold text-[#ef4444]">{{ $stats['totalEscalations'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#fef2f2] rounded-lg flex items-center justify-center group-hover:bg-[#fee2e2] transition-colors">
                    <svg class="w-5 h-5 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Completed</p>
                    <p class="text-2xl font-display font-semibold text-[#22c55e]">{{ $stats['completedEscalations'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#f0fdf4] rounded-lg flex items-center justify-center group-hover:bg-[#dcfce7] transition-colors">
                    <svg class="w-5 h-5 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Completion Rate</p>
                    <p class="text-2xl font-display font-semibold text-[#171717]">{{ number_format($stats['escalationCompletionRate'], 1) }}%</p>
                </div>
                <div class="w-10 h-10 bg-[#f4f4f5] rounded-lg flex items-center justify-center group-hover:bg-[#e4e4e7] transition-colors">
                    <svg class="w-5 h-5 text-[#71717a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Performance Metrics -->
        <div class="vercel-card">
            <div class="mb-6">
                <h2 class="text-xl font-display font-semibold text-[#171717]">Performance Metrics</h2>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-xs text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-sm text-gray-900">Rata-rata Waktu Penyelesaian</h3>
                                <p class="text-xs text-gray-600">Per komplain yang diselesaikan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">2.5</p>
                            <p class="text-xs text-gray-600">hari</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-percentage text-xs text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-sm text-gray-900">Tingkat Penyelesaian</h3>
                                <p class="text-xs text-gray-600">Komplain yang diselesaikan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">{{ $stats['escalationCompletionRate'] }}%</p>
                            <p class="text-xs text-gray-600">dari total</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-xs text-purple-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-sm text-gray-900">Total CS Aktif</h3>
                                <p class="text-xs text-gray-600">Staff customer service</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-purple-600">{{ $stats['activeCS'] }}</p>
                            <p class="text-xs text-gray-600">orang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Complaints -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Komplain Terbaru</h2>
                    <a href="{{ route('complaints.index') }}" class="text-purple-600 hover:text-purple-700 font-medium text-sm transition-colors duration-200">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-4">
                @if($recentComplaints->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentComplaints as $complaint)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-xs text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-sm text-gray-900">{{ $complaint->customer->name }}</h3>
                                    <p class="text-xs text-gray-600">{{ Str::limit($complaint->description, 40) }}</p>
                                    <p class="text-xs text-gray-500">{{ $complaint->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($complaint->escalation_to)
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">DIESKALASI</span>
                                    @if($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action:'))
                                        @if(str_contains($complaint->action_notes, 'resolved'))
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Sudah Ditangani</span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Dikembalikan ke CS</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">Menunggu Tindakan</span>
                                    @endif
                                @endif
                                @if($complaint->status === 'baru')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Baru</span>
                                @elseif($complaint->status === 'diproses')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Diproses</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Selesai</span>
                                @endif
                                <a href="{{ route('complaints.show', $complaint) }}" class="text-purple-600 hover:text-purple-700 transition-colors duration-200">
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-inbox text-lg text-gray-400"></i>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Belum ada eskalasi</h3>
                        <p class="text-xs text-gray-600">Komplain yang dieskalasi akan ditampilkan di sini</p>
                    </div>
                @endif
            </div>
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
