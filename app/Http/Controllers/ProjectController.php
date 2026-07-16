<?php

namespace App\Http\Controllers;

use App\Mail\EvaluationMail;
use App\Models\Project;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    // =========================================================================
    // 1. TRIGGER OTOMATIS: Tutup Event & Tembak Email ke Klien
    // (Dijalankan oleh Manager Operasional dari dalam Sistem)
    // =========================================================================
    public function markAsCompleted($id)
    {
        $project = Project::with(['client.vendors'])->findOrFail($id);

        $project->update([
            'status_proyek' => 'Finish Event',
            'tanggal_finish_event' => now(),
        ]);

        // Buat token evaluasi & kirim email jika belum ada
        if (empty($project->evaluation_token) && ! $project->is_evaluated) {
            $token = Str::random(40);
            $project->update(['evaluation_token' => $token]);

            $clientEmail = $project->client->email ?? null;

            if ($clientEmail) {
                Mail::to($clientEmail)->send(new EvaluationMail($project, $token));
            }
        }

        return redirect()->back()->with('success', 'Event Selesai! Email E-Survey otomatis dikirim ke klien.');
    }

    // =========================================================================
    // 2. RUTE PUBLIK: Menampilkan Form Penilaian untuk Klien
    // (Diakses oleh klien melalui klik link di Email, Tanpa Login)
    // =========================================================================
    public function showEvaluation($token)
    {
        $project = Project::with('client.vendors')->where('evaluation_token', $token)->firstOrFail();

        // Tetap tampilkan form meski sudah pernah diisi (untuk keperluan presentasi/demo).
        // Form akan menampilkan banner info jika sudah pernah diisi.
        $sudahDiisi = $project->is_evaluated;

        return view('evaluations.create', compact('project', 'sudahDiisi'));
    }

    // =========================================================================
    // 3. PEMROSESAN: Menyimpan Skor Evaluasi & Mengunci Token
    //
    // PERUBAHAN ARSITEKTUR (Rating Murni):
    // Rating vendor TIDAK langsung diupdate di sini.
    // Skor klien disimpan ke project.skor_evaluasi_klien dan ke pivot client_vendor.
    // Rating Murni dihitung RatingService saat Finance menandai komisi LUNAS.
    // =========================================================================
    public function submitEvaluation(Request $request, $token)
    {
        $project = Project::with('client.vendors')->where('evaluation_token', $token)->firstOrFail();

        if ($project->is_evaluated) {
            return redirect()->back()->with('error', 'Evaluasi sudah pernah diisi sebelumnya.');
        }

        $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|numeric|min:1|max:5',
        ]);

        $ratings = $request->ratings;
        $client = $project->client;

        // 1. Hitung skor rata-rata dari semua vendor yang dinilai klien
        $skorRata = count($ratings) > 0
            ? round(array_sum($ratings) / count($ratings), 1)
            : 3.0;

        // 2. Simpan skor rata-rata ke project (digunakan RatingService saat komisi lunas)
        $project->update(['skor_evaluasi_klien' => $skorRata]);

        // 3. Simpan skor individual per-vendor ke pivot untuk audit trail
        if ($client) {
            foreach ($ratings as $vendorId => $score) {
                DB::table('client_vendor')
                    ->where('client_id', $client->id)
                    ->where('vendor_id', (int) $vendorId)
                    ->update(['skor_survey_klien' => (float) $score]);
            }
        }

        // 4. Kunci form agar tidak bisa diisi ulang, namun TOKEN TETAP DISIMPAN
        //    agar link e-survey masih bisa diakses untuk keperluan demo/presentasi.
        $project->update([
            'is_evaluated' => true,
            // evaluation_token sengaja tidak di-null-kan untuk keperluan presentasi
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Penilaian Anda sangat berarti bagi pengembangan kualitas layanan PT Liza Makmur Mandiri.');
    }
}
