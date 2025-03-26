<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected $user, protected $otp)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
      return new Envelope(
        from: new Address('noreply@teiph.com', 'TEI'),
        subject: 'Your One-Time Password (OTP) for Secure Access to e-Procurement ',
      );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
      return new Content(
        view: 'emails.otp-user',
        with: [
          'user' => $this->user->name,
          'otp' => $this->otp,
        ],
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
