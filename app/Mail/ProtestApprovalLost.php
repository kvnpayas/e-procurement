<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProtestApprovalLost extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected $project, protected $vendor, protected $messageContent)
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
        subject: 'Bid Re-evaluation for Protest',
      );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
      return new Content(
        view: 'emails.protest-approval-lost',
        with: [
          'vendor' => $this->vendor,
          'projectbid' => $this->project,
          'messageContent' => $this->messageContent
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
