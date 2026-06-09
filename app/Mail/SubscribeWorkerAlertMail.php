<?php

namespace App\Mail;

use App\Models\Subscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscribeWorkerAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscribe $subscribe) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Новая запись на прием',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.SubscribeWorkerAlertMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
