@extends('emails.layout')
@section('subject', 'Your donation receipt')

@section('content')
    @php
        $title = fn ($s) => \Illuminate\Support\Str::title(str_replace('_', ' ', (string) $s));
        $purpose = $donation->category?->name
            ?? $donation->campaign?->title
            ?? ($donation->cow ? 'Cow Sponsorship — '.$donation->cow->name : 'General Donation');
        $donatedAt = $donation->paid_at ?? $donation->created_at;
    @endphp

    <p style="margin:0 0 12px; font-size:16px;">Dear {{ $donation->donor_name }},</p>
    <p style="margin:0 0 16px; font-size:14px; line-height:1.7; color:#5b4a32;">
        Hari Bol! 🙏 Thank you for your generous <span style="color:#b45309; font-weight:bold;">seva</span>.
        We have received your donation of <strong>₹{{ number_format($donation->amount) }}</strong>
        towards <strong>{{ $purpose }}</strong>. Your contribution goes straight toward fodder,
        medicine and shelter for our rescued cows.
    </p>

    <!-- Receipt details -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fdf7ec; border:1px solid #f0e2c8; border-radius:10px;">
        <tr><td style="padding:18px 20px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px; color:#5b4a32;">
                <tr>
                    <td style="padding:5px 0; color:#8a7a5c;">Receipt No.</td>
                    <td style="padding:5px 0; text-align:right; font-weight:bold; color:#3b2f1e;">{{ $donation->receipt_no }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0; color:#8a7a5c;">Reference No.</td>
                    <td style="padding:5px 0; text-align:right; font-family:monospace; color:#3b2f1e;">{{ $donation->reference_no }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0; color:#8a7a5c;">Date</td>
                    <td style="padding:5px 0; text-align:right;">{{ $donatedAt->format('d M Y, h:i A') }}</td>
                </tr>
                <tr>
                    <td style="padding:5px 0; color:#8a7a5c;">Payment Method</td>
                    <td style="padding:5px 0; text-align:right;">{{ $title($donation->payment_method) }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0 0; border-top:1px solid #f0e2c8; color:#8a7a5c; font-size:15px;">Amount Paid</td>
                    <td style="padding:10px 0 0; border-top:1px solid #f0e2c8; text-align:right; font-weight:bold; font-size:18px; color:#b45309;">₹{{ number_format($donation->amount) }}</td>
                </tr>
            </table>
        </td></tr>
    </table>

    <p style="margin:18px 0 6px; font-size:13px; line-height:1.6; color:#5b4a32;">
        📎 A detailed <strong>PDF invoice / 80G receipt</strong> is attached to this email for your records and tax filing.
    </p>
    @if ($donation->wants_80g_receipt && empty($donation->donor_pan))
        <p style="margin:0 0 6px; font-size:13px; color:#9a6b1a;">Note: To claim 80G tax benefit, please reply with your PAN if not already provided.</p>
    @endif

    <p style="margin:18px 0 2px; font-size:13px; color:#5b4a32;">With gratitude,</p>
    <p style="margin:0 0 2px; font-size:14px; font-weight:bold; color:#b45309;">{{ config('services.trust.name') ?? config('app.name') }}</p>
    <p style="margin:0; font-size:12px; color:#8a7a5c;">{{ $donatedAt->format('d M Y') }}</p>
@endsection
