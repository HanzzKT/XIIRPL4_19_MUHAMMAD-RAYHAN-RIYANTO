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
            @top-right { content: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KOMPLAIN PELANGGAN</h1>
        <p>PT Karunia Laris Abadi</p>
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
        @endif
        <p><strong>Total Komplain:</strong> {{ $complaints->count() }}</p>
        <p><strong>Status Baru:</strong> {{ $complaints->where('status', 'baru')->count() }}</p>
        <p><strong>Status Diproses:</strong> {{ $complaints->where('status', 'diproses')->count() }}</p>
        <p><strong>Status Selesai:</strong> {{ $complaints->where('status', 'selesai')->count() }}</p>
        <p><strong>Dicetak oleh:</strong> {{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%">No</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 20%">Pelanggan</th>
                <th style="width: 25%">Deskripsi</th>
                <th style="width: 15%">Kategori</th>
                <th style="width: 10%">Status</th>
                <th style="width: 10%">CS Handler</th>
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
                    <span class="status status-{{ $complaint->status }}">
                        {{ ucfirst($complaint->status) }}
                    </span>
                </td>
                <td>{{ $complaint->handledBy->name ?? 'Belum diambil' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
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