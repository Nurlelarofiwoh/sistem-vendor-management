<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'nama_proyek',
        'status_proyek',
        'nominal_invoice',
        'is_evaluated',
        'is_notifikasi_terkirim',
        'invoice_path',
        'bukti_pembayaran_path',
        'catatan_finance',
        'catatan_operasional',
        'tanggal_komisi_jatuh_tempo',
        'tanggal_komisi_dibayar',
        'penalti_rating',
        'evaluation_token',
        'skor_evaluasi_klien',     // Skor numerik (1-5) dari survey evaluasi klien
        'tanggal_finish_event',    // Tanggal event dinyatakan selesai oleh Manajer Ops
        'total_reminder_terkirim', // Agregat jumlah reminder email yang sudah dikirim
    ];

    protected function casts(): array
    {
        return [
            'is_evaluated' => 'boolean',
            'is_notifikasi_terkirim' => 'boolean',
            'tanggal_komisi_jatuh_tempo' => 'date',
            'tanggal_komisi_dibayar' => 'date',
            'tanggal_finish_event' => 'date',
            'penalti_rating' => 'decimal:1',
            'nominal_invoice' => 'decimal:2',
            'skor_evaluasi_klien' => 'decimal:1',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function technicalMeeting()
    {
        return $this->hasOne(TechnicalMeeting::class);
    }

    public function financePayment()
    {
        return $this->hasOne(FinancePayment::class);
    }
}
