@extends('emails.layout')
@section('subject', 'We received your message')

@section('content')
    <p style="margin:0 0 14px; font-size:16px;">Hello {{ $contact->name }},</p>
    <p style="margin:0 0 16px; font-size:14px; line-height:1.7; color:#5b4a32;">
        Hari Bol! 🙏 Thank you for reaching out to <strong>{{ config('app.name') }}</strong>.
        We have received your message and our team will get back to you shortly.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fdf7ec; border:1px solid #f0e2c8; border-radius:10px;">
        <tr><td style="padding:16px 20px;">
            <p style="margin:0 0 6px; font-size:12px; color:#8a7a5c;">Your message</p>
            <p style="margin:0; font-size:14px; line-height:1.6; color:#3b2f1e; white-space:pre-line;">{{ $contact->message }}</p>
        </td></tr>
    </table>

    <p style="margin:18px 0 0; font-size:14px; line-height:1.7; color:#5b4a32;">
        Your kindness towards Gau Mata means the world to us. 🐄
    </p>
@endsection
