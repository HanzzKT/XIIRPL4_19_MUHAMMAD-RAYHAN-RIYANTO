<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Komplain Saya</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900 mr-4">Komplain Saya</h1>
                    @php
                        $newFeedbackCount = $complaints->where('cs_response', '!=', null)->count();
                    @endphp
                    @if($newFeedbackCount > 0)
                        <div class="flex items-center bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                            {{ $newFeedbackCount }} Feedback Baru
                        </div>
                    @endif
                </div>
                <a href="{{ route('home') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
            
            @if($complaints->count() > 0)
                <div class="space-y-6">
                    @foreach($complaints as $complaint)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Header dengan Status -->
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Detail Komplain {{ $complaint->id }}</h3>
                            <span class="px-3 py-1 text-sm font-medium rounded-full 
                                @if($complaint->status === 'baru') bg-red-100 text-red-800
                                @elseif($complaint->status === 'diproses') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </div>
                        
                        <!-- Detail dalam format table -->
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-500 bg-gray-50">Kategori</td>
                                    <td class="px-6 py-3 text-sm text-gray-900">{{ $complaint->category->name ?? 'Tidak ada kategori' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-500 bg-gray-50">Tanggal Dibuat</td>
                                    <td class="px-6 py-3 text-sm text-gray-900">{{ $complaint->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-500 bg-gray-50 align-top">Deskripsi</td>
                                    <td class="px-6 py-3 text-sm text-gray-900">
                                        @if($complaint->description)
                                            {{ $complaint->description }}
                                        @else
                                            <span class="text-gray-500 italic">Tidak ada deskripsi</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($complaint->action_notes)
                                <tr>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-500 bg-gray-50 align-top">Catatan dari CS</td>
                                    <td class="px-6 py-3 text-sm text-blue-900 bg-blue-50">{{ $complaint->action_notes }}</td>
                                </tr>
                                @endif
                                
                                @if($complaint->cs_response)
                                    @php
                                        $isReadKey = 'complaint_' . $complaint->id . '_read';
                                    @endphp
                                    <script>
                                        // Check if this complaint is marked as read in localStorage
                                        const isRead{{ $complaint->id }} = localStorage.getItem('{{ $isReadKey }}') === 'true';
                                    </script>
                                    @if($complaint->cs_response)
                                        <!-- Feedback BARU - Menyala -->
                                        <tr class="bg-green-50 border-2 border-green-200 animate-pulse">
                                            <td class="px-6 py-3 text-sm font-medium text-green-700 bg-green-100 align-top">
                                                <div class="flex items-center">
                                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-ping"></div>
                                                    Response CS
                                                    <span class="ml-2 px-2 py-1 text-xs bg-red-600 text-white rounded-full animate-bounce">BARU!</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 text-sm text-green-900 bg-green-50">
                                                <div class="font-medium mb-2 text-green-800">{{ $complaint->cs_response }}</div>
                                                @if($complaint->cs_response_updated_at)
                                                    <div class="text-xs text-green-600 mb-2">
                                                        <i class="fas fa-clock mr-1"></i>{{ $complaint->cs_response_updated_at->format('d M Y, H:i') }}
                                                    </div>
                                                @endif
                                                <button onclick="markAsRead({{ $complaint->id }})" class="mt-2 px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors shadow-lg">
                                                    <i class="fas fa-check mr-2"></i>Tandai Sudah Dibaca
                                                </button>
                                            </td>
                                        </tr>
                                    @else
                                        <!-- Feedback SUDAH DIBACA - Mati -->
                                        <tr class="bg-gray-50">
                                            <td class="px-6 py-3 text-sm font-medium text-gray-600 bg-gray-100 align-top">
                                                <div class="flex items-center">
                                                    <i class="fas fa-check-circle text-gray-500 mr-2"></i>
                                                    Response CS
                                                    <span class="ml-2 px-2 py-1 text-xs bg-gray-500 text-white rounded-full">DIBACA</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 text-sm text-gray-700 bg-gray-50">
                                                <div class="font-medium mb-2">{{ $complaint->cs_response }}</div>
                                                @if($complaint->cs_response_updated_at)
                                                    <div class="text-xs text-gray-500 mb-1">
                                                        <i class="fas fa-clock mr-1"></i>{{ $complaint->cs_response_updated_at->format('d M Y, H:i') }}
                                                    </div>
                                                @endif
                                                @if($complaint->feedback_read_at)
                                                    <div class="text-xs text-gray-500">
                                                        <i class="fas fa-eye mr-1"></i>Dibaca: {{ $complaint->feedback_read_at->format('d M Y, H:i') }}
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 mb-4">Anda belum memiliki komplain.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Check localStorage on page load and update UI accordingly
        document.addEventListener('DOMContentLoaded', function() {
            // Check each complaint for read status
            @foreach($complaints as $complaint)
                @if($complaint->cs_response)
                    if (localStorage.getItem('complaint_{{ $complaint->id }}_read') === 'true') {
                        // Find the feedback row and update it to "mati" state
                        const row = document.querySelector('button[onclick="markAsRead({{ $complaint->id }})"]')?.closest('tr');
                        if (row) {
                            // Remove "menyala" styling
                            row.classList.remove('bg-green-50', 'border-2', 'border-green-200', 'animate-pulse');
                            row.classList.add('bg-gray-50');
                            
                            // Update the label to "mati" state
                            const label = row.querySelector('td:first-child');
                            label.innerHTML = '<div class="flex items-center"><i class="fas fa-check-circle text-gray-500 mr-2"></i>Response CS <span class="ml-2 px-2 py-1 text-xs bg-gray-500 text-white rounded-full">DIBACA</span></div>';
                            
                            // Update content cell to "mati" state
                            const contentCell = row.querySelector('td:last-child');
                            const responseText = contentCell.querySelector('.font-medium')?.textContent || '{{ $complaint->cs_response }}';
                            const timeText = '{{ $complaint->cs_response_updated_at ? $complaint->cs_response_updated_at->format("d M Y, H:i") : "" }}';
                            
                            contentCell.innerHTML = `
                                <div class="font-medium mb-2">${responseText}</div>
                                <div class="text-xs text-gray-500 mb-1">
                                    <i class="fas fa-clock mr-1"></i>${timeText}
                                </div>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-eye mr-1"></i>Sudah dibaca
                                </div>
                            `;
                        }
                    }
                @endif
            @endforeach
            
            // Update counter based on remaining unread feedback
            updateFeedbackCounter();
        });
        
        function updateFeedbackCounter() {
            let unreadCount = 0;
            @foreach($complaints as $complaint)
                @if($complaint->cs_response)
                    if (localStorage.getItem('complaint_{{ $complaint->id }}_read') !== 'true') {
                        unreadCount++;
                    }
                @endif
            @endforeach
            
            // Update header counter
            const counterElement = document.querySelector('.bg-red-100');
            if (counterElement) {
                if (unreadCount === 0) {
                    counterElement.remove();
                } else {
                    counterElement.innerHTML = `
                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                        ${unreadCount} Feedback Baru
                    `;
                }
            }
            
            // Update navbar badge
            const navBadge = document.querySelector('.bg-red-500.animate-pulse');
            if (navBadge) {
                if (unreadCount === 0) {
                    navBadge.remove();
                } else {
                    navBadge.textContent = unreadCount;
                }
            }
        }

        function markAsRead(complaintId) {
            if (confirm('Tandai feedback ini sebagai sudah dibaca?')) {
                // Immediately update UI without waiting for server response
                const row = document.querySelector(`button[onclick="markAsRead(${complaintId})"]`).closest('tr');
                
                // Remove "menyala" styling
                row.classList.remove('bg-green-50', 'border-2', 'border-green-200', 'animate-pulse');
                row.classList.add('bg-gray-50');
                
                // Update the label to "mati" state
                const label = row.querySelector('td:first-child');
                label.innerHTML = '<div class="flex items-center"><i class="fas fa-check-circle text-gray-500 mr-2"></i>Response CS <span class="ml-2 px-2 py-1 text-xs bg-gray-500 text-white rounded-full">DIBACA</span></div>';
                
                // Update content cell to "mati" state
                const contentCell = row.querySelector('td:last-child');
                const responseText = contentCell.querySelector('.font-medium').textContent;
                const timeText = contentCell.querySelector('.text-xs').textContent;
                
                contentCell.innerHTML = `
                    <div class="font-medium mb-2">${responseText}</div>
                    <div class="text-xs text-gray-500 mb-1">${timeText}</div>
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-eye mr-1"></i>Dibaca: ${new Date().toLocaleDateString('id-ID')} ${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
                    </div>
                `;
                
                // Update counter in header
                const counterElement = document.querySelector('.bg-red-100');
                if (counterElement) {
                    const currentCount = parseInt(counterElement.textContent.match(/\d+/)[0]);
                    if (currentCount <= 1) {
                        counterElement.remove();
                    } else {
                        counterElement.innerHTML = `
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                            ${currentCount - 1} Feedback Baru
                        `;
                    }
                }
                
                // Update navbar badge
                const navBadge = document.querySelector('.bg-red-500.animate-pulse');
                if (navBadge) {
                    const navCount = parseInt(navBadge.textContent);
                    if (navCount <= 1) {
                        navBadge.remove();
                    } else {
                        navBadge.textContent = navCount - 1;
                    }
                }
                
                // Save to localStorage for persistence
                localStorage.setItem(`complaint_${complaintId}_read`, 'true');
                
                // Show success message
                alert('Feedback berhasil ditandai sebagai sudah dibaca');
            }
        }

    </script>
</body>
</html>
