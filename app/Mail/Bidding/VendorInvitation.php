<?php

namespace App\Mail\Bidding;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\URL;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class VendorInvitation extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(protected ProjectBidding $projectbid)
  {
    $this->projectbid = $projectbid;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      from: new Address('noreply@teiph.com', 'TEI'),
      subject: 'BID INVITATION: ' . $this->projectbid->project_id . ' ' . strtoupper($this->projectbid->title),
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.invitation-project-bid',
      with: [
        'link' => URL::to('/').'/bid-invitation',
        'projectbid' => $this->projectbid,
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
