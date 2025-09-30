@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Customer</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap customer {{ $customer->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('customers.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus customer ini? Data yang terkait akan ikut terhapus.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Customer Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Customer</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->customer->phone ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Akun</label>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                    @if($customer->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Tidak Aktif
                        </span>
                    @endif
                </p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->customer->address ?? 'Alamat tidak diisi' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Daftar</label>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $customer->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Terakhir Login</label>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                    @if($customer->last_login_at)
                        {{ $customer->last_login_at->format('d M Y H:i') }}
                        <span class="text-xs text-gray-500">({{ $customer->last_login_at->diffForHumans() }})</span>
                    @else
                        <span class="text-gray-500">Belum pernah login</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Complaint History -->
    @if($customer->customer && $customer->customer->complaints->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Riwayat Komplain ({{ $customer->customer->complaints->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($customer->customer->complaints as $complaint)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $complaint->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($complaint->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $complaint->category->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($complaint->status === 'baru')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Baru</span>
                            @elseif($complaint->status === 'diproses')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Diproses</span>
                            @elseif($complaint->status === 'selesai')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('complaints.show', $complaint) }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada komplain</h3>
            <p class="mt-1 text-sm text-gray-500">Customer ini belum pernah mengajukan komplain.</p>
        </div>
    </div>
    @endif
</div>
@endsection