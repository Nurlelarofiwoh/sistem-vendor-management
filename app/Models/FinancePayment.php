<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'jumlah_komisi',
        'tenggat_waktu',
        'bukti_transfer',
        'status_pembayaran',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
