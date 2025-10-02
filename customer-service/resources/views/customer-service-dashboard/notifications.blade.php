@extends('layouts.sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifikasi WhatsApp</h1>
                <p class="text-gray-600 mt-1">Komplain baru dari WhatsApp yang perlu ditindaklanjuti</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $newWhatsAppComplaints }} Baru
                </span>
                <button onclick="refreshNotifications()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- WhatsApp Complaints List (modern) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Komplain WhatsApp Terbaru</h2>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($whatsappComplaints as $complaint)
            <div class="p-5 hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                <span class="text-sm font-medium text-green-600">WhatsApp</span>
                            </div>
                            @php($dot = match($complaint->status){ 'baru'=>'bg-red-500', 'diproses'=>'bg-yellow-500', 'selesai'=>'bg-green-500', default=>'bg-gray-400' })
                            <span class="inline-flex items-center gap-1 text-xs text-gray-700">
                                <span class="w-2 h-2 rounded-full {{ $dot }}"></span>
                                <span class="capitalize">{{ $complaint->status }}</span>
                            </span>
                            <span class="text-sm text-gray-500">{{ $complaint->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <h3 class="text-base font-semibold text-gray-900 mb-2">{{ $complaint->title }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Customer:</span> {{ $complaint->customer->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Telepon:</span> {{ $complaint->customer_phone }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Kategori:</span> {{ $complaint->category->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">ID Komplain:</span> {{ $complaint->id }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                            <p class="text-sm text-gray-700">{{ Str::limit($complaint->description, 100) }}</p>
                        </div>
                        
                        @if($complaint->action_notes)
                        <div class="bg-blue-50 rounded-lg p-3 mb-3">
                            <p class="text-sm text-blue-700">
                                <span class="font-medium">Catatan:</span> {{ $complaint->action_notes }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center space-x-2">
                        @if($complaint->handledBy)
                        <span class="text-sm text-gray-600">
                            Ditangani oleh: <span class="font-medium">{{ $complaint->handledBy->name }}</span>
                        </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('complaints.show', $complaint) }}" 
                           class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs transition-colors duration-200">
                            Lihat Detail
                        </a>
                        
                        @if($complaint->status === 'baru')
                        <form action="{{ route('complaints.take', $complaint) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengambil komplain ini?')"
                                    class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-xs transition-colors duration-200">
                                Ambil Komplain
                            </button>
                        </form>
                        @endif
                        
                        <button onclick="sendWhatsAppReply({{ $complaint->id }}, '{{ $complaint->customer_phone }}')" 
                                class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded text-xs transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1 inline" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            Balas WA
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada komplain WhatsApp baru</h3>
                <p class="text-gray-600">Semua komplain WhatsApp sudah ditangani</p>
            </div>
            @endforelse
        </div>
        
        @if($whatsappComplaints->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $whatsappComplaints->links() }}
        </div>
        @endif
    </div>
</div>

<!-- WhatsApp Reply Modal -->
<div id="whatsappReplyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Kirim Pesan WhatsApp</h3>
            </div>
            <form id="whatsappReplyForm" action="/whatsapp/send-reply" method="POST">
                @csrf
                <div class="p-6">
                    <input type="hidden" id="complaintId" name="complaint_id">
                    <input type="hidden" id="phoneNumber" name="phone_number">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                        <textarea id="replyMessage" name="message" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Ketik pesan balasan..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Template Cepat</label>
                        <select id="messageTemplate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih template...</option>
                            <option value="sedang_diproses">Sedang Diproses</option>
                            <option value="butuh_info">Butuh Informasi Tambahan</option>
                            <option value="akan_datang">Teknisi Akan Datang</option>
                            <option value="selesai">Komplain Selesai</option>
                        </select>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end space-x-3">
                    <button type="button" onclick="closeWhatsAppModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Message templates
const messageTemplates = {
    'sedang_diproses': 'Komplain Anda sedang kami proses. Tim teknisi kami akan segera menindaklanjuti. Terima kasih atas kesabaran Anda.',
    'butuh_info': 'Untuk menindaklanjuti komplain Anda, kami membutuhkan informasi tambahan. Mohon dapat menghubungi kami kembali.',
    'akan_datang': 'Teknisi kami akan datang ke lokasi Anda dalam 1-2 jam ke depan. Mohon pastikan ada yang menunggu di lokasi.',
    'selesai': 'Komplain Anda telah kami selesaikan. Terima kasih atas kepercayaan Anda kepada PT Karunia Laris Abadi.'
};

// Handle template selection
document.getElementById('messageTemplate').addEventListener('change', function() {
    const template = this.value;
    if (template && messageTemplates[template]) {
        document.getElementById('replyMessage').value = messageTemplates[template];
    }
});

// Open WhatsApp reply modal
function sendWhatsAppReply(complaintId, phoneNumber) {
    document.getElementById('complaintId').value = complaintId;
    document.getElementById('phoneNumber').value = phoneNumber;
    document.getElementById('whatsappReplyModal').classList.remove('hidden');
}

// Close WhatsApp modal
function closeWhatsAppModal() {
    document.getElementById('whatsappReplyModal').classList.add('hidden');
    document.getElementById('whatsappReplyForm').reset();
}

// Form WhatsApp sekarang menggunakan submit Laravel biasa - tidak ada AJAX

// Fungsi untuk animasi saja - tidak ada AJAX

// Refresh notifications
function refreshNotifications() {
    window.location.reload();
}

// Auto refresh every 30 seconds
setInterval(refreshNotifications, 30000);
</script>
@endsection
