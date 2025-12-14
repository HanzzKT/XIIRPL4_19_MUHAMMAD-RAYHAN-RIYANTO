<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Komplain</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-3xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Detail Komplain {{ $complaint->id }}</h1>
            <a href="{{ route('home') }}#komplain-saya" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-600">Status</div>
                <span class="px-3 py-1 text-sm font-medium rounded-full 
                    @if($complaint->status === 'baru') bg-red-100 text-red-800
                    @elseif($complaint->status === 'diproses') bg-yellow-100 text-yellow-800
                    @else bg-green-100 text-green-800 @endif">
                    {{ ucfirst($complaint->status) }}
                </span>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <div class="text-sm font-medium text-gray-500">Kategori</div>
                    <div class="text-gray-900">{{ $complaint->category->name ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-sm font-medium text-gray-500">Tanggal Dibuat</div>
                    <div class="text-gray-900">{{ $complaint->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div>
                    <div class="text-sm font-medium text-gray-500">Deskripsi</div>
                    <div class="text-gray-900">{{ $complaint->description ?? 'Tidak ada deskripsi' }}</div>
                </div>

                @if($complaint->location)
                <div>
                    <div class="text-sm font-medium text-gray-500">Lokasi</div>
                    <div class="text-gray-900">{{ $complaint->location }}</div>
                </div>
                @endif

                @if($complaint->image_path || $complaint->video_path)
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-2">Lampiran</div>
                    <div class="flex items-center gap-4 flex-wrap">
                        @if($complaint->image_path)
                        <div>
                            <img src="{{ asset('storage/'.$complaint->image_path) }}" alt="Foto Komplain" class="h-24 w-auto rounded border border-gray-200">
                        </div>
                        @endif
                        @if($complaint->video_path)
                        <div>
                            <video controls class="h-28 rounded border border-gray-200">
                                <source src="{{ asset('storage/'.$complaint->video_path) }}" type="video/mp4">
                                <source src="{{ asset('storage/'.$complaint->video_path) }}" type="video/webm">
                                Browser Anda tidak mendukung pemutar video.
                            </video>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($complaint->action_notes && (str_contains($complaint->action_notes, 'Manager Action: resolved') || str_contains($complaint->action_notes, 'Dikembalikan ke CS')))
                <div>
                    <div class="text-sm font-medium text-gray-500">Response</div>
                    @php
                        $managerName = '';
                        $feedbackText = '';
                        $tanggalInstruksi = '';

                        if (str_contains($complaint->action_notes, 'Manager Action: resolved')) {
                            if (str_contains($complaint->action_notes, ' - Notes: ')) {
                                $parts = explode(' - Notes: ', $complaint->action_notes);
                                $feedbackText = trim($parts[1]);
                                if (str_contains($parts[0], ' by ')) {
                                    $nameParts = explode(' by ', $parts[0]);
                                    $managerName = trim($nameParts[1]);
                                }
                            }
                        } else {
                            if (str_contains($complaint->action_notes, ' - Instruksi: ')) {
                                $parts = explode(' - Instruksi: ', $complaint->action_notes);
                                if (str_contains($parts[0], 'oleh ')) {
                                    $nameParts = explode('oleh ', $parts[0]);
                                    $managerName = trim($nameParts[1]);
                                }
                                if (isset($parts[1])) {
                                    if (str_contains($parts[1], ' pada ')) {
                                        $instruksiParts = explode(' pada ', $parts[1]);
                                        $feedbackText = trim($instruksiParts[0]);
                                        $tanggalInstruksi = isset($instruksiParts[1]) ? trim($instruksiParts[1]) : '';
                                    } else {
                                        $feedbackText = trim($parts[1]);
                                    }
                                }
                            }
                        }
                    @endphp
                    <div class="mt-1 px-4 py-3 bg-green-50 text-green-900 border border-green-200 rounded-lg">
                        {{ $feedbackText ?: 'Tidak ada instruksi khusus' }}
                    </div>
                </div>
                @endif

                @if($complaint->cs_response)
                <div>
                    <div class="text-sm font-medium text-gray-500">Response CS</div>
                    <div class="mt-1 px-4 py-3 bg-green-50 text-green-900 border border-green-200 rounded-lg">
                        {{ $complaint->cs_response }}
                    </div>
                    <div class="mt-3">
                        <form method="POST" action="{{ route('customer.complaints.mark-read', $complaint) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                Tandai Sudah Dibaca
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
