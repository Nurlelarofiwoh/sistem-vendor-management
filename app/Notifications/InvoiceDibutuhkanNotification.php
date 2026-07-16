<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvoiceDibutuhkanNotification extends Notification
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
            'pesan' => 'Admin CS meminta Invoice untuk event "'.$this->project->nama_proyek.'". Segera buat invoice!',
            'jadwal' => now(),
            'project_id' => $this->project->id,
            'tipe' => 'invoice_dibutuhkan',
        ];
    }
}
