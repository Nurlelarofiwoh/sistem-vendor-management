<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TechnicalMeeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TechnicalMeetingController extends Controller
{
    // =========================================================
    // 1. Menampilkan daftar Jadwal TM
    // =========================================================
    public function index()
    {
        // Ambil TM yang masih aktif (belum selesai)
        $meetings = TechnicalMeeting::with(['project.client.vendors'])
            ->where('status_tm', 'aktif') // <- Tambahan ini
            ->orderBy('jadwal_tm', 'asc')
            ->get();

        // Ambil TM yang sudah selesai (sebagai riwayat)
        $historyMeetings = TechnicalMeeting::with(['project.client.vendors'])
            ->where('status_tm', 'selesai')
            ->orderBy('jadwal_tm', 'desc')
            ->get();

        return view('technical-meetings.index', compact('meetings', 'historyMeetings'));
    }

    // =========================================================
    // 2. Menampilkan form tambah jadwal
    // =========================================================
    public function create()
    {
        // FILTER DIBUKA: Event yang Terverifikasi, Berjalan, atau sedang TM tetap bisa dipilih
        $projects = Project::with(['client.vendors'])
            ->whereIn('status_proyek', ['Terverifikasi', 'Berjalan', 'Technical Meeting'])
            ->get();

        return view('technical-meetings.create', compact('projects'));
    }

    // =========================================================
    // 3. Menyimpan jadwal ke database & Kirim Email Otomatis
    // =========================================================
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'jadwal_tm' => 'required|date',
            'lokasi' => 'required|string',
            'agenda' => 'required|string',
        ]);

        // Simpan jadwal ke database
        $tm = TechnicalMeeting::create($request->all());

        // Ambil data proyek lengkap beserta Klien dan Vendornya
        $project = Project::with('client.vendors')->findOrFail($request->project_id);

        // AUTO-UPDATE: Ubah status proyek menjadi Technical Meeting jika sebelumnya Terverifikasi/Berjalan
        if ($project->status_proyek === 'Terverifikasi' || $project->status_proyek === 'Berjalan') {
            $project->update(['status_proyek' => 'Technical Meeting']);
        }

        $client = $project->client;

        // Persiapan format tanggal untuk email
        $waktuTM = Carbon::parse($tm->jadwal_tm)->translatedFormat('d F Y - H:i').' WIB';

        // --- 1. KIRIM EMAIL KE KLIEN ---
        if ($client && $client->email) {
            $pesanKlien = "
                <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                    <h2>Undangan Technical Meeting (TM)</h2>
                    <p>Halo <strong>{$client->nama_klien}</strong>,</p>
                    <p>Kami mengundang Bapak/Ibu untuk menghadiri Technical Meeting persiapan event <strong>{$project->nama_proyek}</strong> yang akan dilaksanakan pada:</p>
                    <table style='margin-top: 10px; margin-bottom: 20px;'>
                        <tr><td><strong>Waktu</strong></td><td>: {$waktuTM}</td></tr>
                        <tr><td><strong>Lokasi</strong></td><td>: {$tm->lokasi}</td></tr>
                        <tr><td><strong>Agenda</strong></td><td>: {$tm->agenda}</td></tr>
                    </table>
                    <p>Mohon konfirmasi kehadirannya. Terima kasih.<br><strong>Tim Strategic Partnership</strong></p>
                </div>
            ";

            try {
                Mail::html($pesanKlien, function ($message) use ($client, $project) {
                    $message->to($client->email)
                        ->subject("Undangan Technical Meeting - Event {$project->nama_proyek}");
                });
            } catch (\Exception $e) {
                // Abaikan jika gagal agar proses sistem tidak berhenti
            }
        }

        // --- 2. KIRIM EMAIL KE SELURUH VENDOR TERIKAT ---
        if ($client && $client->vendors && $client->vendors->count() > 0) {
            foreach ($client->vendors as $vendor) {
                if ($vendor->email) {
                    $pesanVendor = "
                        <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                            <h2>Panggilan Technical Meeting (TM) Vendor</h2>
                            <p>Halo Tim <strong>{$vendor->nama_vendor}</strong>,</p>
                            <p>Anda diwajibkan hadir dalam Technical Meeting koordinasi event <strong>{$project->nama_proyek}</strong> (Klien: {$client->nama_klien}) pada:</p>
                            <table style='margin-top: 10px; margin-bottom: 20px;'>
                                <tr><td><strong>Waktu</strong></td><td>: {$waktuTM}</td></tr>
                                <tr><td><strong>Lokasi</strong></td><td>: {$tm->lokasi}</td></tr>
                                <tr><td><strong>Agenda</strong></td><td>: {$tm->agenda}</td></tr>
                            </table>
                            <p>Harap mempersiapkan dokumen teknis yang diperlukan. Terima kasih.<br><strong>Tim Strategic Partnership PT Liza Makmur Mandiri</strong></p>
                        </div>
                    ";

                    try {
                        Mail::html($pesanVendor, function ($message) use ($vendor, $project) {
                            $message->to($vendor->email)
                                ->subject("Jadwal Technical Meeting - Event {$project->nama_proyek}");
                        });
                    } catch (\Exception $e) {
                        // Lanjutkan ke vendor berikutnya jika satu gagal
                    }
                }
            }
        }

        return redirect()->route('technical-meetings.index')
            ->with('success', 'Jadwal TM berhasil ditambahkan dan Undangan Email telah disebar ke Klien & Vendor!');
    }

    // =========================================================
    // 4. Menampilkan form edit jadwal (Reschedule)
    // =========================================================
    public function edit($id)
    {
        $meeting = TechnicalMeeting::findOrFail($id);

        // DIPERBAIKI (BUG #2): Buka filter agar proyek yang "Berjalan" dsb tetap muncul saat Reschedule
        $projects = Project::with(['client.vendors'])
            ->whereIn('status_proyek', ['Terverifikasi', 'Berjalan', 'Technical Meeting'])
            ->get();

        // DIPERBAIKI: Penulisan nama folder diseragamkan
        return view('technical-meetings.edit', compact('meeting', 'projects'));
    }

    // =========================================================
    // 5. Menyimpan perubahan jadwal ke database
    // =========================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'jadwal_tm' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'agenda' => 'required|string',
        ]);

        $meeting = TechnicalMeeting::findOrFail($id);
        $meeting->update([
            'project_id' => $request->project_id,
            'jadwal_tm' => $request->jadwal_tm,
            'lokasi' => $request->lokasi,
            'agenda' => $request->agenda,
        ]);

        return redirect()->route('technical-meetings.index')->with('success', 'Jadwal Technical Meeting berhasil di-Reschedule!');
    }

    // =========================================================
    // 6. Membatalkan / Menghapus jadwal TM
    // =========================================================
    public function destroy($id)
    {
        TechnicalMeeting::findOrFail($id)->delete();

        return back()->with('success', 'Jadwal Technical Meeting berhasil dibatalkan/dihapus.');
    }

    public function markAsDone($id)
    {
        $meeting = TechnicalMeeting::findOrFail($id);
        $meeting->update(['status_tm' => 'selesai']);

        return redirect()->route('technical-meetings.index', ['tab' => 'riwayat'])
            ->with('success', 'Jadwal Technical Meeting berhasil ditandai sebagai Selesai.')
            ->with('active_tab', 'riwayat');
    }
}
