<?php

namespace App\Mail;

use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation)
    {
        $this->donation->loadMissing(['category', 'campaign', 'cow']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your donation receipt — '.$this->donation->reference_no,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donations.receipt',
            with: ['donation' => $this->donation, 'trust' => config('services.trust')],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdf.donation-invoice', [
            'donation' => $this->donation,
            'trust' => config('services.trust'),
        ])->setPaper('a4');

        $name = 'Donation-Receipt-'.$this->donation->reference_no.'.pdf';

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), $name)
                ->withMime('application/pdf'),
        ];
    }
}
