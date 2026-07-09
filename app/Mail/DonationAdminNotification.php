<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation)
    {
        $this->donation->loadMissing(['category', 'campaign', 'cow']);
    }

    public function envelope(): Envelope
    {
        $replyTo = $this->donation->donor_email
            ? [new Address($this->donation->donor_email, $this->donation->donor_name ?? '')]
            : [];

        return new Envelope(
            subject: 'New donation received — ₹'.number_format((float) $this->donation->amount),
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.donations.admin', with: ['donation' => $this->donation]);
    }
}
