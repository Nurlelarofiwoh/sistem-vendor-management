<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * VendorApprovalController
 *
 * Mengelola alur disposisi & approval vendor berjenjang:
 *   Pending → Ditinjau (Divisi SP) → Approved (Manager Commercial)
 *                                  ↘ Ditolak (Manager Commercial)
 *
 * Khusus Manager: saat Approve, rating default 4.5 langsung ditetapkan (cold-start).
 */
class VendorApprovalController extends Controller
{
    /**
     * Tampilkan daftar vendor berdasarkan status approval.
     * Divisi SP → lihat Pending, Ditinjau, & Ditolak.
     * Manager Commercial → lihat Ditinjau.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status', 'semua');

        $query = Vendor::query()->orderBy('created_at', 'desc');

        if ($statusFilter !== 'semua') {
            $query->where('status_approval', $statusFilter);
        } else {
            // Semua status kecuali Approved (vendor approved sudah pindah ke Data Vendor)
            $query->whereIn('status_approval', ['Pending', 'Ditinjau', 'Ditolak']);
        }

        $vendors = $query->paginate(15)->withQueryString();

        // Hitung jumlah per status untuk badge di navbar/tab
        $counts = [
            'Pending' => Vendor::where('status_approval', 'Pending')->count(),
            'Ditinjau' => Vendor::where('status_approval', 'Ditinjau')->count(),
            'Approved' => Vendor::where('status_approval', 'Approved')->count(),
            'Ditolak' => Vendor::where('status_approval', 'Ditolak')->count(),
        ];

        return view('vendor-approval.index', compact('vendors', 'statusFilter', 'counts'));
    }

    /**
     * [DIVISI SP] Tandai vendor sebagai 'Ditinjau'.
     * Hanya bisa dari status 'Pending'.
     */
    public function tandaiDitinjau(Vendor $vendor): RedirectResponse
    {
        if ($vendor->status_approval !== 'Pending') {
            return back()->withErrors('Hanya vendor berstatus Pending yang bisa ditandai Ditinjau.');
        }

        $vendor->update(['status_approval' => 'Ditinjau']);

        return back()->with('success', "Vendor [{$vendor->nama_vendor}] berhasil ditandai 'Ditinjau'.");
    }

    /**
     * [DIVISI SP / PARTNERSHIP] Approve Berkas — ubah status menjadi 'Approved' (belum aktif).
     * Setelah ini, vendor akan masuk ke Data Vendor dengan status Approved (belum aktif)
     * dan partnership dapat mengunggah MoU untuk diajukan ke Manager Commercial.
     */
    public function approveBerkas(Vendor $vendor): RedirectResponse
    {
        if ($vendor->status_approval !== 'Ditinjau') {
            return back()->withErrors('Hanya vendor berstatus Ditinjau yang bisa disetujui berkasnya.');
        }

        $vendor->update([
            'status_approval' => 'Approved',
            'status_aktif' => false, // Belum aktif sampai MoU disetujui Manager
            'catatan_tolak' => null,
            'tanggal_ditolak' => null,
        ]);

        return back()->with('success', "✅ Berkas Vendor [{$vendor->nama_vendor}] dinyatakan LENGKAP dan status disetujui (Approved)! Silakan ajukan MoU dari Detail Profil untuk mengaktifkan vendor.");
    }

    /**
     * [MANAGER COMMERCIAL] Approve MoU — ubah status menjadi 'Approved' dan aktif.
     */
    public function approveMou(Vendor $vendor): RedirectResponse
    {
        if ($vendor->status_approval !== 'Approved' && $vendor->status_approval !== 'Ditinjau') {
            return back()->withErrors('Hanya vendor berstatus Approved atau Ditinjau yang bisa di-Approve MoU-nya.');
        }

        $vendor->update([
            'status_approval' => 'Approved',
            'status_aktif' => true,
            'catatan_tolak' => null,
            'tanggal_ditolak' => null,
            'rating' => 4.5, // Cold-start rating
        ]);

        return back()->with('success', "✅ MoU Vendor [{$vendor->nama_vendor}] berhasil di-Approve! Rating default 4.5 telah ditetapkan.");
    }

    /**
     * [MANAGER COMMERCIAL] Tolak vendor — set status 'Ditolak' beserta catatan alasan.
     * Vendor yang ditolak tetap muncul di Pengajuan Vendor dengan status Ditolak.
     */
    public function tolak(Request $request, Vendor $vendor): RedirectResponse
    {
        $request->validate([
            'catatan_tolak' => ['required', 'string', 'min:10', 'max:500'],
        ], [
            'catatan_tolak.required' => 'Alasan penolakan wajib diisi.',
            'catatan_tolak.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $vendor->update([
            'status_approval' => 'Ditolak',
            'status_aktif' => false,
            'catatan_tolak' => $request->catatan_tolak,
            'tanggal_ditolak' => now(),
        ]);

        return back()->with('success', "Vendor [{$vendor->nama_vendor}] telah ditolak dan diberi catatan penolakan.");
    }

    /**
     * [DIVISI SP / PARTNERSHIP] Reset Pending — kembalikan status ke Pending untuk diajukan ulang.
     */
    public function resetPending(Vendor $vendor): RedirectResponse
    {
        $vendor->update([
            'status_approval' => 'Pending',
            'status_aktif' => false,
            // Simpan catatan tolak sebelumnya untuk referensi, tapi reset tanggal ditolak agar bersih
            'tanggal_ditolak' => null,
        ]);

        return back()->with('success', "Status Vendor [{$vendor->nama_vendor}] berhasil dikembalikan ke Pending untuk peninjauan ulang.");
    }

    /**
     * Download file proposal PDF vendor dari private storage.
     * File disimpan di storage/app/private/proposals (tidak bisa diakses via URL langsung).
     */
    public function downloadProposal(Vendor $vendor): StreamedResponse
    {
        if (! $vendor->proposal_file || ! Storage::disk('local')->exists($vendor->proposal_file)) {
            abort(404, 'File proposal tidak ditemukan.');
        }

        $filename = 'proposal-'.str()->slug($vendor->nama_vendor).'.pdf';

        return Storage::disk('local')->download($vendor->proposal_file, $filename);
    }
}
