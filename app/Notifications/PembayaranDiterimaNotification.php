<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranDiterimaNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Project $project,
        public string $peranPenerima = 'partnership'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $pesan = match ($this->peranPenerima) {
            'finance' => 'Pembayaran event "'.$this->project->nama_proyek.'" terverifikasi. Segera proses pembayaran fee jasa ke vendor!',
            'partnership' => 'Pembayaran event "'.$this->project->nama_proyek.'" terverifikasi. Segera buat jadwal Technical Meeting!',
            default => 'Pembayaran event "'.$this->project->nama_proyek.'" telah terverifikasi.',
        };

        return [
            'pesan' => $pesan,
            'jadwal' => now(),
            'project_id' => $this->project->id,
            'tipe' => 'pembayaran_diterima',
        ];
    }
}
