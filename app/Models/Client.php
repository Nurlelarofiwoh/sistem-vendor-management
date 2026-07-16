<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_klien',
        'instansi',
        'email',
        'no_telepon',
        'kebutuhan_klien',
        'tanggal_acara',
        'tempat_acara',
        'vendor_terpilih_id',
        'is_vendor_acc',
        'budget',
        'catatan_operasional',
    ];

    protected $casts = [
        'is_vendor_acc' => 'boolean',
        'tanggal_acara' => 'date',
    ];

    public function vendorTerpilih()
    {
        return $this->belongsTo(Vendor::class, 'vendor_terpilih_id');
    }

    /**
     * Relasi Many-to-Many ke Vendor melalui pivot client_vendor.
     * Menyertakan semua kolom tracking komisi di pivot.
     */
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'client_vendor')
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

    /** Relasi ke Project (satu klien bisa punya satu project aktif). */
    public function project()
    {
        return $this->hasOne(Project::class);
    }
}
