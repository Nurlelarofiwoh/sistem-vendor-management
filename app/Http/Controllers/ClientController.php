<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\LogistikDisetujuiNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ClientController extends Controller
{
    // ==========================================
    // 1. READ: Menampilkan daftar klien
    // ==========================================
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->get();

        return view('clients.index', compact('clients'));
    }

    // ==========================================
    // 2. CREATE: Menampilkan form tambah klien
    // ==========================================
    public function create()
    {
        return view('clients.create');
    }

    // ==========================================
    // 3. STORE: Menyimpan klien baru
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'nama_klien' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:20',
            'kebutuhan_klien' => 'required|string',
            'tanggal_acara' => 'required|date',
            'tempat_acara' => 'required|string|max:255',
        ]);

        $client = Client::create($request->all());

        ActivityLog::create([
            'user_name' => Auth::user()->name ?? 'Admin CS',
            'aksi' => 'Input Klien Baru',
            'deskripsi' => 'Menambahkan klien '.$client->nama_klien.' dan membuat PDF rekomendasi otomatis.',
        ]);

        return back()->with([
            'success' => 'Data klien berhasil disimpan!',
            'clientId' => $client->id,
        ]);
    }

    // ==========================================
    // 4. PDF REKOMENDASI (Auto-Generate)
    // ==========================================
    public function downloadRecommendationPdf($id): \Symfony\Component\HttpFoundation\Response
    {
        $client = Client::findOrFail($id);
        $rekomendasiPerKategori = $this->buildRekomendasiPerKategori($client);

        $pdf = Pdf::loadView('clients.pdf_recommendation', compact('client', 'rekomendasiPerKategori'));

        return $pdf->download('Rekomendasi_Vendor_'.str_replace(' ', '_', $client->nama_klien).'.pdf');
    }

    // ==========================================
    // 5. EDIT & UPDATE: Mengubah Biodata Klien
    // ==========================================
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nama_klien' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telepon' => 'required|string|max:20',
            'kebutuhan_klien' => 'required|string',
            'tanggal_acara' => 'required|date',
            'tempat_acara' => 'required|string|max:255',
        ]);

        $client->update($request->all());

        ActivityLog::create([
            'user_name' => Auth::user()->name ?? 'Admin CS',
            'aksi' => 'Update Data Klien',
            'deskripsi' => 'Memperbarui profil klien '.$client->nama_klien.'.',
        ]);

        return redirect()->route('clients.index')->with('success', 'Data Klien berhasil diperbarui!');
    }

    // ==========================================
    // 6. CS: Memilih Vendor Final
    // ==========================================
    public function pilihVendor(Request $request, Client $client)
    {
        // Validasi bahwa input adalah array dan datanya ada di tabel vendors
        $request->validate([
            'vendor_ids' => 'required|array|min:1',
            'vendor_ids.*' => 'exists:vendors,id',
        ]);

        // Menyimpan 1, 2, atau 3 vendor sekaligus ke tabel penghubung
        $client->vendors()->sync($request->vendor_ids);

        $client->update([
            'is_vendor_acc' => false,
            'catatan_operasional' => null,
        ]); // Reset ACC dan hapus catatan penolakan jika ada

        ActivityLog::create([
            'user_name' => Auth::user()->name ?? 'Admin CS',
            'aksi' => 'Pengajuan Vendor',
            'deskripsi' => 'Mengajukan '.count($request->vendor_ids).' vendor untuk klien '.$client->nama_klien.', menunggu validasi Partnership.',
        ]);

        return back()->with('success', 'Daftar vendor berhasil diajukan! Menunggu Partnership untuk validasi.');
    }

    // ==========================================
    // 7. PARTNERSHIP: ACC Vendor & Trigger Event
    // ==========================================
    public function accVendor(Request $request, Client $client)
    {
        // Validasi tambahan agar sistem menerima array vendor baru dari Partnership
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'vendor_ids' => 'required|array',
            'vendor_ids.*' => 'exists:vendors,id',
        ]);

        // 1. Sinkronisasi (timpa) vendor lama dengan vendor baru pilihan Partnership
        $client->vendors()->sync($request->vendor_ids);

        // 2. Ubah status klien menjadi sudah di-ACC
        $client->update([
            'is_vendor_acc' => true,
        ]);

        // 3. Terbitkan/Buat Proyek Event baru untuk Logistik & Finance
        $project = Project::create([
            'client_id' => $client->id,
            'nama_proyek' => $request->nama_proyek,
            'status_proyek' => 'Berjalan', // Event Berjalan langsung agar terintegrasi instan
        ]);

        // Kirim notifikasi ke semua aktor terkait
        $aktorRole = ['admin_cs', 'partnership', 'finance', 'manager_comercial'];
        foreach ($aktorRole as $role) {
            $users = User::role($role)->get();
            if ($users->count() > 0) {
                Notification::send($users, new LogistikDisetujuiNotification($project));
            }
        }

        // 4. Catat aktivitas
        ActivityLog::create([
            'user_name' => auth()->user()->name ?? 'Manager Operasional',
            'aksi' => 'Validasi Logistik (ACC)',
            'deskripsi' => 'Memberikan validasi vendor akhir, kesiapan logistik, dan menerbitkan event '.$request->nama_proyek.' (Status: Berjalan) ke operasional.',
        ]);

        return redirect()->route('clients.index')->with('success', 'Vendor & Logistik berhasil divalidasi dan Event Resmi Berjalan!');
    }

    // ==========================================
    // 7b. MANAGER OPERASIONAL: Tolak Vendor & Logistik
    // ==========================================
    public function rejectVendor(Request $request, Client $client)
    {
        $request->validate([
            'catatan_operasional' => 'required|string|min:5',
        ]);

        $client->update([
            'is_vendor_acc' => false,
            'catatan_operasional' => $request->catatan_operasional,
        ]);

        // Catat aktivitas
        ActivityLog::create([
            'user_name' => auth()->user()->name ?? 'Manager Operasional',
            'aksi' => 'Tolak Vendor/Logistik Klien',
            'deskripsi' => 'Menolak pengajuan vendor untuk klien '.$client->nama_klien.'. Alasan: '.$request->catatan_operasional,
        ]);

        return redirect()->route('clients.index')->with('success', 'Pengajuan vendor berhasil ditolak dengan catatan.');
    }

    // ==========================================
    // 8. FUNGSI READ-ONLY DETAIL KLIEN
    // ==========================================
    public function show(Client $client): \Illuminate\View\View
    {
        $rekomendasiPerKategori = $this->buildRekomendasiPerKategori($client);

        return view('clients.show', compact('client', 'rekomendasiPerKategori'));
    }

    // ==========================================
    // PRIVATE: Logika Rekomendasi Terpusat
    // Digunakan identik oleh show() dan downloadRecommendationPdf()
    // sehingga tampilan web dan PDF selalu konsisten.
    // ==========================================
    private function buildRekomendasiPerKategori(Client $client): array
    {
        $kategoriTersedia = Vendor::select('kategori_jasa')->distinct()->pluck('kategori_jasa')->toArray();

        $kategoriDiminta = [];
        foreach ($kategoriTersedia as $kat) {
            if (stripos($client->kebutuhan_klien, $kat) !== false) {
                $kategoriDiminta[] = $kat;
            }
        }

        // Fallback: jika tidak ada kategori terdeteksi, tampilkan semua kategori
        if (empty($kategoriDiminta)) {
            $kategoriDiminta = $kategoriTersedia;
        }

        $rekomendasiPerKategori = [];
        foreach ($kategoriDiminta as $kat) {
            // Gunakan lokasi klien — Vendor model menangani fallback lokasi secara cerdas
            $vendors = Vendor::getRecommendationsForClient($client, $kat, $client->tempat_acara);
            if ($vendors->isNotEmpty()) {
                $rekomendasiPerKategori[$kat] = $vendors;
            }
        }

        return $rekomendasiPerKategori;
    }
}
