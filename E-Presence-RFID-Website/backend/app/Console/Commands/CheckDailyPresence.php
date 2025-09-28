<?php

namespace App\Console\Commands;

use App\Http\Controllers\AttendanceController;
use App\Models\User;
use App\Models\Presence;
use App\Models\LeaveDocument;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckDailyPresence extends Command
{
    protected $signature = 'app:check-daily-presence';
    protected $description = 'Cek presensi harian, set Alpa & Izin/Sakit jika sesuai ketentuan.';

    public function handle()
    {
        $this->info('Checking daily presence...');

        $presenceController = new AttendanceController();
        $productiveDays = $presenceController
            ->getProductiveDays(Carbon::now()->month, Carbon::now()->year)['productive'];

        $today = Carbon::now();

        // ambil waktu dari .env
        $limitTime = env('MAX_MORNING_ENTRY', '08:00');
        $limit = Carbon::createFromFormat('H:i', $limitTime);

        $prod = in_array($today->toDateString(), $productiveDays);
        $isProductive = $prod ? true : false;
        $this->info($isProductive ? 'Hari ini adalah hari produktif.' : 'Hari ini bukan hari produktif.');

        $users = User::select('nis', 'status_ban')->get();

        foreach ($users as $user) {
            $nis = $user->nis;

            // cek apakah sudah ada presensi hari ini
            $presence = Presence::where('nis', $nis)
                ->whereDate('time_masuk', $today->toDateString())
                ->first();

            if ($presence) {
                $this->info("NIS {$nis} sudah ada presensi hari ini, skip.");
                continue;
            }

            $productiveText = $isProductive ? 'yes' : 'no';
            $this->info("Debug: NIS={$nis}, now={$today->toTimeString()}, productive={$productiveText}, banStatus={$user->status_ban}");

            $leave = LeaveDocument::where('nis', $nis)
                    ->whereDate('start_date', '<=', $today->toDateString())
                    ->whereDate('end_date', '>=', $today->toDateString())
                    ->first();

            if ($leave) {
                Presence::create([
                    'nis'         => $nis,
                    'time_masuk'  => $today,
                    'status'      => $leave->type, // Izin atau Sakit
                    'status_hari' => $isProductive ? 'Hari Produktif' : 'Hari Non-Produktif',
                ]);

                $this->info("NIS {$nis} status {$leave->type}.");
                continue;
            }

            // Jika sudah melewati jam masuk (MAX_MORNING_ENTRY)
            if ($today->greaterThan($limit) && $isProductive && $user->status_ban !== 'inactive') {
                Presence::create([
                    'nis'         => $nis,
                    'time_masuk'  => $today,
                    'status'      => 'Alpa',
                    'status_hari' => 'Hari Produktif',
                ]);

                $this->info("NIS {$nis} status Alpa (karena lewat jam {$limitTime} dan tidak ada izin/sakit).");
            }
        }

        $this->info('Daily presence check completed.');
    }
}
