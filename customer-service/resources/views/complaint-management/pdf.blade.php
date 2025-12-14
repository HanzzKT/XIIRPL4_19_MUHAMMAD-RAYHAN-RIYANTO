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
            padding: 0;
        }
        .header {
            position: fixed;
            top: -50px;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding: 3px 0 3px 0;
            background: #fff;
        }
        .header h1 {
            margin: 28px 0 0 0;
            font-size: 14px;
            color: #333;
        }
        .header p {
            margin: 1px 0 0 0;
            color: #666;
            font-size: 9px;
        }
        .meta-info {
            margin-top: 2px;
            margin-bottom: 3px;
            padding: 2px 3px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .meta-info p {
            display: block;
            margin: 0 0 2px 0;
            line-height: 1.15;
            font-size: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 2px 3px;
            text-align: left;
            vertical-align: top;
            color: #000;
        }
        /* Center align for specific columns: No, Tanggal, Status, Eskalasi */
        thead th:nth-child(1), tbody td:nth-child(1),
        thead th:nth-child(2), tbody td:nth-child(2),
        thead th:nth-child(6), tbody td:nth-child(6),
        thead th:nth-child(7), tbody td:nth-child(7) {
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 7.5px;
            line-height: 1.1;
        }
        td {
            font-size: 8px;
            line-height: 1.0;
        }
        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tbody { display: table-row-group; }
        .status {
            padding: 0;
            border-radius: 0;
            color: #000;
            font-weight: normal;
            text-align: left;
            background: transparent;
        }
        .status-baru { background-color: transparent; color: #000; }
        .status-diproses { background-color: transparent; color: #000; }
        .status-selesai { background-color: transparent; color: #000; }
        .escalation {
            padding: 0;
            border-radius: 0;
            font-weight: normal;
            text-align: left;
            font-size: 9px;
            color: #000;
            background: transparent;
        }
        .escalation-pending { background-color: transparent; color: #000; }
        .escalation-resolved { background-color: transparent; color: #000; }
        .escalation-returned { background-color: transparent; color: #000; }
        
        /* Prevent unknown characters */
        .escalation {
            font-family: Arial, sans-serif;
            text-rendering: optimizeLegibility;
        }
        .priority {
            padding: 0;
            border-radius: 0;
            font-weight: normal;
            text-align: left;
            color: #000;
            background: transparent;
        }
        .priority-low { background-color: transparent; color: #000; }
        .priority-normal { background-color: transparent; color: #000; }
        .priority-high { background-color: transparent; color: #000; }
        .priority-critical { background-color: transparent; color: #000; }
        .footer {
            position: fixed;
            bottom: -130px;
            left: 0;
            right: 0;
            height: 130px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            background: #fff;
        }
        .footer .signature {
            position: absolute;
            right: 30px;
            bottom: 86px;
            width: auto;
            text-align: right;
        }
        .footer .signature .sig-line {
            height: 32px;
            border-bottom: 1px solid #333;
            margin-bottom: 8px;
        }
        .footer .signature .sig-name {
            font-weight: bold;
            color: #333;
            margin-top: 2px;
        }
        .footer .signature .sig-role {
            font-size: 9px;
            color: #555;
        }
        .footer .disclaimer {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 14px;
            text-align: center;
            line-height: 1.3;
            font-size: 9px;
        }
        .truncate {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .desc {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        /* Explicit spacer to ensure content clears fixed header */
        .content-spacer { height: 0; }
        @media print {
            body { margin: 0; }
            .header { page-break-after: avoid; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
        
        /* Page margins to allocate space for header and footer */
        @page {
            margin: 70px 28px 8px 28px; /* top right bottom left */
            size: A4;
        }
        /* Signature section: keep signature + disclaimer together and align to right */
        .signature-section {
            page-break-inside: avoid;
            break-inside: avoid;
            page-break-before: auto;
            page-break-after: auto;
            margin-top: 12px;
        }
        .signature-block {
            width: auto;
            margin-left: auto;
            text-align: center;
        }
        .signature-block .sig-line {
            height: 12px;
            border-bottom: 1px solid #333;
            margin-bottom: 3px;
        }
        .signature-block .sig-name {
            font-weight: bold;
            color: #000;
            margin-top: 1px;
            font-size: 9px;
        }
        .signature-block .sig-role {
            font-size: 9px;
            color: #000;
        }
        .disclaimer { 
            margin-top: 3px; 
            font-size: 7.5px; 
            line-height: 1.0;
            text-align: center; 
            color: #000;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KOMPLAIN PELANGGAN PT Karunia Laris Abadi</h1>
        <p>Tanggal Cetak: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <div class="content-spacer"></div>

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
        <colgroup>
            <col style="width:5%">
            <col style="width:9%">
            <col style="width:16%">
            <col style="width:20%">
            <col style="width:10%">
            <col style="width:8%">
            <col style="width:8%">
            <col style="width:12%">
            <col style="width:12%">
        </colgroup>
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
                    <div>{{ Str::limit($complaint->description, 60) }}</div>
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
                        <span style="font-size: 9px; color: #000;">-</span>
                    @endif
                </td>
                <td>
                    @if($complaint->escalation_to)
                        @if($complaint->manager_claimed_by)
                            <strong>{{ $complaint->managerClaimedBy->name }}</strong>
                        @else
                            <span style="font-size: 9px; color: #000;">Belum diambil</span>
                        @endif
                    @else
                        <span style="font-size: 9px; color: #000;">-</span>
                    @endif
                </td>
                <td>
                    {{ $complaint->handledBy->name ?? 'Belum diambil' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #000;">
                    Tidak ada data komplain yang tersedia
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $sigName = trim(auth()->user()->name ?? '');
        $nameLen = mb_strlen($sigName ?? '');
        // width in ch so it approximates the name length; clamp to reasonable bounds
        $lineCh = max(16, min(50, (int) ceil($nameLen * 1.15)));
        $roleMap = [
            'cs' => 'CS',
            'manager' => 'Manager ',
            'admin' => 'Admin',
        ];
        $roleRaw = strtolower(auth()->user()->role ?? '');
        $roleText = $roleMap[$roleRaw] ?? ucfirst($roleRaw);
        $linePx = max(120, min(260, (int) ceil(($nameLen ?: 12) * 8.5 + 30)));
        $isManagerOrAdmin = in_array($roleRaw, ['manager','admin']);
        $roleLabel = $roleRaw === 'manager' ? 'Manager' : ($roleRaw === 'admin' ? 'Admin' : $roleText);
    @endphp
    <div class="signature-section" style="text-align: right;">
        @php
            // Signature width that follows name length (approx.) for manager
            $lineNamePx = max(60, min(600, (int) ceil(($nameLen ?: 1) * 9.0)));
        @endphp
        <div class="signature-block" style="display: inline-block; text-align: center;">
            @if($isManagerOrAdmin)
                <div class="sig-role" style="font-weight: bold;">Mengetahui</div>
                <div class="sig-role" style="margin-top: 2px;">{{ $roleLabel }}</div>
                <div style="display: inline-block; margin-top: 34px;">
                    <span style="display: inline-block; white-space: nowrap; border-top: 1px solid #333; padding-top: 3px; font-weight: bold; font-size: 9px;">{{ $sigName }}</span>
                </div>
            @else
                <div class="sig-line" style="width: 100%;"></div>
                <div class="sig-name">{{ $sigName }}</div>
                <div class="sig-role">{{ $roleText }}</div>
            @endif
        </div>
        <div class="disclaimer">
            <p>Laporan ini digenerate secara otomatis oleh Sistem Customer Service PT Karunia Laris Abadi</p>
            <p>Untuk informasi lebih lanjut, hubungi tim IT atau Customer Service</p>
        </div>
    </div>

</body>
</html>