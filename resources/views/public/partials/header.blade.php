<header x-data="{ open: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 8 })"
        :class="scrolled ? 'shadow-md bg-white/95' : 'bg-white/90'"
        class="sticky top-0 z-50 backdrop-blur border-b border-saffron-100 transition-all">

    {{-- Top bar --}}
    <div class="hidden md:block bg-saffron-900 text-saffron-50 text-sm">
        <div class="container mx-auto px-4 py-2 flex items-center justify-between">
            <div class="flex items-center gap-5">
                <a href="tel:{{ $publicSettings['phone'] }}" class="flex items-center gap-1 hover:text-saffron-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.5L9 7l-2 1a11 11 0 005 5l1-2 4 1.5V15a2 2 0 01-2 2A14 14 0 013 5z"/></svg>
                    {{ $publicSettings['phone'] }}
                </a>
                <a href="mailto:{{ $publicSettings['email'] }}" class="flex items-center gap-1 hover:text-saffron-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $publicSettings['email'] }}
                </a>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="bg-saffron-700/40 px-2 py-0.5 rounded-full">Registered Trust</span>
                <span class="bg-saffron-700/40 px-2 py-0.5 rounded-full">80G Tax Exempt</span>
                <span class="bg-saffron-700/40 px-2 py-0.5 rounded-full">12A Certified</span>
            </div>
        </div>
    </div>

    {{-- Main nav --}}
    <div class="container mx-auto px-4 py-3 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center" aria-label="Gopal Samarpan Sewa Charitable Trust — Home">
            <img src="{{ asset('img/logo.png') }}?v={{ @filemtime(public_path('img/logo.png')) }}" alt="Gopal Samarpan Sewa Charitable Trust" class="h-14 md:h-16 w-auto">
        </a>

        <nav class="hidden lg:flex items-center gap-6 text-sm font-medium text-saffron-900">
            <a href="{{ route('home') }}" class="hover:text-saffron-600 {{ request()->routeIs('home') ? 'text-saffron-600' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="hover:text-saffron-600 {{ request()->routeIs('about') ? 'text-saffron-600' : '' }}">About</a>
            <a href="{{ route('goshala') }}" class="hover:text-saffron-600 {{ request()->routeIs('goshala') ? 'text-saffron-600' : '' }}">Our Goshala</a>
            <a href="{{ route('donations.index') }}" class="hover:text-saffron-600 {{ request()->routeIs('donations.*') ? 'text-saffron-600' : '' }}">Seva &amp; Donation</a>
            <a href="{{ route('campaigns.index') }}" class="hover:text-saffron-600 {{ request()->routeIs('campaigns.*') ? 'text-saffron-600' : '' }}">Campaigns</a>
            <a href="{{ route('events.index') }}" class="hover:text-saffron-600 {{ request()->routeIs('events.*') ? 'text-saffron-600' : '' }}">Events</a>
            <a href="{{ route('gallery.index') }}" class="hover:text-saffron-600 {{ request()->routeIs('gallery.*') ? 'text-saffron-600' : '' }}">Gallery</a>
            <a href="{{ route('blog.index') }}" class="hover:text-saffron-600 {{ request()->routeIs('blog.*') ? 'text-saffron-600' : '' }}">Blog</a>
            <a href="{{ route('volunteer.index') }}" class="hover:text-saffron-600 {{ request()->routeIs('volunteer.*') ? 'text-saffron-600' : '' }}">Volunteer</a>
            <a href="{{ route('contact.index') }}" class="hover:text-saffron-600 {{ request()->routeIs('contact.*') ? 'text-saffron-600' : '' }}">Contact</a>
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ route('donations.index') }}" class="hidden sm:inline-flex btn btn-primary text-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.151c.969 0 1.371 1.24.588 1.81l-3.357 2.44a1 1 0 00-.364 1.118l1.287 3.95c.3.922-.755 1.688-1.54 1.118l-3.358-2.44a1 1 0 00-1.175 0l-3.358 2.44c-.784.57-1.838-.196-1.539-1.118l1.287-3.95a1 1 0 00-.364-1.118L2.05 9.377c-.783-.57-.38-1.81.588-1.81h4.15a1 1 0 00.951-.69l1.286-3.95z"/></svg>
                Donate Now
            </a>
            <button @click="open = !open" class="lg:hidden p-2 rounded-md text-saffron-900 hover:bg-saffron-50" aria-label="Open menu">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile nav --}}
    <div x-show="open" x-transition class="lg:hidden border-t border-saffron-100 bg-white" style="display:none">
        <nav class="container mx-auto px-4 py-3 grid gap-2 text-saffron-900 font-medium">
            @foreach ([
                'home' => 'Home',
                'about' => 'About Us',
                'goshala' => 'Our Goshala',
                'donations.index' => 'Seva & Donation',
                'campaigns.index' => 'Campaigns',
                'events.index' => 'Events & Festivals',
                'gallery.index' => 'Gallery',
                'blog.index' => 'Blog / गौ सेवा ज्ञान',
                'volunteer.index' => 'Volunteer / Join Us',
                'contact.index' => 'Contact Us',
            ] as $r => $label)
                <a href="{{ route($r) }}" class="py-2 border-b border-saffron-50 last:border-b-0 hover:text-saffron-600">{{ $label }}</a>
            @endforeach
            <a href="{{ route('donations.index') }}" class="btn btn-primary mt-2">Donate Now</a>
        </nav>
    </div>
</header>
