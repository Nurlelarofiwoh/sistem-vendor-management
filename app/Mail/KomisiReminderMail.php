<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KomisiReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  object  $vendor  Data vendor (nama, email)
     * @param  object  $project  Data project (nama_proyek, tanggal_finish_event)
     * @param  int  $reminderKe  Urutan reminder: 1 (H+1), 2 (H+7), 3 (H+30)
     * @param  int  $sisaHari  Sisa hari sebelum batas maksimal 30 hari
     */
    public function __construct(
        public readonly object $vendor,
        public readonly object $project,
        public readonly int $reminderKe,
        public readonly int $sisaHari,
    ) {}

    public function envelope(): Envelope
    {
        $prefixes = ['', '[REMINDER]', '[PENTING]', '[PERINGATAN TERAKHIR]'];
        $prefix = $prefixes[$this->reminderKe] ?? '[REMINDER]';

        return new Envelope(
            subject: "{$prefix} Tagihan Komisi Event {$this->project->nama_proyek} — PT Liza Makmur Mandiri",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.komisi-reminder',
        );
    }
}
