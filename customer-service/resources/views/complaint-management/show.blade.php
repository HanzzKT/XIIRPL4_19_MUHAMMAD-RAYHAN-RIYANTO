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

                @if($complaint->location)
                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-900">
                        {{ $complaint->location }}
                    </div>
                </div>
                @endif

                @if($complaint->image_path || $complaint->video_path)
                <!-- Lampiran -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lampiran</label>
                    <div class="space-y-4">
                        @if($complaint->image_path)
                            <div>
                                <img src="{{ asset('storage/'.$complaint->image_path) }}" alt="Foto Komplain" class="max-h-80 rounded-lg border border-gray-200">
                            </div>
                        @endif
                        @if($complaint->video_path)
                            <div>
                                <video controls class="w-full max-h-96 rounded-lg border border-gray-200">
                                    <source src="{{ asset('storage/'.$complaint->video_path) }}" type="video/mp4">
                                    <source src="{{ asset('storage/'.$complaint->video_path) }}" type="video/webm">
                                    Browser Anda tidak mendukung pemutar video.
                                </video>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Manager Return Instructions (when returned to CS) -->
                @if(!$complaint->escalation_to && $complaint->action_notes && str_contains($complaint->action_notes, 'Dikembalikan ke CS'))
                <div class="md:col-span-2">
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-arrow-left text-orange-600 mr-2"></i>
                            <h3 class="text-lg font-semibold text-gray-900">Instruksi dari Manager</h3>
                        </div>
                        @php
                            // Extract instruksi dari action_notes
                            // Format: "Dikembalikan ke CS oleh {name} - Instruksi: {instruksi} pada {tanggal}"
                            $managerName = '';
                            $instruksi = '';
                            $tanggal = '';
                            
                            if (str_contains($complaint->action_notes, ' - Instruksi: ')) {
                                $parts = explode(' - Instruksi: ', $complaint->action_notes);
                                
                                // Extract manager name dari bagian pertama
                                if (str_contains($parts[0], 'oleh ')) {
                                    $nameParts = explode('oleh ', $parts[0]);
                                    $managerName = trim($nameParts[1]);
                                }
                                
                                // Extract instruksi dan tanggal dari bagian kedua
                                if (isset($parts[1]) && str_contains($parts[1], ' pada ')) {
                                    $instruksiParts = explode(' pada ', $parts[1]);
                                    $instruksi = trim($instruksiParts[0]);
                                    $tanggal = isset($instruksiParts[1]) ? trim($instruksiParts[1]) : '';
                                }
                            } else {
                                // Jika tidak ada instruksi, tampilkan pesan default
                                if (str_contains($complaint->action_notes, 'oleh ')) {
                                    $nameParts = explode('oleh ', $complaint->action_notes);
                                    $managerName = trim(str_replace(' pada', '', $nameParts[1]));
                                }
                                $instruksi = 'Tidak ada instruksi khusus';
                            }
                        @endphp
                        <div class="space-y-2">
                            @if($managerName)
                            <div class="text-sm text-gray-600">
                                <strong>Manager:</strong> {{ $managerName }}
                            </div>
                            @endif
                            <div class="px-4 py-3 bg-white border border-orange-300 rounded-lg">
                                <p class="text-gray-900">{{ $instruksi }}</p>
                            </div>
                            @if($tanggal)
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>{{ $tanggal }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Escalation Section -->
                @if($complaint->escalation_to)
                <div class="md:col-span-2">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Eskalasi</h3>
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-orange-100 text-orange-800">
                                Dieskalasi
                            </span>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Eskalasi Diambil Oleh</span>
                                <span class="font-medium text-gray-900">
                                    {{ $complaint->manager_claimed_by ? $complaint->managerClaimedBy->name : 'Menunggu manager mengambil' }}
                                </span>
                            </div>
                            
                            @if($complaint->escalated_at)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Tanggal Eskalasi</span>
                                <span class="text-gray-900">{{ $complaint->escalated_at->format('d M Y, H:i') }}</span>
                            </div>
                            @endif
                            
                            @if($complaint->escalation_reason)
                            <div class="py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600 block mb-1">Alasan Eskalasi</span>
                                <p class="text-gray-900">{{ $complaint->escalation_reason }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Manager Action -->
                        @if($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Status Tindakan</span>
                                <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    Masalah Ditangani Manager
                                </span>
                            </div>
                            @if($complaint->manager_claimed_at)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Waktu tindakan</span>
                                <span class="text-gray-900">{{ $complaint->manager_claimed_at->format('d M Y, H:i') }}</span>
                            </div>
                            @endif
                            
                            <!-- Instruksi dari Manager -->
                            @php
                                // Extract feedback dari action_notes
                                // Format: "Manager Action: resolved by {name} - Notes: {feedback}"
                                $managerFeedback = '';
                                $managerName = '';
                                
                                if (str_contains($complaint->action_notes, ' - Notes: ')) {
                                    $parts = explode(' - Notes: ', $complaint->action_notes);
                                    $managerFeedback = trim($parts[1]);
                                    
                                    // Extract manager name
                                    if (str_contains($parts[0], ' by ')) {
                                        $nameParts = explode(' by ', $parts[0]);
                                        $managerName = trim($nameParts[1]);
                                    }
                                }
                            @endphp
                            @if($managerFeedback)
                            <div class="py-3 mt-2">
                                <span class="text-sm font-medium text-gray-700 mb-2 block">
                                    <i class="fas fa-clipboard-list mr-2 text-purple-600"></i>Instruksi dari Manager
                                    @if($managerName)
                                        <span class="text-xs text-gray-500">({{ $managerName }})</span>
                                    @endif
                                </span>
                                <div class="px-4 py-3 bg-purple-50 border border-purple-200 rounded-lg text-gray-900">
                                    {{ $managerFeedback }}
                                </div>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            @if(auth()->user()->role === 'manager' && !$complaint->manager_claimed_by)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Menunggu tindakan</span>
                                    <form method="POST" action="{{ route('complaints.claim-escalation', $complaint) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                            Ambil
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <span class="text-sm text-gray-500">Menunggu tindakan manager</span>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- CS Response Section -->
                @if(in_array(auth()->user()->role, ['cs', 'admin']) && $complaint->handled_by && !$complaint->escalation_to)
                <div class="md:col-span-2" id="cs-response">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-dots mr-2 text-blue-600"></i>Respon untuk Customer
                    </label>

                    @if($complaint->cs_response)
                        <div class="px-3 py-2 border border-green-300 rounded-md bg-green-50 text-gray-900 min-h-[60px] mb-3 text-sm">
                            {{ $complaint->cs_response }}
                            @if($complaint->cs_response_updated_at)
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>Diupdate: {{ $complaint->cs_response_updated_at->format('d M Y, H:i') }}
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($complaint->status !== 'selesai')
                    <form method="POST" action="{{ route('complaints.update-response', $complaint) }}" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <textarea name="cs_response" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Tulis respon untuk customer...">{{ $complaint->cs_response }}</textarea>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm inline-flex items-center">
                            <i class="fas fa-save mr-2"></i>{{ $complaint->cs_response ? 'Update Respon' : 'Simpan Respon' }}
                        </button>
                    </form>
                    @endif
                </div>
                @elseif($complaint->cs_response)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment-dots mr-2 text-blue-600"></i>Response CS
                    </label>
                    <div class="px-3 py-2 border border-green-300 rounded-md bg-green-50 text-gray-900 min-h-[80px] text-sm">
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
                                <!-- Jika tidak sedang dieskalasi (belum dieskalasi atau sudah dikembalikan ke CS) -->
                                @if(auth()->user()->role === 'cs' && $complaint->handled_by === auth()->id())
                                    @if($complaint->cs_response)
                                        <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <input type="hidden" name="cs_response" value="{{ $complaint->cs_response }}">
                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-flex items-center" onclick="return confirm('Tandai komplain ini sebagai selesai?')">
                                                <i class="fas fa-check mr-2"></i>Selesaikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm cursor-not-allowed inline-flex items-center" title="Isi respon terlebih dahulu sebelum menyelesaikan">
                                            <i class="fas fa-lock mr-2"></i>Selesaikan
                                        </span>
                                    @endif
                                    
                                    <a href="{{ route('complaints.escalate-form', $complaint) }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors inline-flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Eskalasi ke Manager
                                    </a>
                                @elseif(auth()->user()->role === 'manager')
                                    <div class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg">
                                        <i class="fas fa-info-circle mr-2"></i>Komplain sedang ditangani oleh CS
                                    </div>
                                @endif
                            @else
                                <!-- Jika sudah dieskalasi, Manager menangani masalah tapi CS yang memberikan feedback -->
                                @if(auth()->user()->role === 'manager')
                                    @if(!$complaint->action_notes)
                                        <!-- Manager belum memberikan action -->
                                        @if($complaint->manager_claimed_by === auth()->id())
                                            <a href="{{ route('complaints.manager-action-form', $complaint) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center">
                                                <i class="fas fa-clipboard-list mr-2"></i>Berikan Instruksi
                                            </a>
                                            <form method="POST" action="{{ route('complaints.release-escalation', $complaint) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors" onclick="return confirm('Apakah Anda yakin ingin melepas eskalasi ini?')">
                                                    <i class="fas fa-hand-paper mr-2"></i>Lepas Eskalasi
                                                </button>
                                            </form>
                                        @elseif(!$complaint->manager_claimed_by)
                                            <form method="POST" action="{{ route('complaints.claim-escalation', $complaint) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-hand-paper mr-2"></i>Ambil Eskalasi
                                                </button>
                                            </form>
                                        @else
                                            <div class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg">
                                                <i class="fas fa-lock mr-2"></i>Eskalasi sudah diambil oleh {{ $complaint->managerClaimedBy->name }}
                                            </div>
                                        @endif
                                    @elseif($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))
                                        <div class="px-4 py-2 bg-purple-100 text-purple-800 rounded-lg">
                                            <i class="fas fa-check-circle mr-2"></i>Masalah sudah ditangani oleh Manager
                                        </div>
                                    @endif
                                @endif
                                
                                @if(auth()->user()->role === 'cs')
                                    @if($complaint->action_notes && str_contains($complaint->action_notes, 'Manager Action: resolved'))
                                        {{-- Manager sudah menangani, CS harus isi komentar lalu selesaikan --}}
                                        <div class="w-full">
                                            <form method="POST" action="{{ route('complaints.update-status', $complaint) }}" class="space-y-3" id="form-selesaikan-escalated">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="selesai">

                                                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                                    <div class="flex items-center mb-3">
                                                        <i class="fas fa-comment-check text-green-600 mr-2"></i>
                                                        <span class="text-sm font-semibold text-gray-800">Komentar Penutup CS <span class="text-red-500">*</span></span>
                                                    </div>
                                                    <textarea
                                                        name="cs_response"
                                                        id="cs-closing-response"
                                                        rows="3"
                                                        required
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm resize-none"
                                                        placeholder="Tulis komentar penutup untuk customer sebelum menyelesaikan komplain ini...">{{ $complaint->cs_response }}</textarea>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <i class="fas fa-info-circle mr-1"></i>Komentar ini wajib diisi dan akan dikirim ke customer sebagai respons akhir.
                                                    </p>
                                                </div>

                                                <button type="submit"
                                                    class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-flex items-center font-medium shadow-sm"
                                                    onclick="return validateAndConfirm()">
                                                    <i class="fas fa-check-circle mr-2"></i>Selesaikan Komplain
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const responseSection = document.getElementById('cs-response');
    const textarea = responseSection ? responseSection.querySelector('textarea[name="cs_response"]') : null;

    // Auto-scroll and focus textarea if arrived via #cs-response anchor
    if (window.location.hash === '#cs-response' && responseSection) {
        setTimeout(function () {
            responseSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            if (textarea) textarea.focus();
        }, 200);
    }

    // Auto-scroll to closing form if manager already resolved
    const closingForm = document.getElementById('form-selesaikan-escalated');
    if (closingForm) {
        const closingTextarea = document.getElementById('cs-closing-response');
        if (closingTextarea) {
            closingTextarea.addEventListener('input', function () {
                this.style.borderColor = this.value.trim() ? '#16a34a' : '#d1d5db';
            });
        }
    }
});

function validateAndConfirm() {
    const textarea = document.getElementById('cs-closing-response');
    if (!textarea) return true;

    const val = textarea.value.trim();
    if (!val) {
        textarea.focus();
        textarea.style.borderColor = '#dc2626';
        textarea.style.boxShadow = '0 0 0 2px rgba(220,38,38,0.2)';
        alert('Komentar penutup CS wajib diisi sebelum menyelesaikan komplain!');
        return false;
    }
    return confirm('Pastikan komentar sudah sesuai. Tandai komplain ini sebagai selesai?');
}
</script>

@endsection
