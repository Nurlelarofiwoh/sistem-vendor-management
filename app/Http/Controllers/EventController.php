<?php

namespace App\Http\Controllers;

use App\Mail\EvaluationMail;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\User;
use App\Notifications\InvoiceDibutuhkanNotification;
use App\Notifications\InvoiceTersediaNotification;
use App\Notifications\PembayaranDiterimaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Menampilkan halaman Management Event Pusat.
     * Hanya menampilkan event yang sudah berstatus 'Berjalan' ke atas (logistik sudah ACC).
     */
    public function index()
    {
        $events = Project::with(['client', 'client.vendors'])
            ->whereNotIn('status_proyek', ['Menunggu Logistik', 'Ditolak Logistik'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('events.index', compact('events'));
    }

    public function calendar()
    {
        $events = Project::with(['client', 'client.vendors'])->get();

        $formattedEvents = [];
        foreach ($events as $event) {
            if ($event->client && $event->client->tanggal_acara) {
                $vendorList = $event->client->vendors->pluck('nama_vendor')->implode(', ');

                $formattedEvents[] = [
                    'title' => $event->nama_proyek,
                    'start' => $event->client->tanggal_acara,
                    'description' => 'Vendor: '.($vendorList ?: 'Belum ada vendor'),
                    'location' => $event->client->tempat_acara,
                    'color' => '#8B5A2B',
                ];
            }
        }

        return view('events.calendar', compact('formattedEvents'));
    }

    /**
     * Mengelola seluruh transisi status Management Event.
     */
    public function updateStatus(Request $request, $id)
    {
        $event = Project::with(['client.vendors'])->findOrFail($id);
        $aksi = $request->input('aksi');

        // ==========================================
        // 1. ADMIN CS: Minta Invoice
        // ==========================================
        if ($aksi === 'minta_invoice') {
            $request->validate([
                'nominal_invoice' => 'required|numeric|min:0',
            ], [
                'nominal_invoice.required' => 'Nominal invoice wajib diisi.',
                'nominal_invoice.numeric' => 'Nominal invoice harus berupa angka.',
                'nominal_invoice.min' => 'Nominal invoice tidak boleh negatif.',
            ]);

            $event->update([
                'status_proyek' => 'Perlu Invoice',
                'nominal_invoice' => $request->input('nominal_invoice'),
            ]);

            // Notifikasi ke Finance
            $financeUsers = User::role('finance')->get();
            if ($financeUsers->count() > 0) {
                Notification::send($financeUsers, new InvoiceDibutuhkanNotification($event));
            }

            $this->catatLog('Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp '.number_format($event->nominal_invoice, 0, ',', '.').' untuk event '.$event->nama_proyek);

            return back()->with('success', 'Permintaan Invoice senilai Rp '.number_format($event->nominal_invoice, 0, ',', '.').' telah dikirim ke Finance. Notifikasi sudah masuk ke lonceng mereka.');
        }

        // ==========================================
        // 2. FINANCE: Upload Invoice PDF
        // ==========================================
        if ($aksi === 'upload_invoice') {
            $request->validate(['invoice_file' => 'required|file|mimes:pdf,jpg,png|max:5120']);
            $path = $request->file('invoice_file')->store('invoices', 'public');

            $event->update([
                'status_proyek' => 'Invoice Tersedia',
                'invoice_path' => $path,
            ]);

            // Notifikasi ke Admin CS
            $csUsers = User::role('admin_cs')->get();
            if ($csUsers->count() > 0) {
                Notification::send($csUsers, new InvoiceTersediaNotification($event));
            }

            $this->catatLog('Upload Invoice', 'Finance mengunggah invoice untuk event '.$event->nama_proyek);

            return back()->with('success', 'Invoice berhasil diunggah! Admin CS telah mendapat notifikasi untuk minta klien melakukan pembayaran.');
        }

        // ==========================================
        // 3. ADMIN CS: Upload Bukti Pembayaran
        // ==========================================
        if ($aksi === 'upload_bukti') {
            $request->validate(['bukti_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120']);

            $path = $request->file('bukti_file')->store('bukti_pembayaran', 'public');

            $event->update([
                'status_proyek' => 'Menunggu Verifikasi',
                'bukti_pembayaran_path' => $path,
                'catatan_finance' => null,
            ]);

            $this->catatLog('Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event '.$event->nama_proyek);

            return back()->with('success', 'Bukti transfer berhasil diunggah! Finance akan segera melakukan verifikasi.');
        }

        // ==========================================
        // 4. FINANCE: Verifikasi Pembayaran (Terima / Tolak)
        // ==========================================
        if ($aksi === 'verifikasi_pembayaran') {
            $keputusan = $request->input('keputusan');

            if ($keputusan === 'terima') {
                $event->update(['status_proyek' => 'Terverifikasi']);

                // Notifikasi ke Partnership: setup jadwal TM
                $partnershipUsers = User::role('partnership')->get();
                if ($partnershipUsers->count() > 0) {
                    Notification::send($partnershipUsers, new PembayaranDiterimaNotification($event, 'partnership'));
                }

                // Notifikasi ke Finance: bayar fee vendor
                $financeUsers = User::role('finance')->get();
                if ($financeUsers->count() > 0) {
                    Notification::send($financeUsers, new PembayaranDiterimaNotification($event, 'finance'));
                }

                $this->catatLog('Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event '.$event->nama_proyek);

                return back()->with('success', 'Pembayaran Terverifikasi! Partnership mendapat notifikasi untuk setup jadwal TM, Finance untuk proses fee vendor.');
            } else {
                $request->validate(['catatan' => 'required|string|min:5'], [
                    'catatan.required' => 'Alasan penolakan wajib diisi.',
                ]);

                $event->update([
                    'status_proyek' => 'Gagal Verifikasi',
                    'catatan_finance' => $request->input('catatan'),
                ]);

                $this->catatLog('Tolak Pembayaran', 'Finance menolak bukti transfer '.$event->nama_proyek.': '.$request->input('catatan'));

                return back()->with('success', 'Pembayaran ditolak. Admin CS akan diminta mengunggah bukti baru.');
            }
        }

        // ==========================================
        // 5. MANAGER OPERASIONAL: Tandai Finish Event
        // ==========================================
        if ($aksi === 'finish_event') {
            // Buat token evaluasi jika belum ada
            if (empty($event->evaluation_token) && ! $event->is_evaluated) {
                $token = Str::random(40);
                $event->update([
                    'evaluation_token' => $token,
                    'status_proyek' => 'Finish Event',
                    'tanggal_komisi_jatuh_tempo' => now()->addDays(7),
                    'tanggal_finish_event' => now(), // Untuk kalkulasi Rating Murni
                ]);
            } else {
                $event->update([
                    'status_proyek' => 'Finish Event',
                    'tanggal_komisi_jatuh_tempo' => now()->addDays(7),
                    'tanggal_finish_event' => now(), // Untuk kalkulasi Rating Murni
                ]);
            }

            $client = $event->client;

            // Kirim E-Survey ke klien (jika belum dikirim)
            if ($client && ! $event->is_notifikasi_terkirim) {
                $surveyLink = route('evaluation.show', $event->evaluation_token ?? 'expired');
                $clientEmail = $client->email ?? null;

                if ($clientEmail) {
                    try {
                        Mail::to($clientEmail)->send(new EvaluationMail($event, $event->evaluation_token));
                    } catch (\Exception $e) {
                        // Abaikan kegagalan email
                    }
                }

                // Kirim tagihan komisi ke semua vendor
                if ($client->vendors && $client->vendors->count() > 0) {
                    foreach ($client->vendors as $vendor) {
                        if ($vendor->email) {
                            $pesanVendor = "
                                <div style='font-family:Arial,sans-serif;color:#333;line-height:1.6;'>
                                    <h2>Halo {$vendor->nama_vendor},</h2>
                                    <p>Event <strong>{$event->nama_proyek}</strong> telah selesai. Mohon segera melakukan pembayaran komisi dalam <strong>7 hari</strong> sesuai kontrak.</p>
                                    <p>Terima kasih.<br><strong>Manajemen PT Liza Makmur Mandiri</strong></p>
                                </div>
                            ";
                            try {
                                Mail::html($pesanVendor, function ($msg) use ($vendor, $event) {
                                    $msg->to($vendor->email)->subject("Tagihan Komisi Event {$event->nama_proyek} - PT Liza Makmur Mandiri");
                                });
                            } catch (\Exception $e) {
                                // Lanjutkan ke vendor berikutnya
                            }
                        }
                    }
                }

                $event->update(['is_notifikasi_terkirim' => true]);
            }

            $this->catatLog('Finish Event', 'Manager Operasional menyatakan event "'.$event->nama_proyek.'" selesai. E-Survey & tagihan komisi dikirim otomatis.');

            return back()->with('success', 'Event dinyatakan Selesai! E-Survey dikirim ke klien dan tagihan komisi ke vendor.');
        }

        // ==========================================
        // 6. FINANCE: Tutup Transaksi (Komisi Vendor Cair)
        // ==========================================
        if ($aksi === 'transaksi_komplit') {
            $vendorId = $request->input('vendor_id');
            if ($vendorId) {
                $event->client->vendors()->updateExistingPivot($vendorId, [
                    'status_komisi' => 'Lunas',
                    'tanggal_bayar_komisi' => now(),
                ]);

                // Muat ulang data relasi untuk mendapatkan status pivot terbaru
                $event->load('client.vendors');

                $allLunas = true;
                foreach ($event->client->vendors as $v) {
                    if ($v->pivot->status_komisi !== 'Lunas') {
                        $allLunas = false;
                        break;
                    }
                }

                $vendorName = $event->client->vendors->where('id', $vendorId)->first()->nama_vendor ?? 'Vendor';

                if ($allLunas) {
                    $event->update([
                        'status_proyek' => 'Transaksi Komplit',
                        'tanggal_komisi_dibayar' => now(),
                    ]);
                    $this->catatLog('Transaksi Komplit', 'Finance menutup siklus finansial event '.$event->nama_proyek.'. Semua komisi vendor telah lunas.');

                    return back()->with('success', "Komisi untuk $vendorName berhasil dilunasi. Seluruh vendor telah lunas, Siklus Event Ditutup!");
                }

                return back()->with('success', "Komisi untuk $vendorName berhasil ditandai Lunas.");
            }

            // Fallback (jaga-jaga jika tombol lama yang tidak ada vendor_id ter-klik)
            $event->update([
                'status_proyek' => 'Transaksi Komplit',
                'tanggal_komisi_dibayar' => now(),
            ]);

            $this->catatLog('Transaksi Komplit', 'Finance menutup siklus finansial event '.$event->nama_proyek.'. Komisi vendor telah diterima.');

            return back()->with('success', 'Siklus Event Ditutup! Komisi vendor tercatat diterima pada hari ini.');
        }

        return back()->withErrors('Aksi tidak dikenali sistem.');
    }

    /**
     * Helper private untuk mencatat Log ke database.
     */
    private function catatLog(string $aksi, string $deskripsi): void
    {
        ActivityLog::create([
            'user_name' => Auth::user()->name ?? 'Sistem',
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
        ]);
    }
}
