<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Komplain</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .report-date {
            font-size: 12px;
            color: #666;
        }
        
        .stats-section {
            margin-bottom: 25px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            background-color: #f9f9f9;
        }
        
        .stats-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .stats-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        
        .filter-info {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .filter-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .status-baru {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-diproses {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-selesai {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                margin: 0;
                padding: 10px;
            }
            
            .header {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">PT KARUNIA LARIS ABADI</div>
        <div class="report-title">LAPORAN KOMPLAIN PELANGGAN</div>
        <div class="report-date">
            Dicetak pada: {{ date('d F Y, H:i') }} WIB
        </div>
        @if(isset($print_mode) && $print_mode)
        <div class="no-print" style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; border-radius: 5px;">
            <strong>⚠️ Catatan:</strong> Package DomPDF belum diinstall. Silakan install dengan perintah: <code>composer require barryvdh/laravel-dompdf</code><br>
            Untuk sementara, Anda bisa print halaman ini dengan Ctrl+P dan pilih "Save as PDF"
        </div>
        @endif
    </div>

    <!-- Filter Information -->
    <div class="filter-info">
        <div class="filter-title">Filter Laporan:</div>
        <div>
            @if($request->filled('start_date') || $request->filled('end_date'))
                <strong>Periode:</strong> 
                {{ $request->start_date ? date('d/m/Y', strtotime($request->start_date)) : 'Awal' }} - 
                {{ $request->end_date ? date('d/m/Y', strtotime($request->end_date)) : 'Sekarang' }}
                <br>
            @endif
            
            @if($request->filled('status'))
                <strong>Status:</strong> {{ ucfirst($request->status) }}<br>
            @endif
            
            @if(!$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('status'))
                <strong>Semua Data</strong>
            @endif
        </div>
    </div>

    <!-- Complaints Table -->
    <h3>Detail Komplain</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 8%">No</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 15%">Pelanggan</th>
                <th style="width: 32%">Detail Komplain</th>
                <th style="width: 8%">Status</th>
                <th style="width: 12%">CS Handler</th>
                <th style="width: 13%">Waktu Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $index => $complaint)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>{{ $complaint->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <strong>{{ $complaint->customer->name ?? 'N/A' }}</strong><br>
                    <small>{{ $complaint->customer->phone ?? 'N/A' }}</small>
                </td>
                <td>
                    <strong>{{ $complaint->category->name }}</strong>
                    @if($complaint->description)
                        <br><small>{{ Str::limit($complaint->description, 60) }}</small>
                    @endif
                </td>
                <td>
                    <span class="status-badge status-{{ $complaint->status }}">
                        {{ ucfirst($complaint->status) }}
                    </span>
                </td>
                <td>{{ $complaint->handledBy?->name ?? 'Belum diambil' }}</td>
                <td>{{ $complaint->resolved_at ? $complaint->resolved_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">
                    Tidak ada data komplain yang sesuai dengan filter
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>


    <!-- Footer -->
    <div class="footer">
        <div>Laporan ini digenerate secara otomatis oleh Sistem Customer Service PT Karunia Laris Abadi</div>
        <div>Untuk informasi lebih lanjut, hubungi tim IT atau Customer Service</div>
    </div>
</body>
</html>
