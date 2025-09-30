@extends('layouts.sidebar')

@section('title', 'Dashboard Admin - PT Karunia Laris Abadi')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Header with Real-time Clock -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-sm text-gray-500">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
        <div class="hidden md:flex items-center">
            <div id="realTimeClock" class="text-sm font-medium text-gray-700 bg-gray-100 px-4 py-2 rounded-lg">
                <i class="fas fa-clock mr-2 text-blue-600"></i>
                <span id="currentDateTime"></span>
            </div>
        </div>
    </div>

    <!-- Main Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Komplain -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Komplain</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['totalComplaints'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Eskalasi -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Eskalasi</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['escalatedComplaints'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-up text-red-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['totalUsers'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Customer</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['totalCustomers'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-friends text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Kategori Aktif</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['totalCategories'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tags text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Complaints -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Komplain Terbaru</h2>
                <a href="{{ route('complaints.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Tanggal</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Pelanggan</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Komplain</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Status</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">CS</th>
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
                        <i class="fas fa-user-plus text-indigo-600"></i>
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
                        <i class="fas fa-tags text-purple-600"></i>
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
                        <i class="fas fa-plus text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Komplain baru ditambahkan</p>
                        <p class="text-xs text-gray-500">{{ now()->format('H:i') }} - Sari Dewi</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-green-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Komplain diselesaikan</p>
                        <p class="text-xs text-gray-500">Kemarin - Budi Santoso</p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user-plus text-purple-600 text-sm"></i>
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
