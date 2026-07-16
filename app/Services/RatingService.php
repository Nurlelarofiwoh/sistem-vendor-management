<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Project;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RatingService
{
    /**
     * Hitung dan terapkan Rating Murni untuk satu vendor pada satu project/event.
     *
     * Dipanggil HANYA ketika Finance menandai vendor sebagai "Lunas" untuk pertama kalinya.
     * Rating 4.5 (cold-start default) akan MUTLAK digantikan oleh hasil kalkulasi ini.
     *
     * Formula:
     *   Hasil Akhir Rating = (Skor Survey Klien + Skor Kecepatan Pembayaran Komisi) / 2
     *
     * Skor Kecepatan Komisi (berdasarkan hari sejak event selesai):
     *   - ≤ 1 hari  → Skor 5 (Sangat Cepat)
     *   - 2–7 hari  → Skor 3 (Normal)
     *   - 8–30 hari → Skor 1 (Lambat)
     *
     * @param  int  $clientId  ID client yang event-nya selesai
     * @param  int  $vendorId  ID vendor yang baru lunas
     * @param  int  $projectId  ID project/event terkait
     */
    public function hitungDanTerapkanRatingMurni(int $clientId, int $vendorId, int $projectId): float
    {
        $project = Project::findOrFail($projectId);
        $vendor = Vendor::findOrFail($vendorId);

        // Ambil data pivot vendor ini di event ini
        $pivotData = DB::table('client_vendor')
            ->where('client_id', $clientId)
            ->where('vendor_id', $vendorId)
            ->first();

        if (! $pivotData) {
            return $vendor->rating;
        }

        // ── SKOR SURVEY KLIEN ──────────────────────────────────────────────────
        // Ambil dari kolom skor_evaluasi_klien di project.
        // Jika klien belum mengisi evaluasi, gunakan nilai tengah (3.0).
        $skorSurvey = $project->skor_evaluasi_klien ?? 3.0;

        // Pastikan skor dalam rentang valid 1–5
        $skorSurvey = max(1.0, min(5.0, (float) $skorSurvey));

        // ── SKOR KECEPATAN PEMBAYARAN KOMISI ──────────────────────────────────
        $tanggalFinish = $project->tanggal_finish_event
            ?? $project->updated_at; // Fallback ke updated_at jika kolom belum terisi

        $tanggalBayar = Carbon::parse($pivotData->tanggal_bayar_komisi);
        $selisihHari = (int) Carbon::parse($tanggalFinish)->diffInDays($tanggalBayar);

        $skorKecepatan = $this->hitungSkorKecepatan($selisihHari);

        // ── UPDATE DATABASE ────────────────────────────────────────────────────
        // 1. Simpan skor komponen ke pivot untuk audit trail
        DB::table('client_vendor')
            ->where('client_id', $clientId)
            ->where('vendor_id', $vendorId)
            ->update([
                'skor_survey_klien' => $skorSurvey,
                'skor_kecepatan_komisi' => $skorKecepatan,
            ]);

        // 2. Terapkan rating murni ke vendor via hitung dinamis
        $vendor->recalculateRatingAndNewStatus();

        return $vendor->rating;
    }

    /**
     * Kalkulasi skor kecepatan pembayaran komisi berdasarkan jumlah hari.
     *
     * @param  int  $selisihHari  Jumlah hari dari tanggal finish event ke tanggal bayar komisi
     * @return float Skor: 5.0, 3.0, atau 1.0
     */
    public function hitungSkorKecepatan(int $selisihHari): float
    {
        return match (true) {
            $selisihHari <= 1 => 5.0, // Sangat cepat: dibayar ≤ 1 hari
            $selisihHari <= 7 => 3.0, // Normal: dibayar 2–7 hari
            default => 1.0, // Lambat: dibayar > 7 hari (hingga maks 30 hari)
        };
    }
}
