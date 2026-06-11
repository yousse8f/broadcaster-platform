<?php

namespace App\Mail;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LicenseExpiringSoon extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $license;
    public $daysUntilExpiration;

    /**
     * Create a new message instance.
     */
    public function __construct(License $license, $daysUntilExpiration)
    {
        $this->license = $license;
        $this->daysUntilExpiration = $daysUntilExpiration;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your License Will Expire in {$this->daysUntilExpiration} Days",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.license-expiring-soon',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
