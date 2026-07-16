<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // ==========================================
    // 1. READ: Menampilkan halaman Daftar Vendor
    // ==========================================
    public function index(Request $request)
    {
        // Hanya tampilkan vendor yang sudah di-approve (aktif di sistem)
        $query = Vendor::query()->where('status_approval', 'Approved');

        if ($request->filled('kategori')) {
            $query->where('kategori_jasa', $request->kategori);
        }

        if ($request->filled('daerah')) {
            $query->where('alamat', 'like', '%'.$request->daerah.'%');
        }

        if ($request->filled('vendor_baru')) {
            $query->where('is_new_vendor', $request->vendor_baru === 'ya');
        }

        // Tampilkan vendor terdaftar dengan pagination 15 item
        $vendors = $query->orderBy('rating', 'desc')->paginate(15)->withQueryString();

        $kategoriList = Vendor::distinct()->pluck('kategori_jasa')->sort();
        $daerahList = [
            'Tangerang Selatan',
            'Tangerang',
            'Jakarta Selatan',
            'Jakarta Pusat',
            'Bekasi',
            'Depok',
            'Bogor',
            'Jakarta Timur',
            'Jakarta Barat',
            'Jakarta Utara',
        ];

        return view('vendors.index', compact('vendors', 'kategoriList', 'daerahList'));
    }

    // ==========================================
    // 2. CREATE: Menampilkan form Tambah Vendor
    // ==========================================
    public function create()
    {
        return view('vendors.create');
    }

    // ==========================================
    // 3. STORE: Menyimpan vendor baru ke Database
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'kategori_jasa' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string',
            'harga' => 'required|numeric|min:1',
            'link_portofolio' => 'required|url|max:255',
            'detail' => 'nullable|array',
        ], [
            'harga.required' => 'Estimasi harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga harus bernilai positif dan tidak boleh nol atau negatif.',
            'link_portofolio.required' => 'Link portofolio wajib diisi.',
            'link_portofolio.url' => 'Link portofolio harus berupa URL yang valid (contoh: https://...).',
        ]);

        // Simpan ke Database beserta alamat, portofolio, dan spesifikasi khusus
        Vendor::create([
            'nama_vendor' => $request->nama_vendor,
            'kategori_jasa' => $request->kategori_jasa,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'link_portofolio' => $request->link_portofolio,
            'detail_spesifikasi' => $request->detail,
            'rating' => 0.00, // Default vendor baru
            'status_aktif' => true, // Default vendor baru
        ]);

        return redirect()->route('vendors.index')->with('success', 'Data Vendor berhasil ditambahkan!');
    }

    // ==========================================
    // 4. SHOW: Menampilkan detail & dokumen vendor
    // ==========================================
    public function show(Vendor $vendor)
    {
        $vendor->load('documents');

        return view('vendors.show', compact('vendor'));
    }

    // ==========================================
    // 5. EDIT: Menampilkan form Edit Vendor
    // ==========================================
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    // ==========================================
    // 6. UPDATE: Menyimpan perubahan data Edit
    // ==========================================
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'kategori_jasa' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string',
            'range_harga' => 'nullable|string|max:255',
            'link_portofolio' => 'nullable|url',       // <--- Validasi link baru
            'detail' => 'nullable|array',     // <--- Validasi array JSON baru
            'rating' => 'required|numeric|min:0|max:5',
            'status_aktif' => 'required|boolean',
            'tanggal_kontrak_habis' => 'nullable|date', // <--- Validasi tanggal baru
        ]);

        // Mapping manual agar 'detail' masuk ke 'detail_spesifikasi'
        $vendor->update([
            'nama_vendor' => $request->nama_vendor,
            'kategori_jasa' => $request->kategori_jasa,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'harga' => $request->harga,                 // <--- Diperbarui (sebelumnya range_harga)
            'link_portofolio' => $request->link_portofolio,
            'detail_spesifikasi' => $request->detail,
            'rating' => $request->rating,
            'status_aktif' => $request->status_aktif,
            'tanggal_kontrak_habis' => $request->tanggal_kontrak_habis, // <--- Ditambahkan agar kalender tersimpan
        ]);

        return redirect()->route('vendors.index')->with('success', 'Data Vendor berhasil diperbarui!');
    }

    // ==========================================
    // 7. DESTROY: Menghapus data vendor
    // ==========================================
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Data Vendor berhasil dihapus dari sistem!');
    }

    // ==========================================
    // 8. OVERRIDE RATING: Koreksi rating secara manual
    // ==========================================
    public function overrideRating(Request $request, Vendor $vendor)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $oldRating = $vendor->rating;
        $newRating = round((float) $request->rating, 1);

        // Update rating vendor secara manual
        $vendor->update([
            'rating' => $newRating,
        ]);

        // Simpan log ke sys_audit_trail
        \DB::table('sys_audit_trail')->insert([
            'user_name' => auth()->user()->name ?? 'Manager Commercial',
            'vendor_id' => $vendor->id,
            'nilai_sebelum' => $oldRating,
            'nilai_sesudah' => $newRating,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Rating vendor berhasil dikoreksi secara manual!');
    }
}
