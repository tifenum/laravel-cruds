<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class CandidatureStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($candidature, $status)
    {
        $this->candidature = $candidature;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.candidature_status')
                    ->with([
                        'candidature' => $this->candidature,
                        'status' => $this->status,
                    ]);
    }
}
