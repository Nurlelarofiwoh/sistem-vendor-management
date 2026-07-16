<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinancePaymentController extends Controller
{
    // =========================================================
    // 1. Menampilkan Dashboard Finance (Rekap Invoice & Bukti)
    // =========================================================
    public function index()
    {
        // Mengambil semua proyek yang membutuhkan tindakan dari Finance
        // Status: menunggu_invoice, menunggu_validasi, atau lunas
        $projects = Project::with('client')
            ->whereIn('status_pembayaran', ['menunggu_invoice', 'menunggu_validasi', 'lunas'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('finance.index', compact('projects'));
    }

    // =========================================================
    // 2. Finance Drop/Unggah Dokumen Invoice (Tanpa Nominal)
    // =========================================================
    public function uploadInvoice(Request $request, $id)
    {
        // Validasi format file
        $request->validate([
            'file_invoice' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $project = Project::findOrFail($id);

        // Simpan file fisik ke folder 'storage/app/public/invoices'
        $path = $request->file('file_invoice')->store('invoices', 'public');

        // Update database: Ubah status agar Klien/CS bisa upload bukti bayar
        $project->update([
            'file_invoice' => $path,
            'status_pembayaran' => 'menunggu_pembayaran',
        ]);

        return back()->with('success', 'Dokumen Invoice berhasil di-drop! Sistem sedang menunggu Klien mengirimkan bukti transfer.');
    }

    // =========================================================
    // 3. Finance Memvalidasi Bukti Transfer Klien -> LUNAS
    // =========================================================
    public function validasiBukti($id)
    {
        $project = Project::findOrFail($id);

        $project->update([
            'status_pembayaran' => 'lunas',
        ]);

        return back()->with('success', 'Bukti Transfer sah! Pembayaran divalidasi LUNAS. Event siap dieksekusi oleh Operasional.');
    }

    // =========================================================
    // 4. Finance Menolak Bukti Transfer (Revisi ke Klien)
    // =========================================================
    public function tolakBukti($id)
    {
        $project = Project::findOrFail($id);

        // Hapus file bukti bayar yang salah/buram dari storage
        if ($project->file_bukti_tf) {
            Storage::disk('public')->delete($project->file_bukti_tf);
        }

        // Kembalikan status ke menunggu_pembayaran dan kosongkan buktinya
        $project->update([
            'status_pembayaran' => 'menunggu_pembayaran',
            'file_bukti_tf' => null,
        ]);

        return back()->with('success', 'Bukti Pembayaran ditolak dan dihapus. Status dikembalikan ke Klien/CS untuk diunggah ulang.');
    }
}
