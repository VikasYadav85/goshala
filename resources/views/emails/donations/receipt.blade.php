@php
    $title = fn ($s) => \Illuminate\Support\Str::title(str_replace('_', ' ', (string) $s));
    $purpose = $donation->category?->name
        ?? $donation->campaign?->title
        ?? ($donation->cow ? 'Cow Sponsorship — '.$donation->cow->name : 'General Donation');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
</head>
<body style="margin:0; padding:0; background:#f6f1e7; font-family:Arial, Helvetica, sans-serif; color:#3b2f1e;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f1e7; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:92%; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#f59e0b,#b45309); padding:28px 32px; text-align:center; color:#fff;">
                            <div style="font-size:34px; line-height:1; margin-bottom:8px;">🪔</div>
                            <div style="font-size:20px; font-weight:bold;">{{ $trust['name'] }}</div>
                            <div style="font-size:13px; opacity:0.9; margin-top:4px;">Donation Receipt &amp; 80G Tax Invoice</div>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="padding:28px 32px 8px;">
                            <p style="margin:0 0 12px; font-size:15px;">Dear {{ $donation->donor_name }},</p>
                            <p style="margin:0 0 16px; font-size:14px; line-height:1.6; color:#5b4a32;">
                                Hari Bol! 🙏 Thank you for your generous <span style="color:#b45309; font-weight:bold;">seva</span>.
                                We have received your donation of
                                <strong>₹{{ number_format($donation->amount) }}</strong> towards
                                <strong>{{ $purpose }}</strong>. Your contribution goes straight toward fodder,
                                medicine and shelter for our rescued cows.
                            </p>
                        </td>
                    </tr>

                    <!-- Receipt box -->
                    <tr>
                        <td style="padding:8px 32px 4px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fdf7ec; border:1px solid #f0e2c8; border-radius:10px;">
                                <tr>
                                    <td style="padding:18px 20px;">
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
                                                <td style="padding:5px 0; text-align:right;">{{ ($donation->paid_at ?? $donation->created_at)->format('d M Y, h:i A') }}</td>
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
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 32px 4px; font-size:13px; line-height:1.6; color:#5b4a32;">
                            <p style="margin:0 0 8px;">📎 A detailed <strong>PDF invoice / 80G receipt</strong> is attached to this email for your records and tax filing.</p>
                            @if ($donation->wants_80g_receipt && empty($donation->donor_pan))
                                <p style="margin:0; color:#9a6b1a;">Note: To claim 80G tax benefit, please reply with your PAN if not already provided.</p>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:22px 32px 28px; text-align:center; border-top:1px solid #f0e2c8; margin-top:12px;">
                            <p style="margin:0 0 4px; font-size:13px; color:#5b4a32;">With gratitude,</p>
                            <p style="margin:0 0 10px; font-size:14px; font-weight:bold; color:#b45309;">{{ $trust['name'] }}</p>
                            <p style="margin:0; font-size:12px; color:#8a7a5c;">
                                @if($trust['address']){{ $trust['address'] }}<br>@endif
                                @if($trust['email'])✉ {{ $trust['email'] }}@endif
                                @if($trust['phone']) &nbsp;•&nbsp; ☎ {{ $trust['phone'] }}@endif
                            </p>
                        </td>
                    </tr>
                </table>
                <p style="font-size:11px; color:#a89a7c; margin:16px 0 0;">This is a computer-generated receipt.</p>
            </td>
        </tr>
    </table>
</body>
</html>
