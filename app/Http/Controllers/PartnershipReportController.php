<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;

class PartnershipReportController extends Controller
{
    // Menampilkan halaman Laporan di layar
    public function index()
    {
        $totalVendor = Vendor::where('status_aktif', true)->count();
        $totalKlien = Client::count();
        // Event berjalan = Semua proyek KECUALI yang sudah komplit atau finish
        $eventBerjalan = Project::whereNotIn('status_proyek', ['Transaksi Komplit', 'Finish Event'])->count();

        $vendors = Vendor::orderBy('nama_vendor')->get();
        $clients = Client::orderBy('nama_klien')->get();
        $events = Project::with(['client', 'client.vendors'])
            ->whereNotIn('status_proyek', ['Transaksi Komplit', 'Finish Event'])
            ->get();

        return view('partnership.report', compact('totalVendor', 'totalKlien', 'eventBerjalan', 'vendors', 'clients', 'events'));
    }

    // Mengunduh Laporan dalam bentuk PDF sesuai pilihan data
    public function downloadPdf()
    {
        $dataFilter = request('data', 'semua'); // default: semua

        // 1. Ambil Angka Total (Sama seperti tampilan Dashboard)
        $totalVendor = Vendor::where('status_aktif', true)->count();
        $totalKlien = Client::count();
        $eventBerjalan = Project::whereNotIn('status_proyek', ['Transaksi Komplit', 'Finish Event'])->count();

        // 2. Ambil Rekapitulasi Vendor Berdasarkan Kategori
        $rekapVendor = Vendor::where('status_aktif', true)
            ->selectRaw('kategori_jasa, COUNT(*) as total, AVG(rating) as avg_rating')
            ->groupBy('kategori_jasa')
            ->orderByDesc('total')
            ->get();

        // 3. Ambil Data Klien
        $rekapKlien = Client::orderBy('nama_klien')
            ->get(['nama_klien', 'instansi', 'no_telepon', 'created_at']);

        // 4. Ambil Rekapitulasi Event Berdasarkan Status
        $rekapEvent = Project::selectRaw('status_proyek, COUNT(*) as total')
            ->groupBy('status_proyek')
            ->get();

        $pdf = Pdf::loadView('partnership.report_pdf', compact(
            'totalVendor',
            'totalKlien',
            'eventBerjalan',
            'rekapVendor',
            'rekapKlien',
            'rekapEvent',
            'dataFilter',
        ));

        $suffix = match ($dataFilter) {
            'vendor' => 'Vendor',
            'klien'  => 'Klien',
            'event'  => 'Event',
            default  => 'Lengkap',
        };

        return $pdf->download('Laporan_' . $suffix . '_VMS_' . date('d_M_Y') . '.pdf');
    }
}
