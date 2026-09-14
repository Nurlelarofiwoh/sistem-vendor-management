<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\TechnicalMeeting;
use App\Models\Vendor;
use App\Models\VendorDocument;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =========================================================
        // 1. DATA UNIVERSAL LINTAS DIVISI
        // =========================================================
        $totalKlien = Client::count();
        $totalEvent = Project::count();
        $totalVendor = Vendor::where('status_aktif', true)->count();

        // Menghitung Event yang sudah sepenuhnya ditutup/Lunas
        $totalEventLunas = Project::where('status_proyek', 'Transaksi Komplit')->count();

        // =========================================================
        // 2. STRATEGIS / PARTNERSHIP: KONTRAK VENDOR MAU HABIS
        // =========================================================
        $kontrakMauHabisVendors = Vendor::query()
            ->where('status_aktif', true)
            ->whereNotNull('tanggal_kontrak_habis')
            ->whereBetween('tanggal_kontrak_habis', [
                now()->toDateString(),
                now()->addDays(30)->toDateString(),
            ])
            ->get();
        $kontrakMauHabis = $kontrakMauHabisVendors->count();

        // =========================================================
        // 3. LOGIKA GRAFIK (KLIEN, VENDOR, EVENT) - 6 BULAN TERAKHIR
        // =========================================================
        $chartLabels = [];
        $chartDataKlien = [];
        $chartDataVendor = [];
        $chartDataEvent = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $chartLabels[] = $bulan->translatedFormat('M Y'); // Menampilkan misal: 'Jun 2026'

            // 1. Hitung Klien per bulan
            $chartDataKlien[] = Client::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();

            // 2. Hitung Vendor per bulan
            $chartDataVendor[] = Vendor::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();

            // 3. Hitung Event (Project) per bulan
            $chartDataEvent[] = Project::whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();
        }

        // =========================================================
        // 4. LOGIKA GRAFIK PENDAPATAN 12 BULAN (PROYEK BARU)
        // =========================================================
        $clientBulanan = array_fill(1, 12, 0);
        $clients = Client::whereYear('created_at', Carbon::now()->year)->get();

        foreach ($clients as $c) {
            if ($c->created_at) {
                $bulan = (int) $c->created_at->format('n');
                $clientBulanan[$bulan]++;
            }
        }

        $pendapatanBulanan = array_fill(1, 12, 0);
        $projects = Project::with('client')
            ->where('status_proyek', 'Transaksi Komplit')
            ->whereYear('created_at', Carbon::now()->year)
            ->get();

        foreach ($projects as $p) {
            $bulan = $p->created_at->format('n');
            $pendapatanBulanan[$bulan] += $p->nominal_invoice ?? $p->client->budget ?? 0;
        }

        // =========================================================
        // TAMBAHAN: DATA KHUSUS KINERJA CS (MANAGER OPERASIONAL)
        // =========================================================
        $klienHariIni = Client::whereDate('created_at', Carbon::today())->count();

        // =========================================================
        // 5. DATA REKAP FINANCE: VENDOR BELUM BAYAR KOMISI
        // =========================================================
        $finVendorBelumBayar = Project::with('client.vendors')
            ->whereIn('status_proyek', ['Finish Event'])
            ->whereNotNull('tanggal_komisi_jatuh_tempo')
            ->whereNull('tanggal_komisi_dibayar')
            ->get()
            ->flatMap(function ($project) {
                return $project->client->vendors->map(function ($vendor) use ($project) {
                    $hariTelat = (int) now()->diffInDays($project->tanggal_komisi_jatuh_tempo, false);

                    return [
                        'nama_vendor' => $vendor->nama_vendor,
                        'nama_proyek' => $project->nama_proyek,
                        'jatuh_tempo' => $project->tanggal_komisi_jatuh_tempo,
                        'hari_telat' => $hariTelat < 0 ? (int) abs($hariTelat) : 0,
                        'sudah_jatuh' => $hariTelat < 0,
                    ];
                });
            });

        // =========================================================
        // 6. DATA REKAP PARTNERSHIP: RATING VENDOR
        // =========================================================
        $spTopVendor = Vendor::where('status_aktif', true)
            ->orderBy('rating', 'desc')
            ->limit(10)
            ->get();

        // Vendor dengan rating di bawah 2 — perlu tindak lanjut segera
        $spLowRatingVendors = Vendor::where('status_aktif', true)
            ->where('rating', '<', 2)
            ->where('status_approval', 'Approved')
            ->orderBy('rating', 'asc')
            ->get();

        $spVendorTerlambat = Project::with('client.vendors')
            ->whereIn('status_proyek', ['Finish Event'])
            ->whereNotNull('tanggal_komisi_jatuh_tempo')
            ->whereNull('tanggal_komisi_dibayar')
            ->where('tanggal_komisi_jatuh_tempo', '<', now())
            ->get();

        $spVendorPending = Vendor::where('status_approval', 'Pending')->count();
        $spVendorDitinjau = Vendor::where('status_approval', 'Ditinjau')->count();
        $spPengajuanBaru = Vendor::where('status_approval', 'Pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // =========================================================
        // 7. KOMPILASI DATA UNTUK DIKIRIM KE BLADE
        // =========================================================
        $data = [
            // --- DATA GRAFIK 6 BULAN (KOMERSIAL DLL) ---
            'chartLabels' => $chartLabels,
            'chartDataKlien' => $chartDataKlien,
            'chartDataVendor' => $chartDataVendor,
            'chartDataEvent' => $chartDataEvent,

            // ... (BIARKAN VARIABEL LAINNYA SEPERTI SEBELUMNYA) ...
            'total_klien' => $totalKlien,
            'total_event' => $totalEvent,
            'total_vendor' => $totalVendor,
            'grafik_klien' => array_values($clientBulanan),
            'grafik_pendapatan' => array_values($pendapatanBulanan),

            // --- ADMIN CS ---
            'cs_totalKlien' => $totalKlien,
            'cs_totalEvent' => $totalEvent,

            // --- STRATEGI PARTNERSHIP ---
            'sp_totalVendor' => $totalVendor,
            'sp_totalKlien' => $totalKlien,
            'sp_totalEvent' => $totalEvent,
            'sp_kontrakHabis' => $kontrakMauHabis,
            'sp_kontrakHabisVendors' => $kontrakMauHabisVendors,
            'sp_topVendor' => $spTopVendor,
            'sp_lowRatingVendors' => $spLowRatingVendors,
            'sp_vendorTerlambat' => $spVendorTerlambat,
            'sp_vendorPending' => $spVendorPending,
            'sp_vendorDitinjau' => $spVendorDitinjau,
            'sp_pengajuanBaru' => $spPengajuanBaru,

            // --- FINANCE ---
            'fin_butuhInvoice' => Project::where('status_proyek', 'Perlu Invoice')->count(),
            'fin_jumlahInvoice' => Project::whereNotNull('invoice_path')->count(),
            'fin_komisiPending' => Project::where('status_proyek', 'Menunggu Verifikasi')->count(),
            'fin_komisiLunas' => $totalEventLunas,
            'fin_pendapatan' => $totalEventLunas,
            'fin_vendorBelumBayar' => $finVendorBelumBayar,

            // --- MANAGER COMERCIAL ---
            'mc_butuhDisposisi' => VendorDocument::where('status_approval', 'pending')->count(),
            'mc_pendapatan' => $totalEventLunas,
            'mc_vendorBermasalah' => Vendor::where('status_aktif', true)
                ->where('harga', '<=', 25000000)
                ->where(function ($query) {
                    $query->where('rating', '<', 2.0)
                        ->orWhereIn('skor_finansial', [1, 2]);
                })->get(),

            // --- MANAGER OPERASIONAL ---
            'mo_jumlahEvent' => $totalEvent,
            'mo_jumlahKlien' => $totalKlien,
            'mo_jumlahTM' => TechnicalMeeting::count(),
            'mo_listTM' => TechnicalMeeting::with('project.client')->orderBy('jadwal_tm', 'asc')->get(),

            // Variabel Baru Untuk Dashboard Operasional:
            'mo_klienHariIni' => $klienHariIni,
        ];

        return view('dashboard', $data);
    }
}
