@extends('layouts.sidebar')

@section('title', 'Manager Dashboard - PT Karunia Laris Abadi')
@section('page-title', 'Manager Dashboard')

@section('content')
<div class="space-y-8 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-display font-semibold text-[#171717] tracking-tight">Dashboard Manager</h1>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Total Eskalasi</p>
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
                    <p class="text-sm text-[#71717a] mb-1">Selesai</p>
                    <p class="text-2xl font-display font-semibold text-[#22c55e]">{{ $stats['completedComplaints'] }}</p>
                    <p class="text-xs text-[#71717a] mt-1">Total sistem: {{ $stats['totalComplaints'] }}</p>
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
                    <p class="text-sm text-[#71717a] mb-1">Skor Penyelesaian</p>
                    <p class="text-2xl font-display font-semibold text-[#171717]">{{ number_format($stats['systemCompletionRate'], 1) }}%</p>
                    <p class="text-xs text-[#71717a] mt-1">Eskalasi: {{ number_format($stats['escalationCompletionRate'], 1) }}%</p>
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
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-sm text-gray-900">Rata-rata Waktu Penyelesaian</h3>
                                <p class="text-xs text-gray-600">Per komplain yang diselesaikan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">{{ $stats['avgResolutionTime'] ?? '0' }}</p>
                            <p class="text-xs text-gray-600">{{ $stats['avgResolutionUnit'] ?? 'jam' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-medium text-sm text-gray-900">Rasio Penyelesaian</h3>
                                <p class="text-xs text-gray-600">Komplain yang diselesaikan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">{{ number_format($stats['systemCompletionRate'], 1) }}%</p>
                            <p class="text-xs text-gray-600">dari total</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
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
                    <h2 class="text-lg font-semibold text-gray-900">Komplain Baru</h2>
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
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-semibold text-purple-600">
                                        {{ strtoupper(substr($complaint->customer->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $complaint->customer->name)[1] ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-sm text-gray-900">{{ $complaint->customer->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $complaint->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1">
                                @if($complaint->escalation_to)
                                    @if($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action:'))
                                        @if(str_contains($complaint->action_notes, 'resolved'))
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                </svg>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                </svg>
                                                Kembali
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            </svg>
                                            Tunggu
                                        </span>
                                    @endif
                                @else
                                    @if($complaint->status === 'baru')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Baru</span>
                                    @elseif($complaint->status === 'diproses')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Proses</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Selesai</span>
                                    @endif
                                @endif
                                <a href="{{ route('complaints.show', $complaint) }}" class="text-purple-600 hover:text-purple-700 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Belum ada komplain baru</h3>
                        <p class="text-xs text-gray-600">Komplain baru akan ditampilkan di sini</p>
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
