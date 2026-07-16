<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Vendor;
use App\Services\RatingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * FinanceKomisiController
 *
 * Mengelola fitur "Kelola Komisi" multi-vendor untuk Finance.
 * Memungkinkan Finance menandai lunas per-vendor dan
 * memicu perhitungan Rating Murni secara otomatis.
 */
class FinanceKomisiController extends Controller
{
    public function __construct(private readonly RatingService $ratingService) {}

    /**
     * Tampilkan daftar event Finish Event dengan status komisi vendor-vendornya.
     */
    public function index(): View
    {
        $events = Project::with(['client.vendors'])
            ->whereIn('status_proyek', ['Finish Event', 'Transaksi Komplit'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('finance.komisi.index', compact('events'));
    }

    /**
     * Tampilkan modal/detail komisi vendor-vendor untuk satu event.
     */
    public function show(Project $project): View
    {
        $project->load('client.vendors');

        return view('finance.komisi.show', compact('project'));
    }

    /**
     * Tandai vendor tertentu sebagai LUNAS dalam sebuah event.
     *
     * Alur:
     * 1. Validasi: vendor harus terlibat dalam event ini dan belum lunas
     * 2. Catat timestamp pembayaran di pivot client_vendor
     * 3. Jalankan kalkulasi Rating Murni HANYA untuk vendor ini
     */
    public function tandaiLunas(Request $request, Project $project, Vendor $vendor): RedirectResponse
    {
        $client = $project->client;

        if (! $client) {
            return back()->withErrors('Data klien tidak ditemukan.');
        }

        // Pastikan vendor ini memang terlibat dalam event ini
        $pivotExists = DB::table('client_vendor')
            ->where('client_id', $client->id)
            ->where('vendor_id', $vendor->id)
            ->exists();

        if (! $pivotExists) {
            return back()->withErrors('Vendor ini tidak terdaftar di event tersebut.');
        }

        // Cek apakah sudah lunas sebelumnya
        $pivot = DB::table('client_vendor')
            ->where('client_id', $client->id)
            ->where('vendor_id', $vendor->id)
            ->first();

        if ($pivot && $pivot->status_komisi === 'Lunas') {
            return back()->withErrors("Komisi vendor [{$vendor->nama_vendor}] sudah tercatat lunas.");
        }

        // 1. Catat pelunasan di pivot
        DB::table('client_vendor')
            ->where('client_id', $client->id)
            ->where('vendor_id', $vendor->id)
            ->update([
                'status_komisi' => 'Lunas',
                'tanggal_bayar_komisi' => now(),
            ]);

        // 2. Jalankan kalkulasi Rating Murni hanya untuk vendor ini
        $ratingBaru = $this->ratingService->hitungDanTerapkanRatingMurni(
            $client->id,
            $vendor->id,
            $project->id
        );

        // 3. Cek apakah semua vendor di event ini sudah lunas → Transaksi Komplit
        $totalVendor = DB::table('client_vendor')
            ->where('client_id', $client->id)
            ->count();

        $totalLunas = DB::table('client_vendor')
            ->where('client_id', $client->id)
            ->where('status_komisi', 'Lunas')
            ->count();

        if ($totalVendor > 0 && $totalVendor === $totalLunas) {
            $project->update([
                'status_proyek' => 'Transaksi Komplit',
                'tanggal_komisi_dibayar' => now(),
            ]);
        }

        return back()->with(
            'success',
            "✅ Komisi [{$vendor->nama_vendor}] berhasil ditandai Lunas! Rating Murni ditetapkan: ⭐ {$ratingBaru}"
        );
    }

    /**
     * Update nominal komisi untuk vendor tertentu di sebuah event.
     * Finance perlu bisa menginput nominal sebelum menandai lunas.
     */
    public function updateNominalKomisi(Request $request, Project $project, Vendor $vendor): RedirectResponse
    {
        $request->validate([
            'jumlah_komisi' => ['required', 'numeric', 'min:0'],
        ], [
            'jumlah_komisi.required' => 'Nominal komisi wajib diisi.',
            'jumlah_komisi.numeric' => 'Nominal komisi harus berupa angka.',
        ]);

        $client = $project->client;

        DB::table('client_vendor')
            ->where('client_id', $client->id)
            ->where('vendor_id', $vendor->id)
            ->update(['jumlah_komisi' => $request->input('jumlah_komisi')]);

        return back()->with('success', "Nominal komisi [{$vendor->nama_vendor}] berhasil diperbarui.");
    }
}
