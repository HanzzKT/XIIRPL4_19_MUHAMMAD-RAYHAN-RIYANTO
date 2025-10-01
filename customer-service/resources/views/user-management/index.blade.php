@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                @if(auth()->user()->role === 'admin')
                    Kelola User
                @else
                    Kelola CS Staff
                @endif
            </h1>
            <p class="text-gray-600 mt-1">
                @if(auth()->user()->role === 'admin')
                    Kelola akun CS, Manager, dan Admin
                @else
                    Kelola dan pantau performa CS staff
                @endif
            </p>
        </div>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah User
        </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <form method="GET" class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @if(auth()->user()->role === 'admin')
                        <option value="">Semua Role</option>
                        <option value="cs" {{ request('role') == 'cs' ? 'selected' : '' }}>Customer Service</option>
                        <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    @else
                        <option value="">Semua CS</option>
                        <option value="cs" {{ request('role') == 'cs' ? 'selected' : '' }}>Customer Service</option>
                    @endif
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Cari
                </button>
                <a href="{{ auth()->user()->role === 'manager' ? route('manager.users.index') : route('users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <!-- Semua role bisa melihat detail -->
                            <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">Detail</a>
                            
                            @if(auth()->user()->role === 'admin')
                                <!-- Hanya Admin yang bisa edit dan hapus -->
                                @if($user->role === 'cs' || ($user->role === 'manager' && auth()->user()->role === 'admin'))
                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">Edit</a>
                                @endif
                                
                                @if($user->id !== auth()->id())
                                    @if($user->role === 'cs' || ($user->role === 'manager' && auth()->user()->role === 'admin'))
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')" class="text-red-600 hover:text-red-900 transition-colors duration-200">Hapus</button>
                                        </form>
                                    @elseif($user->role === 'admin' && $user->id !== auth()->id())
                                        <span class="text-gray-400 text-xs">Tidak dapat dihapus</span>
                                    @endif
                                @endif
                            @elseif(auth()->user()->role === 'manager')
                                <!-- Manager hanya bisa melihat, tidak bisa edit/hapus -->
                                <span class="text-gray-500 text-xs">View Only</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada user</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan user baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
