<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckFinanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:check-finance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek keterlambatan komisi vendor dan update skor finansial otomatis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan komisi vendor...');

        // Cari transaksi belum lunas
        $unpaid = DB::table('client_vendor')
            ->join('projects', 'client_vendor.client_id', '=', 'projects.client_id')
            ->where('client_vendor.status_komisi', '!=', 'Lunas')
            ->whereNotNull('projects.tanggal_finish_event')
            ->select('client_vendor.vendor_id', 'projects.tanggal_finish_event')
            ->get();

        $vendorLateDays = [];

        // Hitung max hari telat per vendor
        foreach ($unpaid as $row) {
            $days = Carbon::now()->diffInDays(Carbon::parse($row->tanggal_finish_event));
            if (! isset($vendorLateDays[$row->vendor_id]) || $days > $vendorLateDays[$row->vendor_id]) {
                $vendorLateDays[$row->vendor_id] = $days;
            }
        }

        // Update skor_finansial
        $count1 = 0;
        $count2 = 0;

        foreach ($vendorLateDays as $vendorId => $days) {
            $vendor = Vendor::find($vendorId);
            if (! $vendor) {
                continue;
            }

            $newScore = $vendor->skor_finansial;
            if ($days >= 90) {
                $newScore = 1; // Sengketa
            } elseif ($days >= 7) {
                $newScore = 2; // Warning
            }

            // Update jika skor berubah ATAU jika sebelumnya lunas (3) menjadi warning (2)
            if ($vendor->skor_finansial > $newScore) {
                $vendor->update(['skor_finansial' => $newScore]);
                if ($newScore === 1) {
                    $count1++;
                }
                if ($newScore === 2) {
                    $count2++;
                }
            }
        }

        $this->info('Pengecekan selesai. Sistem tidak menurunkan rating layanan klien, hanya mengubah skor finansial.');
        $this->info("Vendor di-update ke Sengketa (1): $count1");
        $this->info("Vendor di-update ke Warning (2): $count2");
    }
}
