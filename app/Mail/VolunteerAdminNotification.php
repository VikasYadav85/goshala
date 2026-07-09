<?php

namespace App\Mail;

use App\Models\Volunteer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VolunteerAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Volunteer $volunteer) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New volunteer application — '.$this->volunteer->full_name,
            replyTo: [new Address($this->volunteer->email, $this->volunteer->full_name)],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.volunteer.admin', with: ['volunteer' => $this->volunteer]);
    }
}
