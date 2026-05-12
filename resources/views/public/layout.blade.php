<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Gopal Seva Samarpan Trust — a sanctuary for rescued cows in Bharat. Donate, sponsor a cow, volunteer, and join our spiritual seva.')">

    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Gopal Seva Samarpan Trust — Goshala for rescued cows.')">
    <meta property="og:image" content="@yield('og_image', asset('img/og-default.jpg'))">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Tiro+Devanagari+Hindi&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
