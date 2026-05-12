<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Gopal Seva Trust</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 h-full font-sans" x-data="{ sidebarOpen: false }">

<div class="flex h-full">
    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-saffron-900 text-saffron-50 transform transition-transform overflow-y-auto">
        <div class="px-5 py-5 border-b border-saffron-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="bg-white rounded-xl p-1.5 flex items-center justify-center">
                    <img src="{{ asset('img/logo-mark.png') }}" alt="" class="w-9 h-9 object-contain">
                </div>
                <div>
                    <div class="font-display font-semibold leading-tight">Gopal Seva</div>
                    <div class="text-xs text-saffron-300">Admin Panel</div>
                </div>
            </a>
        </div>

        <nav class="p-4 space-y-1 text-sm">
            @php
                $navGroups = [
                    [
                        'label' => 'Overview',
                        'links' => [
                            ['admin.dashboard', '🏠 Dashboard'],
                        ],
                    ],
                    [
                        'label' => 'Donations',
                        'links' => [
                            ['admin.donations.index', '💰 Donations'],
                            ['admin.donation-categories.index', '🪔 Donation categories'],
                            ['admin.campaigns.index', '🎯 Campaigns'],
                        ],
                    ],
                    [
                        'label' => 'Goshala',
                        'links' => [
                            ['admin.cows.index', '🐄 Cows'],
                            ['admin.events.index', '🎉 Events'],
                            ['admin.gallery.index', '🖼️ Gallery'],
                        ],
                    ],
                    [
                        'label' => 'Content',
                        'links' => [
                            ['admin.blog.index', '📝 Blog'],
                            ['admin.testimonials.index', '⭐ Testimonials'],
                            ['admin.team.index', '👥 Team'],
                            ['admin.faqs.index', '❓ FAQs'],
                        ],
                    ],
                    [
                        'label' => 'Engagement',
                        'links' => [
                            ['admin.volunteers.index', '🌱 Volunteers'],
                            ['admin.messages.index', '✉️ Messages'],
                        ],
                    ],
                    [
                        'label' => 'Settings',
                        'links' => [
                            ['admin.settings.edit', '⚙️ Site settings'],
                        ],
                    ],
                ];
            @endphp

            @foreach ($navGroups as $group)
                <div class="pt-3 first:pt-0">
                    <div class="text-xs uppercase tracking-widest text-saffron-400 px-2 mb-1">{{ $group['label'] }}</div>
                    @foreach ($group['links'] as $link)
                        @php $isActive = request()->routeIs($link[0]) || request()->routeIs(str_replace('.index', '.*', $link[0])); @endphp
                        <a href="{{ route($link[0]) }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg {{ $isActive ? 'bg-saffron-700 text-white' : 'text-saffron-100 hover:bg-saffron-800' }}">
                            {{ $link[1] }}
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="px-4 lg:px-8 py-3 flex items-center justify-between gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-700 hover:bg-gray-100" aria-label="Toggle sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex-1">
                    <h1 class="font-display text-xl text-gray-900">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="text-sm text-saffron-700 hover:text-saffron-900">View site →</a>
                    <div class="hidden sm:block text-right">
                        <div class="text-sm font-medium text-gray-900">{{ auth()->user()?->name }}</div>
                        <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', auth()->user()?->role ?? '')) }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="px-3 py-1.5 rounded-lg text-sm text-gray-700 hover:bg-gray-100 border border-gray-200">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 lg:p-8 overflow-y-auto">
            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                    <strong>Please fix the following:</strong>
                    <ul class="list-disc ml-5 mt-1">
                        @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
