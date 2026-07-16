<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvoiceReminderNotification extends Notification
{
    use Queueable;

    private $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Dikirim ke Lonceng UI
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pesan' => 'Proyek:'.$this->project->nama_proyek.'. Segera Drop Invoice!',
            'jadwal' => now(), // Waktu notifikasi masuk
        ];
    }
}
