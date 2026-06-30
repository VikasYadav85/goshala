@php
    $title = fn ($s) => \Illuminate\Support\Str::title(str_replace('_', ' ', (string) $s));
    $purpose = $donation->category?->name
        ?? $donation->campaign?->title
        ?? ($donation->cow ? 'Cow Sponsorship — '.$donation->cow->name : 'General Donation');
    $logo = public_path('img/logo.png');
    $hasLogo = is_file($logo);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #3b2f1e; font-size: 12px; margin: 0; }
        .wrap { padding: 32px 36px; }
        .header { border-bottom: 3px solid #b45309; padding-bottom: 14px; margin-bottom: 22px; }
        .header td { vertical-align: middle; }
        .brand { font-size: 20px; font-weight: bold; color: #b45309; }
        .sub { font-size: 11px; color: #8a7a5c; margin-top: 3px; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 18px; margin: 0; color: #3b2f1e; letter-spacing: 1px; }
        .doc-title .small { font-size: 11px; color: #8a7a5c; }
        table { width: 100%; border-collapse: collapse; }
        .meta { margin: 18px 0 24px; }
        .meta td { padding: 3px 0; font-size: 12px; }
        .meta .label { color: #8a7a5c; width: 130px; }
        .meta .value { font-weight: bold; }
        .panel { width: 48%; }
        .panel h3 { font-size: 11px; text-transform: uppercase; color: #8a7a5c; margin: 0 0 6px; letter-spacing: 0.5px; }
        .panel p { margin: 0; line-height: 1.6; font-size: 12px; }
        .items { margin-top: 8px; border: 1px solid #e8dcc2; }
        .items th { background: #fdf7ec; text-align: left; padding: 10px 12px; font-size: 11px; text-transform: uppercase; color: #8a7a5c; border-bottom: 1px solid #e8dcc2; }
        .items td { padding: 12px; font-size: 12px; border-bottom: 1px solid #f0e8d8; }
        .items .amt { text-align: right; font-weight: bold; }
        .total-row td { padding: 12px; font-size: 14px; font-weight: bold; }
        .total-row .lbl { text-align: right; color: #5b4a32; }
        .total-row .amt { text-align: right; color: #b45309; font-size: 16px; }
        .note { margin-top: 22px; padding: 12px 14px; background: #fdf7ec; border: 1px solid #f0e2c8; border-radius: 6px; font-size: 11px; color: #5b4a32; line-height: 1.6; }
        .footer { margin-top: 28px; text-align: center; font-size: 10px; color: #a89a7c; border-top: 1px solid #e8dcc2; padding-top: 12px; }
        .status { display: inline-block; padding: 2px 10px; border-radius: 10px; background: #e6f4ea; color: #1e7a3a; font-size: 11px; font-weight: bold; }
    </style>
</head>
<body>
<div class="wrap">
    <table class="header">
        <tr>
            <td style="width:60%;">
                @if($hasLogo)
                    <img src="{{ $logo }}" alt="logo" style="height:46px;">
                @endif
                <div class="brand">{{ $trust['name'] }}</div>
                <div class="sub">
                    @if($trust['address']){{ $trust['address'] }}<br>@endif
                    @if($trust['email']){{ $trust['email'] }}@endif @if($trust['phone']) • {{ $trust['phone'] }}@endif
                </div>
            </td>
            <td class="doc-title">
                <h1>DONATION RECEIPT</h1>
                <div class="small">80G Tax Invoice</div>
                @if($trust['80g_number'])<div class="small">80G Reg: {{ $trust['80g_number'] }}</div>@endif
                @if($trust['pan'])<div class="small">PAN: {{ $trust['pan'] }}</div>@endif
                @if($trust['reg_number'])<div class="small">Reg No: {{ $trust['reg_number'] }}</div>@endif
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td class="label">Receipt No.</td>
            <td class="value">{{ $donation->receipt_no }}</td>
            <td class="label">Reference No.</td>
            <td class="value">{{ $donation->reference_no }}</td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td class="value">{{ ($donation->paid_at ?? $donation->created_at)->format('d M Y, h:i A') }}</td>
            <td class="label">Status</td>
            <td><span class="status">{{ $title($donation->payment_status) }}</span></td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="panel">
                <h3>Donor</h3>
                <p>
                    <strong>{{ $donation->is_anonymous ? 'Anonymous Donor' : $donation->donor_name }}</strong><br>
                    @unless($donation->is_anonymous)
                        {{ $donation->donor_email }}<br>
                        @if($donation->donor_phone){{ $donation->donor_phone }}<br>@endif
                        @if($donation->donor_address){{ $donation->donor_address }}<br>@endif
                        {{ trim(collect([$donation->donor_city, $donation->donor_state, $donation->donor_pincode])->filter()->join(', ')) }}
                        {{ $donation->donor_country }}<br>
                        @if($donation->donor_pan)PAN: {{ $donation->donor_pan }}@endif
                    @endunless
                </p>
            </td>
            <td class="panel" style="text-align:right;">
                <h3>Payment</h3>
                <p>
                    Method: {{ $title($donation->payment_method) }}<br>
                    Frequency: {{ $title($donation->frequency) }}<br>
                    @if($donation->razorpay_payment_id)Txn: {{ $donation->razorpay_payment_id }}<br>@endif
                </p>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align:right; width:140px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $purpose }}
                    @if($donation->dedication)<br><span style="color:#8a7a5c; font-size:11px;">Dedication: {{ $donation->dedication }}</span>@endif
                </td>
                <td class="amt">₹{{ number_format($donation->amount) }}</td>
            </tr>
            <tr class="total-row">
                <td class="lbl">Total Donation</td>
                <td class="amt">₹{{ number_format($donation->amount) }} {{ $donation->currency }}</td>
            </tr>
        </tbody>
    </table>

    <div class="note">
        @if($donation->wants_80g_receipt)
            This donation is eligible for tax deduction under Section 80G of the Income Tax Act, 1961.
            Please retain this receipt for your records.
            @if(empty($donation->donor_pan)) To claim the benefit, kindly share your PAN with us.@endif
        @else
            Thank you for your generous contribution toward the care of our rescued cows.
        @endif
    </div>

    <div class="footer">
        This is a computer-generated receipt and does not require a physical signature.<br>
        {{ $trust['name'] }} — Serving Gau Mata with Devotion, Compassion &amp; Humanity.
    </div>
</div>
</body>
</html>
