<?php

namespace App\Mail;

use App\Models\ProjectBidding;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class BidBulletinNotification extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(protected $data, protected $bidding, protected $vendor)
  {
    $this->data = $data;
    $this->bidding = $bidding;
    $this->vendor = $vendor;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      from: new Address('noreply@teiph.com', 'TEI'),
      subject: 'BID BULLETIN NO[' . $this->data['count_bulletin'] . ']: ' . $this->bidding->project_id . ' ' . strtoupper($this->bidding->title) . '.',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.bid-bulletin-notification',
      with: [
        'bulletin' => $this->data,
        'bidding' => $this->bidding,
        'vendor' => $this->vendor,
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
