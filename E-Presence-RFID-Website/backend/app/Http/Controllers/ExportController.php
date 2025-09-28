<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\WargaTels;
use App\Exports\PresenceMultiSheetExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    /**
     * Export data presensi ke format XLSX
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPresence(Request $request)
    {
        // Mengambil parameter dari request
        $filter = ucfirst(strtolower($request->input('filter', 'Hari Ini')));
        $tab = $request->input('tab', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        // Query dasar untuk data presensi
        $query = Presence::with('warga_tels');
        
        // Filter berdasarkan tab yang dipilih
        if ($tab != 'all') {
            $status = ucfirst($tab);
            $query->where('status', $status);
        }
        
        // Filter berdasarkan waktu
        switch ($filter) {
            case 'Hari ini':
                    $start = Carbon::today('Asia/Jakarta')->startOfDay();
                    $end   = Carbon::today('Asia/Jakarta')->endOfDay();
                    $query->whereBetween('time_masuk', [$start, $end]);
                    break;
            case 'Kemarin':
                $start = Carbon::yesterday('Asia/Jakarta')->startOfDay();
                $end   = Carbon::yesterday('Asia/Jakarta')->endOfDay();
                $query->whereBetween('time_masuk', [$start, $end]);
                break;
            case 'Minggu ini':
                $query->whereBetween('time_masuk', [Carbon::now('Asia/Jakarta')->startOfWeek(), Carbon::now('Asia/Jakarta')->endOfWeek()]);
                break;
            case 'Minggu lalu':
                $query->whereBetween('time_masuk', [Carbon::now('Asia/Jakarta')->subWeek()->startOfWeek(), Carbon::now('Asia/Jakarta')->subWeek()->endOfWeek()]);
                break;
            case 'Bulan Ini':
                $query->whereMonth('time_masuk', Carbon::now('Asia/Jakarta')->month)
                      ->whereYear('time_masuk', Carbon::now('Asia/Jakarta')->year);
                break;
            case 'Bulan lalu':
                $query->whereMonth('time_masuk', Carbon::now('Asia/Jakarta')->subMonth()->month)
                      ->whereYear('time_masuk', Carbon::now('Asia/Jakarta')->subMonth()->year);
                break;
            case 'Tahun ini':
                $query->whereYear('time_masuk', Carbon::now('Asia/Jakarta')->year);
                break;
            case 'Tahun lalu':
                $query->whereYear('time_masuk', Carbon::now('Asia/Jakarta')->subYear()->year);
                break;
            case 'Custom':
                if ($dateFrom && $dateTo) {
                    $query->whereBetween(DB::raw('DATE(time_masuk)'), [$dateFrom, $dateTo]);
                }
                break;
        }
        
        // Mengambil data presensi
        $presences = $query->get()->map(function ($presence) {
            $tanggal = \Carbon\Carbon::parse($presence->time_masuk)->format('Y-m-d');
    
            $izin = \App\Models\LeaveDocument::where('nis', $presence->nis)
                ->whereDate('start_date', '<=', $tanggal)
                ->whereDate('end_date', '>=', $tanggal)
                ->first();
    
            $presence->izin_reason = $izin->reason ?? '-';
            return $presence;
        });
        
        // Membuat nama file berdasarkan filter dan tab
        $fileName = $this->generateFileName($filter, $tab, $dateFrom, $dateTo);

        // Export ke XLSX
        return Excel::download(
            new PresenceMultiSheetExport($filter, $tab, $dateFrom, $dateTo, $presences),
            $fileName
        );
    }
    
    /**
     * Membuat nama file berdasarkan filter dan tab yang dipilih
     * 
     * @param string $filter Filter waktu yang dipilih
     * @param string $tab Tab yang dipilih
     * @param string|null $dateFrom Tanggal awal jika filter Custom
     * @param string|null $dateTo Tanggal akhir jika filter Custom
     * @return string Nama file
     */
    protected function generateFileName($filter, $tab, $dateFrom, $dateTo)
    {
        // Menentukan status berdasarkan tab
        $status = 'Semua_Siswa';
        if ($tab == 'hadir') {
            $status = 'Siswa_Hadir';
        } elseif ($tab == 'izin') {
            $status = 'Siswa_Izin';
        } elseif ($tab == 'sakit') {
            $status = 'Siswa_Sakit';
        } elseif ($tab == 'alpa') {
            $status = 'Siswa_Alpa';
        } elseif ($tab == 'terlambat') {
            $status = 'Siswa_Terlambat';
        }
        
        // Menentukan periode berdasarkan filter
        $periode = str_replace(' ', '_', $filter);
        if ($filter == 'Custom' && $dateFrom && $dateTo) {
            $from = Carbon::parse($dateFrom)->format('d-m-Y');
            $to = Carbon::parse($dateTo)->format('d-m-Y');
            $periode = "Tanggal_{$from}_sd_{$to}";
        }
        
        // Format: Presensi_[Status]_[Periode]_[Timestamp].xlsx
        return "Presensi_{$status}_{$periode}_" . Carbon::now()->format('YmdHis') . ".xlsx";
    }
}
