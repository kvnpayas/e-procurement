<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class BidApprovalAPproved extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(protected $winningBidder, protected $bidding, protected $approverId)
  {
    $this->winningBidder = $winningBidder;
    $this->bidding = $bidding;
    $this->approverId = $approverId;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      from: new Address('noreply@teiph.com', 'TEI'),
      subject: strtoupper($this->winningBidder) . ' WINNING BIDDER FOR  ' . $this->bidding->project_id . ' ' . strtoupper($this->bidding->title) . '.',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.bid-approval-approved',
      with: [
        'winningBidder' => $this->winningBidder,
        'bidding' => $this->bidding,
        'approverId' => $this->approverId,
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
