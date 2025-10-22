@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap user {{ $user->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ auth()->user()->role === 'manager' ? route('manager.users.index') : route('users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
                Kembali
            </a>
            @if(auth()->user()->role === 'admin')
                <!-- Hanya Admin yang bisa edit dan hapus -->
                @if($user->role === 'cs' || ($user->role === 'manager' && auth()->user()->role === 'admin'))
                    <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Edit
                    </a>
                @endif
                @if($user->id !== auth()->id() && ($user->role === 'cs' || $user->role === 'manager'))
                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Hapus
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <!-- User Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi User</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                    {{ $user->name }}
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                    {{ $user->email }}
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                    @php
                        $roleColors = [
                            'cs' => 'bg-green-100 text-green-800',
                            'manager' => 'bg-blue-100 text-blue-800', 
                            'admin' => 'bg-red-100 text-red-800'
                        ];
                        $roleNames = [
                            'cs' => 'Customer Service',
                            'manager' => 'Manager',
                            'admin' => 'Admin'
                        ];
                    @endphp
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $roleNames[$user->role] ?? ucfirst($user->role) }}
                    </span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Akun</label>
                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bergabung</label>
                <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-900">
                    {{ $user->created_at->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    @if($user->role === 'cs')
    <!-- Performance Statistics -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik Kinerja</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $user->handledComplaints->count() }}</div>
                <div class="text-sm text-gray-600">Komplain Ditangani</div>
            </div>
            <div class="text-center">
                @php
                    $completedFromHandled = $user->handledComplaints->where('status', 'selesai')->count();
                @endphp
                <div class="text-2xl font-bold text-teal-600">{{ $completedFromHandled }}</div>
                <div class="text-sm text-gray-600">Selesai dari Ditangani</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $user->resolvedComplaints->count() }}</div>
                <div class="text-sm text-gray-600">Diselesaikan Sendiri</div>
            </div>
            <div class="text-center">
                @php
                    $totalHandled = $user->handledComplaints->count();
                    $successRate = $totalHandled > 0 ? round(($completedFromHandled / $totalHandled) * 100, 1) : 0;
                @endphp
                <div class="text-2xl font-bold text-purple-600">{{ $successRate }}%</div>
                <div class="text-sm text-gray-600">Tingkat Penyelesaian</div>
            </div>
        </div>
       </div>
    @endif
</div>
@endsection
