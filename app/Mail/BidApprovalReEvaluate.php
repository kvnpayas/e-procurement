<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class BidApprovalReEvaluate extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(protected $winningBidder, protected $bidding)
  {
    $this->winningBidder = $winningBidder;
    $this->bidding = $bidding;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      from: new Address('noreply@teiph.com', 'TEI'),
      subject: strtoupper($this->winningBidder) . ' WINNING BIDDER RE-EVALUATION FOR ' . $this->bidding->project_id . ' ' . strtoupper($this->bidding->title) . '.',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(
      view: 'emails.bid-approval-re-evaluate',
      with: [
        'winningBidder' => $this->winningBidder,
        'bidding' => $this->bidding,
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
