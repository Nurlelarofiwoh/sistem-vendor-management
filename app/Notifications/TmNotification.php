<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TmNotification extends Notification
{
    use Queueable;

    private $meeting;

    public function __construct($meeting)
    {
        $this->meeting = $meeting;
    }

    // Mengirim notifikasi ke database (Lonceng UI)
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Format data yang akan disimpan dan ditampilkan
    public function toArray(object $notifiable): array
    {
        return [
            'pesan' => 'Jadwal TM Baru: Proyek '.$this->meeting->project->nama_proyek,
            'jadwal' => $this->meeting->jadwal_tm,
        ];
    }
}
