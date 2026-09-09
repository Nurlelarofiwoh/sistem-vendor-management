<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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
        'skor_finansial',      // Kepatuhan komisi: 3=Lancar, 2=Warning, 1=Sengketa
        'is_new_vendor',
        'status_aktif',
        'status_approval',     // Alur disposisi: Pending → Ditinjau → Approved / Ditolak
        'catatan_tolak',       // Catatan penolakan dari Manager Commercial
        'alasan_penolakan',    // Catatan penolakan Moa/MoU tambahan
        'tanggal_ditolak',     // Tanggal penolakan MoU oleh Manager Commercial
        'tanggal_kontrak_habis',
        'total_review',
        'status_kemitraan',    // Status kemitraan vendor ('Vendor Baru', 'Preferred', 'Under Review')
    ];

    protected function casts(): array
    {
        return [
            'detail_spesifikasi' => 'array',
            'status_aktif' => 'boolean',
            'rating' => 'decimal:1',
            'skor_finansial' => 'integer',
            'is_new_vendor' => 'boolean',
            'harga' => 'decimal:2',
            'tanggal_ditolak' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($vendor) {
            // Vendor baru selalu NULL — penanda "belum ada data penilaian".
            // Rating baru akan terbentuk otomatis setelah proyek pertama selesai
            // melalui recalculateRatingAndNewStatus().
            if (! isset($vendor->rating)) {
                $vendor->rating = null;
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
        $reviews = $this->reviews()->get();
        $n = $reviews->count();

        if ($n === 0) {
            // NULL = belum ada proyek selesai yang direview, tampil sebagai Challenger slot.
            $this->rating = null;
            $this->is_new_vendor = true;
            $this->status_kemitraan = 'Vendor Baru';
        } else {
            $sumScores = $reviews->sum('score');
            $avgRating = round($sumScores / $n, 1);
            $this->rating = $avgRating;
            $this->is_new_vendor = false;

            if ($avgRating < 3.5) {
                $this->status_kemitraan = 'Under Review';
            } else {
                $this->status_kemitraan = 'Preferred';
            }
        }
        $this->save();
    }

    public static function getAvailableForClient(Client $client)
    {
        $tierInfo = self::determineTierInfo((float) ($client->budget ?? 0));

        $query = self::where('status_aktif', true)
            ->where('status_approval', 'Approved');

        // Tier 1: hanya vendor berpengalaman di daftar pilihan manual CS
        if ($tierInfo['tier'] === 1) {
            $query->where('is_new_vendor', false);
        }

        // Try getting vendors within budget / tier ceiling
        $vendors = (clone $query)->when($tierInfo['harga_max'], function ($q) use ($tierInfo) {
            return $q->where('harga', '<=', $tierInfo['harga_max']);
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

    /**
     * Algoritma Top-N Curated Shortlisting dengan Challenger Slot.
     *
     * Mengembalikan MAKSIMAL 3 vendor per kategori per lokasi:
     *   - 2 slot "Proven Vendors" : rating IS NOT NULL, orderBy rating DESC
     *   - 1 slot "Challenger"     : rating IS NULL, orderBy harga ASC (vendor baru termurah)
     *
     * EKSKLUSI FINANSIAL: vendor dengan skor_finansial = 1 (Sengketa) dibuang
     * dari seluruh query, tidak peduli nilai rating-nya.
     *
     * Tiga level fallback lokasi:
     *   Level 1 → exact lokasi ("Jakarta Selatan")
     *   Level 2 → city keyword saja ("Jakarta")
     *   Level 3 → tanpa filter lokasi (universal fallback)
     */
    public static function getTopNShortlist(
        Client $client,
        string $kategori,
        ?string $lokasi
    ): Collection {
        $tierInfo = self::determineTierInfo((float) ($client->budget ?? 0));

        /**
         * Jalankan shortlisting pada satu keyword lokasi tertentu.
         * Return Collection (bisa kosong); caller mencoba level berikutnya jika kosong.
         */
        $shortlistForLocation = function (?string $keyword) use ($kategori, $tierInfo): Collection {
            /** @var Builder $base */
            $base = self::where('status_aktif', true)
                ->where('status_approval', 'Approved')
                ->where('kategori_jasa', $kategori)
                ->where('skor_finansial', '>', 1); // Eksklusi finansial: buang vendor Sengketa

            // Filter lokasi jika keyword tersedia
            if ($keyword) {
                $base->where('alamat', 'LIKE', '%'.$keyword.'%');
            }

            // Filter harga sesuai tier budget klien
            if ($tierInfo['harga_max'] !== null) {
                $base->where('harga', '<=', $tierInfo['harga_max']);
            }

            // Tier 1: Proven slot hanya dari vendor berpengalaman
            $provenBase = clone $base;
            if ($tierInfo['tier'] === 1) {
                $provenBase->where('is_new_vendor', false);
            }

            // Slot A — 2 Proven Vendors: rating IS NOT NULL, rating tertinggi
            $provenVendors = (clone $provenBase)
                ->whereNotNull('rating')
                ->orderBy('rating', 'desc')
                ->limit(2)
                ->get();

            // Slot B — 1 Challenger: rating IS NULL, harga terendah (deterministik)
            // Menggunakan harga ASC agar output stabil dan tidak berubah tiap reload.
            $challengerVendor = (clone $base)
                ->whereNull('rating')
                ->orderBy('harga', 'asc')
                ->limit(1)
                ->get();

            return $provenVendors->merge($challengerVendor)->take(3);
        };

        // Level 1: Exact location match
        if ($lokasi) {
            $result = $shortlistForLocation($lokasi);
            if ($result->isNotEmpty()) {
                return $result;
            }

            // Level 2: City keyword (kata pertama dari lokasi)
            $cityKeyword = explode(' ', trim($lokasi))[0];
            if ($cityKeyword !== $lokasi) {
                $result = $shortlistForLocation($cityKeyword);
                if ($result->isNotEmpty()) {
                    return $result;
                }
            }
        }

        // Level 3: Tanpa filter lokasi (universal fallback)
        return $shortlistForLocation(null);
    }

    /**
     * Public proxy untuk mengekspos informasi tier ke luar model (Controller/View).
     * Dipisah agar `determineTierInfo` tetap private untuk keperluan internal.
     *
     * @return array{tier: int, label: string, harga_max: float|null}
     */
    public static function exposeTierInfo(float $budget): array
    {
        return self::determineTierInfo($budget);
    }

    private static function determineTierInfo(float $budget): array
    {
        if ($budget <= 0) {
            // Tanpa budget: tidak ada batas harga, tampilkan semua
            return ['tier' => 2, 'label' => 'Regular', 'harga_max' => null];
        }

        if ($budget > 150_000_000) {
            return ['tier' => 1, 'label' => 'Premium', 'harga_max' => $budget];
        }

        if ($budget >= 25_000_000) {
            return ['tier' => 2, 'label' => 'Regular', 'harga_max' => $budget];
        }

        // Tier 3: budget < 25 juta — batasi vendor di bawah 25 juta
        return ['tier' => 3, 'label' => 'Standard', 'harga_max' => 25_000_000];
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
    // SCOPES — Filter berdasarkan Skor Finansial (Kepatuhan Komisi)
    // =========================================================================

    /**
     * Vendor dalam status Sengketa — telat bayar komisi > 90 hari.
     * Vendor ini DIEKSKLUSI dari shortlisting rekomendasi klien.
     */
    public function scopeSengketa(Builder $query): Builder
    {
        return $query->where('skor_finansial', 1);
    }

    /** Vendor yang mendapat peringatan keterlambatan pembayaran komisi. */
    public function scopeWarningFinansial(Builder $query): Builder
    {
        return $query->where('skor_finansial', 2);
    }

    /** Vendor dengan kepatuhan komisi terbaik — pembayaran lancar. */
    public function scopeLancar(Builder $query): Builder
    {
        return $query->where('skor_finansial', 3);
    }

    // =========================================================================
    // RELASI & ACCESSOR
    // =========================================================================

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getHariTelatKomisiAttribute(): int
    {
        // Hitung tagihan komisi yang paling lama telat (yang belum lunas)
        $unpaidCommissions = DB::table('client_vendor')
            ->join('projects', 'client_vendor.client_id', '=', 'projects.client_id')
            ->where('client_vendor.vendor_id', $this->id)
            ->where('client_vendor.status_komisi', '!=', 'Lunas')
            ->whereNotNull('projects.tanggal_finish_event')
            ->select('projects.tanggal_finish_event')
            ->get();

        $maxLateDays = 0;
        foreach ($unpaidCommissions as $commission) {
            $days = now()->diffInDays($commission->tanggal_finish_event);
            if ($days > $maxLateDays) {
                $maxLateDays = $days;
            }
        }

        return $maxLateDays;
    }

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
