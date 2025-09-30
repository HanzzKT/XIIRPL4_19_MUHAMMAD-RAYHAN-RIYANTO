@extends('layouts.sidebar')

@section('title', 'Manager Dashboard - PT Karunia Laris Abadi')
@section('page-title', 'Dashboard Manager')

@section('content')
<div class="space-y-4">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-6 text-white shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold mb-1">Dashboard Manager</h1>
                <p class="text-purple-100 text-sm">Selamat datang, {{ auth()->user()->name }}!</p>
            </div>
            <div class="hidden md:block">
                <div id="realTimeClock" class="text-sm font-medium text-white bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl">
                    <i class="fas fa-clock mr-2"></i>
                    <span id="currentDateTime">22 Sep 2025, 22:04</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">Total Eskalasi</p>
                    <p class="text-xl font-bold text-red-600">{{ $stats['escalatedComplaints'] }}</p>
                </div>
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-xs text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">Komplain Selesai</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['completedComplaints'] }}</p>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-xs text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-600">Tingkat Penyelesaian</p>
                    <p class="text-xl font-bold text-blue-600">{{ number_format($stats['completionRate'], 1) }}%</p>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-xs text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Performance Metrics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Metrik Performa</h2>
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
                            <p class="text-lg font-bold text-green-600">{{ $stats['completionRate'] }}%</p>
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
                                    @if($complaint->manager_action)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ ucfirst(str_replace('_', ' ', $complaint->manager_action)) }}</span>
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
