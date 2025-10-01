@extends('layouts.sidebar')

@section('title', 'Detail Komplain - PT Karunia Laris Abadi')
@section('page-title', 'Detail Komplain')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Komplain {{ $complaint->id }}</h1>
            <p class="text-sm text-gray-500">Informasi lengkap komplain</p>
        </div>
        <a href="{{ route('complaints.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
        </a>
    </div>

    <!-- Detail Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ID Komplain -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ID Komplain</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">
                        {{ $complaint->id }}
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $complaint->status === 'baru' ? 'bg-red-100 text-red-800' : ($complaint->status === 'diproses' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                        @if($complaint->escalation_to)
                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>DIESKALASI
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">
                        {{ $complaint->category->name ?? 'Lainnya' }}
                    </div>
                </div>

                <!-- Tanggal Dibuat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dibuat</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">
                        {{ $complaint->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <!-- Pelanggan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pelanggan</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">
                        {{ $complaint->customer->name ?? 'Customer tidak ditemukan' }}
                    </div>
                </div>

                <!-- Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">
                        {{ $complaint->customer_phone ?? '-' }}
                    </div>
                </div>

                <!-- Ditangani Oleh -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ditangani Oleh</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">
                        {{ $complaint->handledBy->name ?? 'Belum ditangani' }}
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900 min-h-[100px]">
                        {{ $complaint->description ?? 'Tidak ada deskripsi' }}
                    </div>
                </div>


                <!-- Escalation & Manager Action Section -->
                @if($complaint->escalation_to)
                <div class="md:col-span-2">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-red-800 mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Eskalasi & Tindakan Manager
                        </h3>
                        
                        <!-- Escalation Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-4">
                            <div>
                                <span class="font-medium text-red-700">Dieskalasi ke:</span>
                                <span class="text-red-900">{{ $complaint->escalatedTo->name ?? 'Manager CS' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-red-700">Tanggal Eskalasi:</span>
                                <span class="text-red-900">{{ $complaint->escalated_at ? $complaint->escalated_at->format('d M Y, H:i') : '-' }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="font-medium text-red-700">Alasan Eskalasi:</span>
                                <p class="text-red-900 mt-1">{{ $complaint->escalation_reason ?? 'Tidak ada alasan yang dicatat' }}</p>
                            </div>
                            <div>
                                <span class="font-medium text-red-700">Dieskalasi oleh:</span>
                                <span class="text-red-900">{{ $complaint->escalatedBy->name ?? 'Staff CS' }}</span>
                            </div>
                        </div>
                        
                        <!-- Manager Action (if exists) -->
                        @if($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action:'))
                        <div class="border-t border-red-200 pt-4">
                            <div class="text-sm">
                                <div class="mb-3">
                                    <span class="font-medium text-red-700">Tindakan Manager:</span>
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ str_contains($complaint->action_notes, 'resolved') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        @if(str_contains($complaint->action_notes, 'resolved'))
                                            <i class="fas fa-check-circle mr-1"></i>Masalah Ditangani
                                        @else
                                            <i class="fas fa-arrow-left mr-1"></i>Dikembalikan ke CS
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium text-red-700">Detail Tindakan:</span>
                                    <p class="text-red-900 mt-1 bg-white bg-opacity-50 p-2 rounded">{{ $complaint->action_notes }}</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="border-t border-red-200 pt-4">
                            <p class="text-sm text-red-700 italic">
                                <i class="fas fa-clock mr-1"></i>Menunggu tindakan dari Manager
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- CS Response Section -->
                @if(in_array(auth()->user()->role, ['cs', 'admin']) && $complaint->handled_by && (!$complaint->escalation_to || ($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))))
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-dots mr-2 text-blue-600"></i>Response untuk Customer
                    </label>
                    
                    @if($complaint->cs_response)
                        <!-- Display existing response -->
                        <div class="px-3 py-2 border border-green-300 rounded-md bg-green-50 text-gray-900 min-h-[80px] mb-3">
                            {{ $complaint->cs_response }}
                            @if($complaint->cs_response_updated_at)
                                <div class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>Diupdate: {{ $complaint->cs_response_updated_at->format('d M Y, H:i') }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Edit response form -->
                        <form method="POST" action="{{ route('complaints.update-response', $complaint) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <textarea name="cs_response" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Update response untuk customer...">{{ $complaint->cs_response }}</textarea>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-edit mr-2"></i>Update Response
                            </button>
                        </form>
                    @else
                        <!-- Add new response form -->
                        <form method="POST" action="{{ route('complaints.update-response', $complaint) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <textarea name="cs_response" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis response untuk customer..." required></textarea>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Response
                            </button>
                        </form>
                    @endif
                </div>
                @elseif($complaint->cs_response)
                <!-- Display response for non-CS users -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-dots mr-2 text-blue-600"></i>Response CS
                    </label>
                    <div class="px-3 py-2 border border-green-300 rounded-md bg-green-50 text-gray-900 min-h-[80px]">
                        {{ $complaint->cs_response }}
                        @if($complaint->cs_response_updated_at)
                            <div class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-clock mr-1"></i>{{ $complaint->cs_response_updated_at->format('d M Y, H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($complaint->resolved_at)
                <!-- Tanggal Selesai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <div class="px-3 py-2 border border-green-300 rounded-md bg-green-50 text-gray-900">
                        {{ $complaint->resolved_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <!-- Diselesaikan Oleh -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diselesaikan Oleh</label>
                    <div class="px-3 py-2 border border-green-300 rounded-md bg-green-50 text-gray-900">
                        {{ $complaint->resolvedBy->name ?? '-' }}
                    </div>
                </div>
                @endif

            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200 flex gap-3">
                @if($complaint->status !== 'selesai')
                    @if(!$complaint->handled_by)
                        @if(auth()->user()->role === 'cs')
                            <form method="POST" action="{{ route('complaints.take', $complaint) }}" class="inline-block">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="return confirm('Apakah Anda yakin ingin mengambil komplain ini?')">
                                    <i class="fas fa-hand-paper mr-2"></i>Ambil Komplain
                                </button>
                            </form>
                        @endif
                    @else
                        @if($complaint->status === 'baru')
                            <a href="{{ route('complaints.edit', $complaint) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                            <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="diproses">
                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-play mr-2"></i>Mulai Proses
                                </button>
                            </form>
                        @elseif($complaint->status === 'diproses')
                            @if(!$complaint->escalation_to)
                                <!-- Jika belum dieskalasi, CS bisa menyelesaikan -->
                                <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="selesai">
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-check mr-2"></i>Selesaikan
                                    </button>
                                </form>
                                
                                @if(auth()->user()->role === 'cs')
                                    <a href="{{ route('complaints.escalate-form', $complaint) }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors inline-flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Eskalasi ke Manager
                                    </a>
                                @endif
                            @else
                                <!-- Jika sudah dieskalasi, Manager menangani masalah tapi CS yang memberikan feedback -->
                                @if(auth()->user()->role === 'manager')
                                    @if(!($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action:')))
                                        <!-- Manager belum memberikan action -->
                                        <a href="{{ route('complaints.manager-action-form', $complaint) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center">
                                            <i class="fas fa-cogs mr-2"></i>Tangani Masalah
                                        </a>
                                    @elseif($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))
                                        <div class="px-4 py-2 bg-purple-100 text-purple-800 rounded-lg">
                                            <i class="fas fa-check-circle mr-2"></i>Masalah sudah ditangani - Menunggu CS memberikan feedback final
                                        </div>
                                    @elseif($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: return_to_cs'))
                                        <div class="px-4 py-2 bg-orange-100 text-orange-800 rounded-lg">
                                            <i class="fas fa-arrow-left mr-2"></i>Dikembalikan ke CS untuk penanganan lebih lanjut
                                        </div>
                                    @endif
                                @endif
                                
                                @if(auth()->user()->role === 'cs')
                                    @if($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))
                                        <!-- Manager sudah menangani, CS bisa memberikan feedback final -->
                                        <div class="flex flex-col space-y-3">
                                            @if(!$complaint->cs_response)
                                                <div class="px-4 py-2 bg-blue-50 text-blue-800 rounded-lg text-sm">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    <strong>Catatan:</strong> Berikan feedback ke customer melalui form "Response untuk Customer" di atas sebelum menyelesaikan komplain.
                                                </div>
                                            @endif
                                            <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                                    <i class="fas fa-check mr-2"></i>Selesaikan Komplain
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="px-4 py-2 bg-orange-100 text-orange-800 rounded-lg">
                                            <i class="fas fa-clock mr-2"></i>Menunggu Manager menangani masalah
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endif
                @else
                    <div class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>Komplain Telah Selesai
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
