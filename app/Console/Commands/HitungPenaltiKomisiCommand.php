<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Vendor;
use Illuminate\Console\Command;

class HitungPenaltiKomisiCommand extends Command
{
    protected $signature = 'vms:hitung-penalti';

    protected $description = 'Hitung dan terapkan penalti rating vendor berdasarkan keterlambatan pembayaran komisi';

    public function handle(): int
    {
        $this->info('[VMS] Memulai penghitungan penalti rating komisi vendor...');

        // Ambil semua project yang sudah finish tapi komisi belum dibayar
        $projects = Project::with('client.vendors')
            ->whereIn('status_proyek', ['Finish Event', 'Transaksi Komplit'])
            ->whereNotNull('tanggal_komisi_jatuh_tempo')
            ->whereNull('tanggal_komisi_dibayar')
            ->get();

        $count = 0;

        foreach ($projects as $project) {
            $hariTelat = now()->diffInDays($project->tanggal_komisi_jatuh_tempo, false);

            // Hanya proses jika sudah melewati jatuh tempo
            if ($hariTelat >= 0) {
                continue;
            }

            $hariTelat = abs($hariTelat);

            // Tentukan besar penalti berdasarkan hari keterlambatan
            $penaltiBaru = match (true) {
                $hariTelat > 365 => 3.0,  // > 1 tahun: penalti berat + nonaktif
                $hariTelat > 30 => 2.0,  // > 1 bulan
                $hariTelat > 7 => 1.0,  // > 1 minggu
                default => 0.5,  // ≤ 7 hari
            };

            // Hanya update jika penalti bertambah dari sebelumnya
            if ($penaltiBaru <= $project->penalti_rating) {
                continue;
            }

            $project->update(['penalti_rating' => $penaltiBaru]);

            // Terapkan penalti ke semua vendor terikat di event ini
            if ($project->client && $project->client->vendors->count() > 0) {
                foreach ($project->client->vendors as $vendor) {
                    // Ambil ulang dari DB untuk data terkini
                    $vendor = Vendor::find($vendor->id);
                    if (! $vendor) {
                        continue;
                    }

                    // Rating dasar vendor (dari penilaian klien) dikurangi penalti
                    // Minimal rating adalah 1.0
                    $ratingBaru = max(1.0, round($vendor->rating - $penaltiBaru, 1));
                    $vendor->update(['rating' => $ratingBaru]);

                    // Nonaktifkan vendor jika terlambat > 1 tahun
                    if ($hariTelat > 365) {
                        $vendor->update(['status_aktif' => false]);
                        $this->warn("  ⚠️  Vendor [{$vendor->nama_vendor}] DINONAKTIFKAN (terlambat {$hariTelat} hari).");
                    }

                    $this->line("  → Vendor [{$vendor->nama_vendor}]: penalti -{$penaltiBaru} | rating baru: {$ratingBaru} | terlambat {$hariTelat} hari");
                    $count++;
                }
            }
        }

        $this->info("[VMS] Selesai. Total vendor diperbarui: {$count}.");

        return Command::SUCCESS;
    }
}
