@extends('emails.layout')
@section('subject', 'New contact message')

@section('content')
    @php
        $labels = [
            'general' => 'General', 'donation' => 'Donation',
            'volunteer' => 'Volunteer', 'visit' => 'Visit', 'partnership' => 'Partnership',
        ];
    @endphp
    <p style="margin:0 0 12px; font-size:16px; font-weight:bold; color:#b45309;">📩 New contact message</p>
    <p style="margin:0 0 16px; font-size:14px; line-height:1.6; color:#5b4a32;">
        Someone reached out through the website contact form. Details below:
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fdf7ec; border:1px solid #f0e2c8; border-radius:10px;">
        <tr><td style="padding:18px 20px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px; color:#5b4a32;">
                <tr><td style="padding:5px 0; color:#8a7a5c; width:35%;">Name</td><td style="padding:5px 0; text-align:right; font-weight:bold;">{{ $contact->name }}</td></tr>
                <tr><td style="padding:5px 0; color:#8a7a5c;">Email</td><td style="padding:5px 0; text-align:right;">{{ $contact->email }}</td></tr>
                @if($contact->phone)<tr><td style="padding:5px 0; color:#8a7a5c;">Phone</td><td style="padding:5px 0; text-align:right;">{{ $contact->phone }}</td></tr>@endif
                <tr><td style="padding:5px 0; color:#8a7a5c;">Type</td><td style="padding:5px 0; text-align:right;">{{ $labels[$contact->message_type] ?? $contact->message_type }}</td></tr>
                @if($contact->subject)<tr><td style="padding:5px 0; color:#8a7a5c;">Subject</td><td style="padding:5px 0; text-align:right;">{{ $contact->subject }}</td></tr>@endif
            </table>
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #f0e2c8;">
                <p style="margin:0 0 6px; font-size:12px; color:#8a7a5c;">Message</p>
                <p style="margin:0; font-size:14px; line-height:1.6; color:#3b2f1e; white-space:pre-line;">{{ $contact->message }}</p>
            </div>
        </td></tr>
    </table>

    <p style="margin:16px 0 0; font-size:13px; color:#5b4a32;">
        Reply directly to this email to respond to <strong>{{ $contact->name }}</strong>.
    </p>
@endsection
