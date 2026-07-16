<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvoiceTersediaNotification extends Notification
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
            'pesan' => 'Invoice untuk event "'.$this->project->nama_proyek.'" sudah tersedia. Segera minta klien melakukan pembayaran dan upload bukti transfer!',
            'jadwal' => now(),
            'project_id' => $this->project->id,
            'tipe' => 'invoice_tersedia',
        ];
    }
}
