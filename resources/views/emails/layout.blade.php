@php
    $trust = config('services.trust');
    $appName = config('app.name');
    $logo = asset('img/logo.png');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject', $appName)</title>
</head>
<body style="margin:0; padding:0; background:#f6f1e7; font-family:Arial, Helvetica, sans-serif; color:#3b2f1e;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f1e7; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:92%; background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    <!-- Header with logo -->
                    <tr>
                        <td style="background:#ffffff; padding:26px 32px 18px; text-align:center; border-bottom:3px solid #f59e0b;">
                            <img src="{{ $logo }}" alt="{{ $appName }}" width="180" style="width:180px; max-width:70%; height:auto; display:inline-block;">
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:28px 32px 8px;">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:22px 32px 26px; text-align:center; border-top:1px solid #f0e2c8;">
                            <p style="margin:0 0 8px; font-size:14px; font-weight:bold; color:#b45309;">{{ $trust['name'] ?? $appName }}</p>
                            <p style="margin:0 0 10px; font-size:12px; color:#8a7a5c; line-height:1.6;">
                                @if(!empty($trust['address'])){{ $trust['address'] }}<br>@endif
                                @if(!empty($trust['email']))✉ {{ $trust['email'] }}@endif
                                @if(!empty($trust['phone'])) &nbsp;•&nbsp; ☎ {{ $trust['phone'] }}@endif
                            </p>
                            <p style="margin:0; font-size:11px; color:#a89a7c;">© {{ date('Y') }} {{ $trust['name'] ?? $appName }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
                <p style="font-size:11px; color:#a89a7c; margin:16px 0 0;">🐄 Seva for Gau Mata &nbsp;•&nbsp; This is an automated email.</p>
            </td>
        </tr>
    </table>
</body>
</html>
