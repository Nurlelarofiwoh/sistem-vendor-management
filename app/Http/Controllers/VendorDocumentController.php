<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorDocumentController extends Controller
{
    // Menyimpan file dokumen yang diunggah
    public function store(Request $request, Vendor $vendor)
    {
        // 1. Validasi: Wajib PDF dan maksimal 5MB
        $request->validate([
            'jenis_dokumen' => 'required|in:mou,kontrak',
            'file' => 'required|mimes:pdf|max:5120',
        ]);

        // 2. Simpan file fisik ke folder 'storage/app/public/documents'
        $filePath = $request->file('file')->store('documents', 'public');

        // 3. Simpan nama dan lokasi file ke database
        $vendor->documents()->create([
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path' => $filePath,
            'status_approval' => 'pending', // Menunggu divalidasi Manager
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah!');
    }
}
