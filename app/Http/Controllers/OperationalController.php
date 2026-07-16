<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\TechnicalMeeting;
use App\Models\User;
use App\Notifications\InvoiceReminderNotification;
use App\Notifications\LogistikDisetujuiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OperationalController extends Controller
{
    // =========================================================
    // 1. Menampilkan halaman Monitoring Operasional
    // =========================================================
    public function index()
    {
        $projects = Project::with(['client.vendors'])
            ->orderBy('created_at', 'desc')
            ->get();

        $meetings = TechnicalMeeting::with(['project.client.vendors'])
            ->orderBy('jadwal_tm', 'asc')
            ->get();

        return view('operasional.index', compact('projects', 'meetings'));
    }

    // =========================================================
    // 2. Mengubah status proyek (Validasi Logistik + Tolak)
    // =========================================================
    public function updateStatus(Request $request, $id)
    {
        $project = Project::with(['client.vendors'])->findOrFail($id);

        $statusBaru = $request->input('status_proyek', 'Berjalan');

        // ---------------------------------------------------------
        // KONDISI A: ACC LOGISTIK → Status "Berjalan"
        // ---------------------------------------------------------
        if ($statusBaru === 'Berjalan') {
            $project->update(['status_proyek' => 'Berjalan']);

            // Kirim notifikasi ke semua aktor terkait
            $aktorRole = ['admin_cs', 'partnership', 'finance', 'manager_comercial'];
            foreach ($aktorRole as $role) {
                $users = User::role($role)->get();
                if ($users->count() > 0) {
                    Notification::send($users, new LogistikDisetujuiNotification($project));
                }
            }

            ActivityLog::create([
                'user_name' => auth()->user()->name ?? 'Manager Operasional',
                'aksi' => 'Validasi Logistik (ACC)',
                'deskripsi' => 'Memvalidasi kesiapan logistik dan memulai operasional event '.$project->nama_proyek,
            ]);

            return back()->with('success', 'Validasi Logistik Berhasil! Event "'.$project->nama_proyek.'" resmi Berjalan. Semua divisi telah mendapat notifikasi.');
        }

        // ---------------------------------------------------------
        // KONDISI B: TOLAK LOGISTIK → Status "Ditolak Logistik"
        // ---------------------------------------------------------
        if ($statusBaru === 'Ditolak Logistik') {
            $request->validate([
                'catatan_operasional' => 'required|string|min:10',
            ], [
                'catatan_operasional.required' => 'Catatan wajib diisi saat menolak logistik.',
                'catatan_operasional.min' => 'Catatan minimal 10 karakter.',
            ]);

            $project->update([
                'status_proyek' => 'Ditolak Logistik',
                'catatan_operasional' => $request->catatan_operasional,
            ]);

            ActivityLog::create([
                'user_name' => auth()->user()->name ?? 'Manager Operasional',
                'aksi' => 'Tolak Logistik',
                'deskripsi' => 'Menolak logistik event '.$project->nama_proyek.'. Alasan: '.$request->catatan_operasional,
            ]);

            return back()->with('success', 'Logistik event "'.$project->nama_proyek.'" DITOLAK. Catatan telah disimpan dan Partnership diberitahu.');
        }

        // ---------------------------------------------------------
        // KONDISI C: SELESAI / FINISH EVENT (dipicu dari Management Event)
        // ---------------------------------------------------------
        if ($statusBaru === 'selesai' || $statusBaru === 'Finish Event') {

            $financeUsers = User::role('finance')->get();
            if ($financeUsers->count() > 0) {
                Notification::send($financeUsers, new InvoiceReminderNotification($project));
            }

            $client = $project->client;

            if ($client && ! $project->is_notifikasi_terkirim) {

                if ($client->email) {
                    $surveyLink = route('evaluation.show', $project->evaluation_token ?? 'expired');
                    $pesanKlien = "
                        <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                            <h2>Halo {$client->nama_klien},</h2>
                            <p>Terima kasih telah mempercayakan acara <strong>{$project->nama_proyek}</strong> kepada tim PT Liza Makmur Mandiri System.</p>
                            <p>Mohon kesediaannya untuk mengisi E-Survey singkat berikut:</p>
                            <p><br><a href='{$surveyLink}' style='display:inline-block; padding:10px 20px; background-color:#8B5A2B; color:#ffffff; text-decoration:none; border-radius:5px; font-weight:bold;'>Isi E-Survey Sekarang</a><br><br></p>
                            <p>Salam hangat,<br><strong>Divisi Operasional PT Liza Makmur Mandiri</strong></p>
                        </div>
                    ";

                    try {
                        Mail::html($pesanKlien, function ($message) use ($client, $project) {
                            $message->to($client->email)
                                ->subject("Terima Kasih & Link E-Survey Event {$project->nama_proyek}");
                        });
                    } catch (\Exception $e) {
                        // Abaikan kegagalan email agar proses tetap berjalan
                    }
                }

                if ($client->vendors && $client->vendors->count() > 0) {
                    foreach ($client->vendors as $vendor) {
                        if ($vendor->email) {
                            $pesanVendor = "
                                <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6;'>
                                    <h2>Halo {$vendor->nama_vendor},</h2>
                                    <p>Pemberitahuan resmi bahwa pelaksanaan event <strong>{$project->nama_proyek}</strong> telah selesai.</p>
                                    <p>Mohon segera melakukan proses rekonsiliasi dan penyetoran potongan komisi kepada manajemen PT Liza Makmur Mandiri sesuai kontrak.</p>
                                    <p>Terima kasih.<br><strong>Manajemen PT Liza Makmur Mandiri</strong></p>
                                </div>
                            ";

                            try {
                                Mail::html($pesanVendor, function ($message) use ($vendor, $project) {
                                    $message->to($vendor->email)
                                        ->subject("Pemberitahuan Penagihan Komisi Event {$project->nama_proyek} - PT Liza Makmur Mandiri");
                                });
                            } catch (\Exception $e) {
                                // Lanjutkan ke vendor berikutnya jika gagal
                            }
                        }
                    }
                }

                $project->update(['is_notifikasi_terkirim' => true]);
            }

            ActivityLog::create([
                'user_name' => auth()->user()->name ?? 'Operasional',
                'aksi' => 'Penyelesaian Event',
                'deskripsi' => 'Event '.$project->nama_proyek.' selesai. Sistem otomatis mengirimkan E-Survey ke Klien dan tagihan komisi ke semua Vendor.',
            ]);

            return back()->with('success', 'Event "'.$project->nama_proyek.'" selesai! E-Survey dikirim ke klien dan notifikasi tagihan ke vendor.');
        }

        return back()->with('success', 'Status proyek berhasil diperbarui!');
    }

    // =========================================================
    // 3. Menampilkan halaman Kinerja CS
    // =========================================================
    public function kinerjaCs()
    {
        $logs = ActivityLog::orderBy('created_at', 'desc')->get();
        $totalInputHariIni = ActivityLog::whereDate('created_at', today())->count();

        return view('operasional.kinerja', compact('logs', 'totalInputHariIni'));
    }
}
