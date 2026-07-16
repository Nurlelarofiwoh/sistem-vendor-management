<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'jadwal_tm',
        'lokasi',
        'agenda',
        'status_tm',
    ];

    // Relasi: TM ini milik Proyek yang mana?
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
