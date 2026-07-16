<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EvaluationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;

    public $token;

    public function __construct($project, $token)
    {
        $this->project = $project;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Bantu Kami Mengevaluasi Event Anda  - '.$this->project->nama_proyek)
            ->view('emails.evaluation');
    }
}
