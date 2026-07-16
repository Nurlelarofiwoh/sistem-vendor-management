<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'nama_dokumen',
        'file_path',
        'status_approval',
        'tanggal_berakhir', // <-- TAMBAHKAN BARIS INI
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
