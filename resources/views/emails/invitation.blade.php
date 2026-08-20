@extends('emails.layout')
@section('subject', 'You are invited — '.$invitation->occasion)

@section('content')
    @php
        $trust = config('services.trust');
        $mapQuery = $invitation->venue ?: ($trust['address'] ?? '');
        $mapUrl = 'https://www.google.com/maps/search/?api=1&query='.urlencode($mapQuery);
    @endphp

    <p style="margin:0 0 4px; font-size:13px; letter-spacing:2px; text-transform:uppercase; color:#b45309; text-align:center;">🙏 With warm regards, you are invited</p>
    <h1 style="margin:0 0 18px; font-size:24px; color:#7c2d12; text-align:center; font-weight:bold;">{{ $invitation->occasion }}</h1>

    <p style="margin:0 0 12px; font-size:15px; line-height:1.7; color:#3b2f1e;">
        Respected <strong>{{ $invitation->invitee_name }}</strong>,
    </p>
    <p style="margin:0 0 18px; font-size:15px; line-height:1.7; color:#5b4a32;">
        It is our privilege to cordially invite you on behalf of <strong>{{ $trust['name'] ?? config('app.name') }}</strong>
        to grace the occasion of <strong>{{ $invitation->occasion }}</strong>. Your presence and blessings would mean a great deal to us and to our Gau Mata.
    </p>

    {{-- Event details --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fdf7ec; border:1px solid #f0e2c8; border-radius:10px; margin:0 0 18px;">
        <tr><td style="padding:18px 20px;">
            <p style="margin:0 0 10px; font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#8a7a5c;">Event details</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#3b2f1e;">
                <tr><td style="padding:5px 0; color:#8a7a5c; width:32%;">Occasion</td><td style="padding:5px 0; font-weight:bold;">{{ $invitation->occasion }}</td></tr>
                @if($invitation->event_date)<tr><td style="padding:5px 0; color:#8a7a5c;">Date</td><td style="padding:5px 0; font-weight:bold;">{{ $invitation->event_date->format('l, d F Y') }}</td></tr>@endif
                @if($invitation->event_time)<tr><td style="padding:5px 0; color:#8a7a5c;">Time</td><td style="padding:5px 0; font-weight:bold;">{{ $invitation->event_time }}</td></tr>@endif
                @if($invitation->venue)<tr><td style="padding:5px 0; color:#8a7a5c; vertical-align:top;">Venue</td><td style="padding:5px 0; font-weight:bold;">{{ $invitation->venue }}</td></tr>@endif
            </table>
        </td></tr>
    </table>

    @if($invitation->message)
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 18px;">
            <tr><td style="padding:14px 18px; background:#fff8ef; border-left:4px solid #f59e0b; border-radius:6px;">
                <p style="margin:0; font-size:14px; line-height:1.7; color:#5b4a32; white-space:pre-line; font-style:italic;">{{ $invitation->message }}</p>
            </td></tr>
        </table>
    @endif

    {{-- CTA --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:6px 0 20px;">
        <tr><td align="center">
            <a href="{{ $mapUrl }}" style="display:inline-block; background:#ea580c; color:#ffffff; text-decoration:none; font-size:14px; font-weight:bold; padding:11px 22px; border-radius:8px; margin:4px;">📍 Get Directions</a>
            <a href="{{ url('/') }}" style="display:inline-block; background:#ffffff; color:#b45309; text-decoration:none; font-size:14px; font-weight:bold; padding:11px 22px; border-radius:8px; margin:4px; border:1px solid #fed7aa;">🌐 Visit Website</a>
        </td></tr>
    </table>

    {{-- Trust contact --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #f0e2c8; margin-top:6px;">
        <tr><td style="padding:16px 2px 2px;">
            <p style="margin:0 0 8px; font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#8a7a5c;">Reach us</p>
            @if(!empty($trust['address']))<p style="margin:0 0 6px; font-size:13px; line-height:1.6; color:#5b4a32;"><strong>Goshala:</strong> {{ $trust['address'] }}</p>@endif
            @if(!empty($trust['registered_office']))<p style="margin:0 0 8px; font-size:13px; line-height:1.6; color:#5b4a32;"><strong>Registered Office:</strong> {{ $trust['registered_office'] }}</p>@endif
            <p style="margin:0; font-size:13px; line-height:1.7; color:#5b4a32;">
                @php $phones = array_filter([$trust['phone'] ?? null, $trust['phone2'] ?? null, $trust['phone3'] ?? null]); @endphp
                @php $emails = array_filter([$trust['email'] ?? null, $trust['email2'] ?? null, $trust['email3'] ?? null]); @endphp
                @if($phones)☎ {{ implode(' , ', $phones) }}<br>@endif
                @if($emails)✉ {{ implode(' , ', $emails) }}<br>@endif
                @if(!empty($trust['whatsapp']))💬 WhatsApp: {{ $trust['whatsapp'] }}@endif
            </p>
        </td></tr>
    </table>

    <p style="margin:20px 0 0; font-size:14px; line-height:1.7; color:#3b2f1e;">
        We look forward to welcoming you. 🐄🙏<br>
        <span style="color:#8a7a5c;">— {{ $trust['name'] ?? config('app.name') }}</span>
    </p>
@endsection
