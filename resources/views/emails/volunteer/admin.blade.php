@extends('emails.layout')
@section('subject', 'New volunteer application')

@section('content')
    <p style="margin:0 0 12px; font-size:16px; font-weight:bold; color:#b45309;">🙋 New volunteer application</p>
    <p style="margin:0 0 16px; font-size:14px; line-height:1.6; color:#5b4a32;">
        A new volunteer signed up through the website. Details below:
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fdf7ec; border:1px solid #f0e2c8; border-radius:10px;">
        <tr><td style="padding:18px 20px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px; color:#5b4a32;">
                <tr><td style="padding:5px 0; color:#8a7a5c; width:38%;">Name</td><td style="padding:5px 0; text-align:right; font-weight:bold;">{{ $volunteer->full_name }}</td></tr>
                <tr><td style="padding:5px 0; color:#8a7a5c;">Email</td><td style="padding:5px 0; text-align:right;">{{ $volunteer->email }}</td></tr>
                <tr><td style="padding:5px 0; color:#8a7a5c;">Phone</td><td style="padding:5px 0; text-align:right;">{{ $volunteer->phone }}</td></tr>
                @if($volunteer->city || $volunteer->state)<tr><td style="padding:5px 0; color:#8a7a5c;">Location</td><td style="padding:5px 0; text-align:right;">{{ trim(($volunteer->city ?? '').' '.($volunteer->state ?? '')) }}</td></tr>@endif
                @if($volunteer->occupation)<tr><td style="padding:5px 0; color:#8a7a5c;">Occupation</td><td style="padding:5px 0; text-align:right;">{{ $volunteer->occupation }}</td></tr>@endif
                @if(!empty($volunteer->areas_of_interest))<tr><td style="padding:5px 0; color:#8a7a5c;">Interests</td><td style="padding:5px 0; text-align:right;">{{ implode(', ', (array) $volunteer->areas_of_interest) }}</td></tr>@endif
                @if(!empty($volunteer->availability))<tr><td style="padding:5px 0; color:#8a7a5c;">Availability</td><td style="padding:5px 0; text-align:right;">{{ implode(', ', (array) $volunteer->availability) }}</td></tr>@endif
            </table>
            @if($volunteer->motivation)
            <div style="margin-top:14px; padding-top:12px; border-top:1px solid #f0e2c8;">
                <p style="margin:0 0 6px; font-size:12px; color:#8a7a5c;">Motivation</p>
                <p style="margin:0; font-size:14px; line-height:1.6; color:#3b2f1e; white-space:pre-line;">{{ $volunteer->motivation }}</p>
            </div>
            @endif
        </td></tr>
    </table>

    <p style="margin:16px 0 0; font-size:13px; color:#5b4a32;">
        Reply directly to this email to reach <strong>{{ $volunteer->full_name }}</strong>.
    </p>
@endsection
