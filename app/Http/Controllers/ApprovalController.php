<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\VendorDocument;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    // Menampilkan daftar dokumen yang butuh persetujuan
    public function index()
    {
        // Ambil semua dokumen beserta nama vendornya (relasi)
        $documents = VendorDocument::with('vendor')->orderBy('created_at', 'desc')->get();

        return view('manager.approvals.index', compact('documents'));
    }

    // Mengubah status dokumen (Approve / Reject)
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_approval' => 'required|in:approved,rejected',
            'catatan_tolak' => 'required_if:status_approval,rejected|nullable|string|min:5|max:500',
        ]);

        $document = VendorDocument::with('vendor')->findOrFail($id);

        $document->update([
            'status_approval' => $request->status_approval,
        ]);

        // Integrasi dengan status vendor
        if ($document->vendor) {
            $vendor = $document->vendor;
            if ($request->status_approval === 'approved') {
                // MoU disetujui -> Vendor menjadi Aktif dan mendapat cold-start rating 4.5
                $vendor->update([
                    'status_aktif' => true,
                    'rating' => 4.5,
                    'catatan_tolak' => null,
                    'tanggal_ditolak' => null,
                ]);
                $pesan = 'Dokumen MoU disetujui dan vendor sekarang berstatus AKTIF dengan rating default 4.5!';
            } else {
                // MoU ditolak -> Vendor kembali ke status Ditolak dan non-aktif
                $vendor->update([
                    'status_approval' => 'Ditolak',
                    'status_aktif' => false,
                    'tanggal_ditolak' => now(),
                    'catatan_tolak' => $request->input('catatan_tolak') ?? 'MoU ditolak oleh Manager Commercial.',
                ]);
                $pesan = 'Dokumen MoU ditolak. Vendor telah dipindahkan kembali ke daftar Pengajuan Vendor dengan status Ditolak.';
            }
        } else {
            $pesan = $request->status_approval == 'approved' ? 'Dokumen berhasil disetujui!' : 'Dokumen telah ditolak.';
        }

        return back()->with('success', $pesan);
    }

    public function activityLogs()
    {
        $logs = ActivityLog::orderBy('created_at', 'desc')->paginate(20);

        return view('manager.logs.index', compact('logs'));
    }
}
