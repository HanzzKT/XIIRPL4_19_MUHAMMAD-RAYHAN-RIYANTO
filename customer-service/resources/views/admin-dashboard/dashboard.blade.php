@extends('layouts.sidebar')

@section('title', 'Admin Dashboard - PT Karunia Laris Abadi')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="space-y-8 p-6">
    <!-- Header with Real-time Clock -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-display font-semibold text-[#171717] tracking-tight">Dashboard Admin</h1>
            <p class="text-[#71717a] mt-1">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
        <div class="hidden md:flex items-center">
            <div id="realTimeClock" class="vercel-card px-4 py-2 text-sm">
                <svg class="w-4 h-4 mr-2 text-[#71717a] inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="currentDateTime" class="font-medium text-[#171717]"></span>
            </div>
        </div>
    </div>

    <!-- Main Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Complaints -->
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Total Komplain</p>
                    <p class="text-2xl font-display font-semibold text-[#171717]">{{ $stats['totalComplaints'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#f4f4f5] rounded-lg flex items-center justify-center group-hover:bg-[#e4e4e7] transition-colors">
                    <svg class="w-5 h-5 text-[#71717a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Escalations -->
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Eskalasi</p>
                    <p class="text-2xl font-display font-semibold text-[#ef4444]">{{ $stats['escalatedComplaints'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#fef2f2] rounded-lg flex items-center justify-center group-hover:bg-[#fee2e2] transition-colors">
                    <svg class="w-5 h-5 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Total Pengguna</p>
                    <p class="text-2xl font-display font-semibold text-[#171717]">{{ $stats['totalUsers'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#f4f4f5] rounded-lg flex items-center justify-center group-hover:bg-[#e4e4e7] transition-colors">
                    <svg class="w-5 h-5 text-[#71717a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Total Pelanggan</p>
                    <p class="text-2xl font-display font-semibold text-[#171717]">{{ $stats['totalCustomers'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#f0fdf4] rounded-lg flex items-center justify-center group-hover:bg-[#dcfce7] transition-colors">
                    <svg class="w-5 h-5 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Categories -->
        <div class="vercel-card group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#71717a] mb-1">Kategori </p>
                    <p class="text-2xl font-display font-semibold text-[#171717]">{{ $stats['totalCategories'] }}</p>
                </div>
                <div class="w-10 h-10 bg-[#fff7ed] rounded-lg flex items-center justify-center group-hover:bg-[#ffedd5] transition-colors">
                    <svg class="w-5 h-5 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Complaints -->
    <div class="vercel-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-display font-semibold text-[#171717]">Recent Complaints</h2>
            <a href="{{ route('complaints.index') }}" class="vercel-button vercel-button-secondary text-sm">
                View All
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto -mx-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#e4e4e7]">
                        <th class="text-left py-3 px-6 text-sm font-medium text-[#71717a]">Date</th>
                        <th class="text-left py-3 px-6 text-sm font-medium text-[#71717a]">Customer</th>
                        <th class="text-left py-3 px-6 text-sm font-medium text-[#71717a]">Complaint</th>
                        <th class="text-left py-3 px-6 text-sm font-medium text-[#71717a]">Status</th>
                        <th class="text-left py-3 px-6 text-sm font-medium text-[#71717a]">CS Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentComplaints as $complaint)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-6 text-sm text-gray-600">
                            {{ $complaint->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3 px-6">
                            <div class="font-medium text-gray-900">{{ $complaint->customer->name }}</div>
                            <div class="text-sm text-gray-500">{{ $complaint->customer->phone }}</div>
                        </td>
                        <td class="py-3 px-6">
                            <div class="font-medium text-gray-900">{{ Str::limit($complaint->description, 40) }}</div>
                            <div class="text-sm text-gray-500">{{ $complaint->category->name }}</div>
                        </td>
                        <td class="py-3 px-6">
                            @php($statusColors = ['baru'=>'bg-red-100 text-red-700', 'diproses'=>'bg-yellow-100 text-yellow-700', 'selesai'=>'bg-green-100 text-green-700'])
                            <span class="px-2 py-1 {{ $statusColors[$complaint->status] ?? 'bg-gray-100 text-gray-700' }} text-xs rounded-full capitalize">
                                {{ $complaint->status }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-sm text-gray-600">
                            {{ $complaint->handledBy->name ?? 'Belum diambil' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Aksi Cepat -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('users.create') }}" 
               class="group bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-indigo-300">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors duration-300">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">Tambah Pengguna</h4>
                        <p class="text-xs text-gray-600">Buat akun pengguna baru</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('complaint-categories.create') }}" 
               class="group bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200 hover:border-purple-300">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors duration-300">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-300">Tambah Kategori</h4>
                        <p class="text-xs text-gray-600">Buat kategori komplain baru</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Users and System Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pengguna Terbaru</h3>
                <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-bold text-blue-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $user->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($user->role === 'admin') bg-red-100 text-red-700
                            @elseif($user->role === 'manager') bg-purple-100 text-purple-700
                            @elseif($user->role === 'cs') bg-blue-100 text-blue-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($user->is_active) Aktif @else Tidak Aktif @endif
                        </p>
                        <p class="text-xs text-gray-500">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Komplain baru ditambahkan</p>
                        <p class="text-xs text-gray-500">{{ now()->format('H:i') }} - Sari Dewi</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-green-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Komplain diselesaikan</p>
                        <p class="text-xs text-gray-500">Kemarin - Budi Santoso</p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Pengguna baru terdaftar</p>
                        <p class="text-xs text-gray-500">Hari ini - CS Baru</p>
                    </div>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('complaints.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua Aktivitas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Clock Script -->
<script>
function updateClock() {
    const now = new Date();
    const options = { 
        day: '2-digit', 
        month: 'short', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    };
    const timeString = now.toLocaleDateString('id-ID', options).replace(',', ',');
    document.getElementById('currentDateTime').textContent = timeString;
}

// Update clock immediately and then every second
updateClock();
setInterval(updateClock, 1000);
</script>
@endsection
