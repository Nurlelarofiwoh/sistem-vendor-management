<?php

namespace App\Console\Commands;

use App\Mail\KomisiReminderMail;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class KirimReminderKomisiCommand extends Command
{
    protected $signature = 'vms:kirim-reminder-komisi {--dry-run : Simulasi tanpa benar-benar mengirim email}';

    protected $description = 'Kirim email pengingat komisi ke vendor yang belum lunas (H+1, H+7, H+30 sejak Finish Event)';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('[DRY RUN] Mode simulasi — tidak ada email yang dikirim.');
        }

        $this->info('[VMS] Memulai pengiriman reminder komisi...');

        // Ambil semua project berstatus 'Finish Event' yang belum semua vendornya lunas
        $projects = Project::with(['client.vendors'])
            ->where('status_proyek', 'Finish Event')
            ->whereNotNull('tanggal_finish_event')
            ->get();

        if ($projects->isEmpty()) {
            $this->info('[VMS] Tidak ada event dengan status Finish Event. Selesai.');

            return Command::SUCCESS;
        }

        $totalTerkirim = 0;

        foreach ($projects as $project) {
            $client = $project->client;

            if (! $client || $client->vendors->isEmpty()) {
                continue;
            }

            $tanggalFinish = Carbon::parse($project->tanggal_finish_event);
            $hariSejaksFinish = (int) $tanggalFinish->diffInDays(now());

            // Tentukan apakah hari ini adalah hari pengiriman reminder (H+1, H+7, H+30)
            // Menggunakan tolerance ±0 hari (tepat pada hari tersebut)
            $jadwalReminder = [1, 7, 30]; // H+N

            foreach ($client->vendors as $vendor) {
                // Skip vendor yang sudah lunas
                if ($vendor->pivot->status_komisi === 'Lunas') {
                    continue;
                }

                // Skip vendor yang sudah 3 kali diingatkan (maks 3 reminder)
                $sudahTerkirim = (int) $vendor->pivot->jumlah_reminder_terkirim;
                if ($sudahTerkirim >= 3) {
                    continue;
                }

                // Tentukan reminder ke-berapa yang harus dikirim hari ini
                // Cek apakah $hariSejaksFinish === jadwal[ke] DAN belum pernah dikirim ke-N itu
                $reminderYangAkanDikirim = null;
                foreach ($jadwalReminder as $index => $hariTarget) {
                    $reminderKe = $index + 1; // 1, 2, atau 3
                    if ($hariSejaksFinish >= $hariTarget && $sudahTerkirim < $reminderKe) {
                        $reminderYangAkanDikirim = $reminderKe;
                        // Ambil yang tertinggi yang belum terkirim
                    }
                }

                if ($reminderYangAkanDikirim === null) {
                    continue;
                }

                // Hitung sisa hari menuju batas 30 hari
                $sisaHari = max(0, 30 - $hariSejaksFinish);

                if (! $vendor->email) {
                    $this->warn("  ⚠️  Vendor [{$vendor->nama_vendor}] tidak memiliki email. Dilewati.");

                    continue;
                }

                $this->line("  → Mengirim Reminder ke-{$reminderYangAkanDikirim} ke [{$vendor->nama_vendor}] untuk event [{$project->nama_proyek}] (H+{$hariSejaksFinish})");

                if (! $isDryRun) {
                    try {
                        Mail::to($vendor->email)->send(
                            new KomisiReminderMail($vendor, $project, $reminderYangAkanDikirim, $sisaHari)
                        );

                        // Update counter di pivot table
                        DB::table('client_vendor')
                            ->where('client_id', $client->id)
                            ->where('vendor_id', $vendor->id)
                            ->update([
                                'jumlah_reminder_terkirim' => $reminderYangAkanDikirim,
                            ]);

                        // Update total reminder di tabel projects (untuk tampilan kolom di event)
                        $project->increment('total_reminder_terkirim');

                    } catch (\Exception $e) {
                        $this->error("  ✗  Gagal kirim ke [{$vendor->nama_vendor}]: {$e->getMessage()}");

                        continue;
                    }
                }

                $totalTerkirim++;
            }
        }

        $mode = $isDryRun ? ' (simulasi)' : '';
        $this->info("[VMS] Selesai. Total reminder terkirim{$mode}: {$totalTerkirim}.");

        return Command::SUCCESS;
    }
}
