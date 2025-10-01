<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Komplain - {{ date('d M Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .meta-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .meta-info p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }
        .status {
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .status-baru { background-color: #dc3545; }
        .status-diproses { background-color: #ffc107; color: #000; }
        .status-selesai { background-color: #28a745; }
        .escalation {
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        .escalation-pending { background-color: #fd7e14; color: white; }
        .escalation-resolved { background-color: #28a745; color: white; }
        .escalation-returned { background-color: #007bff; color: white; }
        
        /* Prevent unknown characters */
        .escalation {
            font-family: Arial, sans-serif;
            text-rendering: optimizeLegibility;
        }
        .priority {
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            text-align: center;
        }
        .priority-low { background-color: #6c757d; color: white; }
        .priority-normal { background-color: #007bff; color: white; }
        .priority-high { background-color: #fd7e14; color: white; }
        .priority-critical { background-color: #dc3545; color: white; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .truncate {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        @media print {
            body { margin: 0; }
            .header { page-break-after: avoid; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            
            /* Hide browser-generated headers and footers */
            @page {
                margin: 0.5in;
                size: A4;
            }
        }
        
        /* Hide URL and other browser elements */
        @page {
            margin: 0.5in;
            size: A4;
            @bottom-left { content: none; }
            @bottom-center { content: none; }
            @bottom-right { content: none; }
            @top-left { content: none; }
            @top-center { content: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        @if(auth()->user()->role === 'cs')
            <h1>LAPORAN INDIVIDU KOMPLAIN</h1>
            <h2>{{ auth()->user()->name }} - Customer Service</h2>
        @else
            <h1>LAPORAN KOMPLAIN PELANGGAN</h1>
            <h2>PT Karunia Laris Abadi</h2>
        @endif
        <p>Tanggal Cetak: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <div class="meta-info">
        @if(isset($request))
            @if($request->filled('start_date') || $request->filled('end_date'))
                <p><strong>Periode:</strong> 
                    {{ $request->filled('start_date') ? date('d/m/Y', strtotime($request->start_date)) : 'Awal' }} - 
                    {{ $request->filled('end_date') ? date('d/m/Y', strtotime($request->end_date)) : 'Akhir' }}
                </p>
            @endif
            @if($request->filled('status'))
                <p><strong>Filter Status:</strong> {{ ucfirst($request->status) }}</p>
            @endif
            @if($request->filled('category'))
                <p><strong>Filter Kategori:</strong> {{ \App\Models\ComplaintCategory::find($request->category)->name ?? 'Tidak ditemukan' }}</p>
            @endif
            @if($request->filled('cs_search'))
                <p><strong>Filter CS:</strong> {{ $request->cs_search }}</p>
            @endif
        @endif
        
        @if(auth()->user()->role === 'cs')
            <p><strong>Laporan untuk:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Total Komplain Saya:</strong> {{ $complaints->count() }}</p>
            <p><strong>Sedang Diproses:</strong> {{ $complaints->where('status', 'diproses')->count() }}</p>
            <p><strong>Sudah Selesai:</strong> {{ $complaints->where('status', 'selesai')->count() }}</p>
        @else
            <p><strong>Total Komplain:</strong> {{ $complaints->count() }}</p>
            <p><strong>Status Baru:</strong> {{ $complaints->where('status', 'baru')->count() }}</p>
            <p><strong>Status Diproses:</strong> {{ $complaints->where('status', 'diproses')->count() }}</p>
            <p><strong>Status Selesai:</strong> {{ $complaints->where('status', 'selesai')->count() }}</p>
            <p><strong>Dieskalasi:</strong> {{ $complaints->whereNotNull('escalation_to')->count() }}</p>
            <p><strong>Eskalasi Diambil Manager:</strong> {{ $complaints->whereNotNull('manager_claimed_by')->count() }}</p>
        @endif
        <p><strong>Dicetak oleh:</strong> {{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 9%">Tanggal</th>
                <th style="width: 16%">Pelanggan</th>
                <th style="width: 20%">Deskripsi</th>
                <th style="width: 10%">Kategori</th>
                <th style="width: 8%">Status</th>
                <th style="width: 8%">Eskalasi</th>
                <th style="width: 12%">Manager</th>
                <th style="width: 12%">CS Handler</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $index => $complaint)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $complaint->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <strong>{{ $complaint->customer->name ?? '-' }}</strong><br>
                    <small>{{ $complaint->customer->phone ?? $complaint->customer_phone ?? '-' }}</small>
                </td>
                <td>
                    <div>{{ Str::limit($complaint->description, 100) }}</div>
                </td>
                <td>{{ $complaint->category->name ?? '-' }}</td>
                <td>
                    <!-- Selalu tampilkan status asli komplain -->
                    <span class="status status-{{ $complaint->status }}">
                        {{ ucfirst($complaint->status) }}
                    </span>
                </td>
                <td>
                    @if($complaint->escalation_to)
                        <!-- Jika dieskalasi, tampilkan status eskalasi -->
                        @php
                            $actionNotes = $complaint->action_notes ?? '';
                            $actionNotes = trim($actionNotes);
                        @endphp
                        @if($actionNotes && str_contains($actionNotes, 'Manager Action: resolved'))
                            <span class="escalation escalation-resolved">Ditangani</span>
                        @elseif($actionNotes && str_contains($actionNotes, 'Manager Action: return_to_cs'))
                            <span class="escalation escalation-returned">Kembali</span>
                        @else
                            <span class="escalation escalation-pending">Tunggu</span>
                        @endif
                    @else
                        <span style="color: #666; font-size: 9px;">-</span>
                    @endif
                </td>
                <td>
                    @if($complaint->escalation_to)
                        @if($complaint->manager_claimed_by)
                            <strong>{{ $complaint->managerClaimedBy->name }}</strong>
                        @else
                            <span style="color: #f59e0b; font-size: 9px;">Belum diambil</span>
                        @endif
                    @else
                        <span style="color: #666; font-size: 9px;">-</span>
                    @endif
                </td>
                <td>
                    {{ $complaint->handledBy->name ?? 'Belum diambil' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #666;">
                    Tidak ada data komplain yang tersedia
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh Sistem Customer Service PT Karunia Laris Abadi</p>
        <p>Untuk informasi lebih lanjut, hubungi tim IT atau Customer Service</p>
    </div>

</body>
</html>