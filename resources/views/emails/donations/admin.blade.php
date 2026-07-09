@extends('emails.layout')
@section('subject', 'New donation received')

@section('content')
    @php
        $title = fn ($s) => \Illuminate\Support\Str::title(str_replace('_', ' ', (string) $s));
        $purpose = $donation->category?->name
            ?? $donation->campaign?->title
            ?? ($donation->cow ? 'Cow Sponsorship — '.$donation->cow->name : 'General Donation');
        $donatedAt = $donation->paid_at ?? $donation->created_at ?? now();
    @endphp

    <p style="margin:0 0 12px; font-size:16px; font-weight:bold; color:#b45309;">🎉 New donation received</p>
    <p style="margin:0 0 16px; font-size:14px; line-height:1.6; color:#5b4a32;">
        A donation of <strong>₹{{ number_format((float) $donation->amount) }}</strong> came in through the website. Details below:
    </p>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fdf7ec; border:1px solid #f0e2c8; border-radius:10px;">
        <tr><td style="padding:18px 20px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px; color:#5b4a32;">
                <tr><td style="padding:5px 0; color:#8a7a5c; width:38%;">Donor</td><td style="padding:5px 0; text-align:right; font-weight:bold;">{{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }}</td></tr>
                @if($donation->donor_email)<tr><td style="padding:5px 0; color:#8a7a5c;">Email</td><td style="padding:5px 0; text-align:right;">{{ $donation->donor_email }}</td></tr>@endif
                @if($donation->donor_phone)<tr><td style="padding:5px 0; color:#8a7a5c;">Phone</td><td style="padding:5px 0; text-align:right;">{{ $donation->donor_phone }}</td></tr>@endif
                <tr><td style="padding:5px 0; color:#8a7a5c;">Purpose</td><td style="padding:5px 0; text-align:right;">{{ $purpose }}</td></tr>
                <tr><td style="padding:5px 0; color:#8a7a5c;">Method</td><td style="padding:5px 0; text-align:right;">{{ $title($donation->payment_method) }}</td></tr>
                <tr><td style="padding:5px 0; color:#8a7a5c;">Reference No.</td><td style="padding:5px 0; text-align:right; font-family:monospace;">{{ $donation->reference_no }}</td></tr>
                @if($donation->receipt_no)<tr><td style="padding:5px 0; color:#8a7a5c;">Receipt No.</td><td style="padding:5px 0; text-align:right;">{{ $donation->receipt_no }}</td></tr>@endif
                <tr><td style="padding:5px 0; color:#8a7a5c;">Date</td><td style="padding:5px 0; text-align:right;">{{ $donatedAt->format('d M Y, h:i A') }}</td></tr>
                <tr>
                    <td style="padding:10px 0 0; border-top:1px solid #f0e2c8; color:#8a7a5c; font-size:15px;">Amount</td>
                    <td style="padding:10px 0 0; border-top:1px solid #f0e2c8; text-align:right; font-weight:bold; font-size:18px; color:#b45309;">₹{{ number_format((float) $donation->amount) }}</td>
                </tr>
            </table>
        </td></tr>
    </table>

    <p style="margin:16px 0 0; font-size:13px; color:#5b4a32;">
        The donor has been sent their 80G receipt automatically. Reply to this email to reach them directly.
    </p>
@endsection
