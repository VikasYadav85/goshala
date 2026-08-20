<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'brevo' => [
        'key' => env('BREVO_API_KEY'),
    ],

    // Where admin/trust notification emails (contact, volunteer) are delivered.
    // Falls back to the From address if unset.
    'admin' => [
        'email' => env('ADMIN_NOTIFY_EMAIL', env('MAIL_FROM_ADDRESS')),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'razorpay' => [
        'key' => env('RAZORPAY_KEY', ''),
        'secret' => env('RAZORPAY_SECRET', ''),
        'currency' => env('RAZORPAY_CURRENCY', 'INR'),
    ],

    'upi' => [
        'vpa' => env('UPI_VPA', ''),
        'payee' => env('UPI_PAYEE_NAME', env('APP_NAME', 'Donation')),
    ],

    // Used on donation receipts / 80G tax invoices
    'trust' => [
        'name' => env('APP_NAME', 'Gopal Samarpan Sewa Charitable Trust'),
        'email' => env('TRUST_EMAIL'),
        'phone' => env('TRUST_PHONE'),
        'address' => env('TRUST_ADDRESS'),
        // Registered office (trust deed) — shown as the official address on 80G receipts.
        'registered_office' => env('TRUST_REGISTERED_OFFICE'),
        'pan' => env('TRUST_PAN'),
        '80g_number' => env('TRUST_80G_NUMBER'),
        'reg_number' => env('TRUST_REG_NUMBER'),
    ],

];
