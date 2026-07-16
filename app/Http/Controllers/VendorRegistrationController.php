<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRegistrationRequest;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VendorRegistrationController extends Controller
{
    /**
     * Menampilkan form registrasi publik untuk vendor baru.
     * Tidak memerlukan autentikasi.
     */
    public function show(): View
    {
        $kategoris = [
            'Venue',
            'Catering',
            'Dekorasi',
            'Dokumentasi',
            'Sound System',
            'Entertainment',
            'Attire',
            'Makeup Artist',
        ];

        return view('vendor-registration.form', compact('kategoris'));
    }

    /**
     * Memproses dan menyimpan data registrasi vendor baru.
     *
     * Logika keamanan:
     * - @csrf ditangani oleh FormRequest (bawaan Laravel)
     * - Input disanitasi oleh rule validasi (type-safe, size-limited)
     * - Status awal selalu 'Pending' — tidak bisa dimanipulasi dari luar
     * - File proposal disimpan di storage private (bukan public URL langsung)
     */
    public function store(StoreVendorRegistrationRequest $request): RedirectResponse
    {
        // Data yang sudah tervalidasi dan aman
        $validatedData = $request->validated();

        // Proses upload file proposal PDF (jika ada)
        $proposalFilePath = null;
        if ($request->hasFile('proposal_file')) {
            // Simpan ke storage/app/private/proposals — tidak dapat diakses via URL publik
            $proposalFilePath = $request->file('proposal_file')
                ->store('proposals', 'local');
        }

        // Simpan vendor baru dengan status selalu 'Pending'
        Vendor::create([
            'nama_vendor' => strip_tags($validatedData['nama_vendor']),
            'kategori_jasa' => $validatedData['kategori_jasa'],
            'email' => strtolower(trim($validatedData['email'])),
            'no_telepon' => strip_tags($validatedData['no_telepon']),
            'alamat' => strip_tags($validatedData['alamat']),
            'harga' => $validatedData['harga'],
            'link_portofolio' => $validatedData['link_portofolio'] ?? null,
            'proposal_file' => $proposalFilePath,
            'status_approval' => 'Pending',  // Selalu Pending saat baru daftar
            'status_aktif' => false,       // Belum aktif sebelum di-approve
            'rating' => 0,
        ]);

        return redirect()
            ->route('vendor.register.success')
            ->with('success', 'Pendaftaran Anda berhasil dikirim! Tim kami akan meninjau dan menghubungi Anda dalam 3-5 hari kerja.');
    }

    /**
     * Menampilkan halaman sukses registrasi vendor.
     */
    public function success(): View|RedirectResponse
    {
        if (! session('success')) {
            return redirect()->route('vendor.register.show');
        }

        return view('vendor-registration.success');
    }
}
