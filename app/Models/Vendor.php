<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_vendor',
        'kategori_jasa',
        'no_telepon',
        'email',
        'alamat',
        'harga',
        'link_portofolio',
        'proposal_file',       // Path file PDF proposal dari form registrasi publik
        'detail_spesifikasi',
        'rating',
        'is_new_vendor',
        'status_aktif',
        'status_approval',     // Alur disposisi: Pending → Ditinjau → Approved / Ditolak
        'catatan_tolak',       // Catatan penolakan dari Manager Commercial
        'alasan_penolakan',    // Catatan penolakan Moa/MoU tambahan
        'tanggal_ditolak',     // Tanggal penolakan MoU oleh Manager Commercial
        'tanggal_kontrak_habis',
        'total_review',
    ];

    protected function casts(): array
    {
        return [
            'detail_spesifikasi' => 'array',
            'status_aktif' => 'boolean',
            'rating' => 'decimal:1',
            'is_new_vendor' => 'boolean',
            'harga' => 'decimal:2',
            'tanggal_ditolak' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($vendor) {
            // Default rating untuk vendor baru jika belum diisi
            if (! isset($vendor->rating) || $vendor->rating == 0) {
                $vendor->rating = 4.5;
            }
            // Hanya set is_new_vendor = true jika belum di-set secara eksplisit.
            // Ini memungkinkan seeder atau admin mengisi nilai false untuk vendor berpengalaman.
            if (! isset($vendor->is_new_vendor)) {
                $vendor->is_new_vendor = true;
            }
        });
    }

    public function recalculateRatingAndNewStatus(): void
    {
        $completedProjects = DB::table('projects')
            ->join('client_vendor', function ($join) {
                $join->on('projects.client_id', '=', 'client_vendor.client_id')
                    ->on('projects.vendor_id', '=', 'client_vendor.vendor_id');
            })
            ->where('projects.vendor_id', $this->id)
            ->whereIn('projects.status_proyek', ['Finish Event', 'Transaksi Komplit'])
            ->select('client_vendor.skor_survey_klien')
            ->get();

        $n = $completedProjects->count();

        if ($n === 0) {
            $this->rating = 4.5;
            $this->is_new_vendor = true;
        } else {
            $sumScores = 0;
            foreach ($completedProjects as $p) {
                $sumScores += $p->skor_survey_klien ?? 3.0; // Fallback to 3.0 if null
            }
            $this->rating = round($sumScores / $n, 1);
            $this->is_new_vendor = false;
        }
        $this->save();
    }

    public static function getAvailableForClient(Client $client)
    {
        $isTier1 = $client->budget >= 50000000;

        $query = self::where('status_aktif', true);

        if ($isTier1) {
            $query->where('is_new_vendor', false);
        }

        // Try getting vendors within budget
        $vendors = (clone $query)->when($client->budget, function ($q) use ($client) {
            return $q->where('harga', '<=', $client->budget);
        })->orderBy('kategori_jasa')->get();

        // If empty and budget is set, fallback to closest above budget
        if ($vendors->isEmpty() && $client->budget) {
            $vendors = (clone $query)->where('harga', '>', $client->budget)
                ->orderBy('harga', 'asc')
                ->orderBy('kategori_jasa')
                ->get();

            foreach ($vendors as $v) {
                $v->warning_status = 'Melebihi Anggaran';
            }
        }

        return $vendors;
    }

    public static function getRecommendationsForClient(Client $client, ?string $category = null, ?string $location = null)
    {
        $isTier1 = $client->budget >= 50000000;

        /**
         * Helper: jalankan query rekomendasi dengan keyword lokasi tertentu.
         * Mengikuti urutan prioritas:
         *   1. Tier-1 (berpengalaman) + dalam budget
         *   2. Semua vendor (termasuk baru) + dalam budget
         *   3. Vendor di atas budget (dengan warning)
         */
        $queryWithLocation = function (?string $keyword) use ($client, $category, $isTier1) {
            $base = self::where('status_aktif', true);

            if ($category) {
                $base->where('kategori_jasa', $category);
            }

            if ($keyword) {
                $base->where('alamat', 'LIKE', '%'.$keyword.'%');
            }

            // Prioritas 1: Tier-1 + dalam budget
            if ($isTier1) {
                $vendors = (clone $base)
                    ->where('is_new_vendor', false)
                    ->when($client->budget, fn ($q) => $q->where('harga', '<=', $client->budget))
                    ->orderBy('rating', 'desc')
                    ->limit(3)
                    ->get();

                if ($vendors->isNotEmpty()) {
                    return $vendors;
                }
            }

            // Prioritas 2: Semua vendor (termasuk baru) + dalam budget
            $vendors = (clone $base)
                ->when($client->budget, fn ($q) => $q->where('harga', '<=', $client->budget))
                ->orderBy('rating', 'desc')
                ->limit(3)
                ->get();

            if ($vendors->isNotEmpty()) {
                return $vendors;
            }

            // Prioritas 3: Vendor di atas budget (sebagai alternatif dengan peringatan)
            if ($client->budget) {
                $vendors = (clone $base)
                    ->where('harga', '>', $client->budget)
                    ->orderBy('harga', 'asc')
                    ->orderBy('rating', 'desc')
                    ->limit(3)
                    ->get();

                foreach ($vendors as $v) {
                    $v->warning_status = 'Melebihi Anggaran';
                }

                if ($vendors->isNotEmpty()) {
                    return $vendors;
                }
            }

            return collect();
        };

        // --- LEVEL 1: Exact location match (misal: "Jakarta Pusat") ---
        if ($location) {
            $vendors = $queryWithLocation($location);

            if ($vendors->isNotEmpty()) {
                return $vendors;
            }

            // --- LEVEL 2: Broad city keyword (misal: "Jakarta" dari "Jakarta Pusat") ---
            $cityKeyword = explode(' ', trim($location))[0];
            if ($cityKeyword !== $location) {
                $vendors = $queryWithLocation($cityKeyword);

                if ($vendors->isNotEmpty()) {
                    return $vendors;
                }
            }
        }

        // --- LEVEL 3: Tanpa filter lokasi (fallback universal) ---
        return $queryWithLocation(null);
    }

    // =========================================================================
    // SCOPES — Filter berdasarkan Status Approval
    // =========================================================================

    /** Vendor yang baru mendaftar, belum ditinjau. */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status_approval', 'Pending');
    }

    /** Vendor yang sedang dalam proses peninjauan oleh Divisi SP. */
    public function scopeDitinjau(Builder $query): Builder
    {
        return $query->where('status_approval', 'Ditinjau');
    }

    /** Vendor yang telah disetujui oleh Manager Commercial. */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status_approval', 'Approved');
    }

    /** Vendor yang ditolak oleh Manager Commercial. */
    public function scopeDitolak(Builder $query): Builder
    {
        return $query->where('status_approval', 'Ditolak');
    }

    // =========================================================================
    // RELASI
    // =========================================================================

    public function documents()
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Relasi ke Client melalui pivot client_vendor.
     * Pivot table diperluas dengan kolom-kolom tracking komisi.
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_vendor')
            ->withPivot([
                'jumlah_komisi',
                'status_komisi',
                'tanggal_bayar_komisi',
                'jumlah_reminder_terkirim',
                'skor_survey_klien',
                'skor_kecepatan_komisi',
            ])
            ->withTimestamps();
    }
}
