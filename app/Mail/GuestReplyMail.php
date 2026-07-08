<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $guestName;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $message, $guestName = null)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->guestName = $guestName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.guest-reply',
            with: [
                'messageContent' => $this->message,
                'guestName' => $this->guestName,
            ]
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
