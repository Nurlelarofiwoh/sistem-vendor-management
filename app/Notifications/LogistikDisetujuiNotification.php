<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LogistikDisetujuiNotification extends Notification
{
    use Queueable;

    public function __construct(public Project $project) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pesan' => 'Logistik event "'.$this->project->nama_proyek.'" telah divalidasi. Event resmi BERJALAN!',
            'jadwal' => now(),
            'project_id' => $this->project->id,
            'tipe' => 'logistik_disetujui',
        ];
    }
}
