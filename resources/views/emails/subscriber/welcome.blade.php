@extends('emails.layout')
@section('subject', 'Welcome to our seva community')

@section('content')
    <p style="margin:0 0 14px; font-size:16px;">Hello {{ $subscriber->name ?: 'Gau Sevak' }},</p>
    <p style="margin:0 0 16px; font-size:14px; line-height:1.7; color:#5b4a32;">
        Hari Bol! 🙏 Welcome to the <strong>{{ config('app.name') }}</strong> seva community.
        You're now subscribed to our updates — we'll share stories of rescued cows, upcoming
        events, and ways you can help serve Gau Mata.
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center" style="padding:6px 0 4px;">
            <a href="{{ route('donations.index') }}" style="display:inline-block; background:#b45309; color:#ffffff; text-decoration:none; font-size:14px; font-weight:bold; padding:12px 28px; border-radius:999px;">🪔 Support a Cow</a>
        </td></tr>
    </table>

    <p style="margin:18px 0 0; font-size:14px; line-height:1.7; color:#5b4a32;">
        Thank you for joining us on this journey of compassion. 🐄
    </p>
@endsection
