<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Gopal Samarpan Sewa Charitable Trust — a sanctuary for rescued cows in Bharat. Donate, sponsor a cow, volunteer, and join our spiritual seva.')">

    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Gopal Samarpan Sewa Charitable Trust — Goshala for rescued cows.')">
    <meta property="og:image" content="@yield('og_image', asset('img/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    @php
        $seoSocial = collect($publicSettings['social'] ?? [])
            ->filter(fn ($u) => $u && $u !== '#')
            ->values()->all();
        $seoOrg = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'NGO',
            'name' => config('app.name'),
            'url' => url('/'),
            'logo' => asset('favicon.ico'),
            'description' => $publicSettings['footer_about'] ?? null,
            'telephone' => $publicSettings['phone'] ?? null,
            'email' => $publicSettings['email'] ?? null,
            'address' => ($publicSettings['address'] ?? null) ? [
                '@type' => 'PostalAddress',
                'streetAddress' => $publicSettings['address'],
                'addressCountry' => 'IN',
            ] : null,
            'sameAs' => $seoSocial ?: null,
        ]);
    @endphp
    <script type="application/ld+json">{!! json_encode($seoOrg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Tiro+Devanagari+Hindi&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-cream text-saffron-900/90 antialiased font-sans">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:bg-saffron-700 focus:text-white focus:px-4 focus:py-2 focus:rounded-full">Skip to content</a>

    @include('public.partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('public.partials.donate-cta')
    @include('public.partials.footer')

    @stack('scripts')
</body>
</html>
