@extends('public.layout')
@section('title', 'Home — Serving Gau Mata with Devotion')
@section('meta_description', 'Gopal Samarpan Sewa Charitable Trust serves rescued cows with devotion, medical care and shelter. Donate, sponsor a cow, or volunteer to join our spiritual seva.')

@section('content')

{{-- 1. Hero --}}
<section class="relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('img/home-hero.jpg') }}"
             alt="Indian Desi Gau Mata at our Goshala in golden evening light"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-hero-gradient"></div>
    </div>

    <div class="relative container mx-auto px-4 py-24 md:py-36 text-white">
        <div class="max-w-3xl">
            <p class="uppercase tracking-[0.3em] text-saffron-200 text-xs md:text-sm mb-4">
                <span class="text-devanagari text-base">गौ सेवा परम धर्मः</span>
            </p>
            <h1 class="font-display text-4xl md:text-6xl font-bold leading-tight mb-6">
                Serving <span class="text-devanagari text-saffron-200">गौ माता</span><br>
                with Devotion, Compassion &amp; Humanity.
            </h1>
            <p class="text-lg text-saffron-50/95 max-w-2xl mb-8 leading-relaxed">
                Join Gopal Samarpan Sewa Charitable Trust in protecting the sacred soul of Bharat.
                From rescue to rehabilitation, we ensure every cow lives with dignity and love.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('donations.index') }}" class="btn btn-primary text-base">
                    Donate Now
                </a>
                <a href="{{ route('goshala') }}" class="btn !bg-white !text-saffron-800 hover:!bg-saffron-50">Sponsor a Cow</a>
                <a href="{{ route('contact.index') }}" class="btn !border !border-white/40 !text-white hover:!bg-white/10">Visit Our Goshala</a>
            </div>

            <div class="mt-10 flex flex-wrap gap-3 text-xs">
                @foreach (['Registered Trust', '80G Tax Exempt', '12A Certified', '100% Transparent'] as $badge)
                    <span class="px-3 py-1 rounded-full bg-white/15 backdrop-blur border border-white/20">{{ $badge }}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- decorative bottom edge --}}
    <svg class="absolute bottom-0 left-0 right-0 w-full text-cream" viewBox="0 0 1440 60" preserveAspectRatio="none" aria-hidden="true">
        <path fill="currentColor" d="M0,32 C240,80 480,0 720,16 C960,32 1200,80 1440,32 L1440,60 L0,60 Z"></path>
    </svg>
</section>

{{-- 2. About snapshot --}}
<section class="py-20">
    <div class="container mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        <div class="relative">
            <div class="aspect-[4/5] rounded-3xl overflow-hidden shadow-xl">
                <img src="{{ asset('img/home-hero.jpg') }}"
                     alt="Indian Desi cows in our Goshala" class="w-full h-full object-cover">
            </div>
            <div class="absolute -bottom-6 -right-6 hidden md:block bg-white card-soft p-5 max-w-xs">
                <div class="text-3xl font-display font-bold text-saffron-700">15+ years</div>
                <div class="text-sm text-saffron-900/70">of devoted Gau Seva across Bharat</div>
            </div>
        </div>
        <div>
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">A Sanctuary of Faith and Protection</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 mb-5 heading-underline">Who we are</h2>
            <p class="text-saffron-900/80 leading-relaxed mb-4">
                Gopal Samarpan Sewa Charitable Trust is more than just an NGO; it is a movement dedicated to the service of
                <span class="text-devanagari font-semibold">नंदी</span> and
                <span class="text-devanagari font-semibold">गौ माता</span>. Rooted in spiritual values and driven by
                modern transparency, we provide a permanent home to abandoned, sick, and rescued cattle.
            </p>
            <p class="text-saffron-900/80 leading-relaxed mb-6">
                From medical care and nutritious fodder to evening <span class="text-devanagari font-semibold">गौ आरती</span>,
                every act in our Goshala is performed with reverence, science, and seva.
            </p>
            <a href="{{ route('about') }}" class="btn btn-secondary">Read Our Full Story →</a>
        </div>
    </div>
</section>

{{-- 3. Mission / Vision / Values --}}
<section class="py-20 bg-saffron-gradient">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <p class="uppercase text-xs tracking-widest text-saffron-700 mb-2">Why we exist</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 mb-4 heading-underline heading-underline-center inline-block">Mission, Vision &amp; Values</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach ([
                ['title' => 'Mission', 'text' => 'To provide high-quality medical care, nutritious fodder, and a safe haven for every rescued cow while promoting the spiritual significance of Gau Seva.', 'icon' => '🌿'],
                ['title' => 'Vision', 'text' => 'A world where no cow is left abandoned and where every individual recognises the ecological and spiritual necessity of cow protection.', 'icon' => '🕉️'],
                ['title' => 'Core Values', 'text' => 'Compassion (करुणा), Service (सेवा), and Integrity (सत्यता) — the bedrock of every act inside our Goshala.', 'icon' => '🪔'],
            ] as $item)
                <div class="card-soft p-7">
                    <div class="text-3xl mb-3">{{ $item['icon'] }}</div>
                    <h3 class="font-display text-xl font-semibold text-saffron-900 mb-2">{{ $item['title'] }}</h3>
                    <p class="text-saffron-900/80 leading-relaxed">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 4. Daily activities --}}
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 max-w-2xl mx-auto">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">A Day in the Goshala</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 mb-4 heading-underline heading-underline-center inline-block">Daily Goshala Activities</h2>
            <p class="text-saffron-900/70">From sunrise to evening आरती, every act is choreographed with care, science and devotion.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([
                ['🌾', 'Nutritious Feeding', 'Fresh green fodder, oil cakes and clean water served twice daily for every cow in our care.'],
                ['🩺', 'Medical Care', 'On-site veterinary checkups, vaccinations and specialised treatment for injured or elderly cows.'],
                ['🪔', 'Gau Aarti & Bhakti', 'Vedic chants in the morning and evening prayers honour the divine presence of Gau Mata.'],
                ['🧹', 'Cleanliness & Hygiene', 'A 24/7 sanitised environment prevents disease and keeps every shelter calm and comfortable.'],
                ['🚑', 'Rescue Missions', 'Round-the-clock cow rescue from highways, accidents and abandonment — across districts.'],
                ['🌱', 'Sustainability', 'Gobar gas, organic compost, panchgavya and cow urine products complete the natural cycle.'],
            ] as $card)
                <div class="card-soft p-6">
                    <div class="text-3xl mb-3">{{ $card[0] }}</div>
                    <h3 class="font-display text-lg font-semibold text-saffron-900 mb-2">{{ $card[1] }}</h3>
                    <p class="text-sm text-saffron-900/70 leading-relaxed">{{ $card[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 5. Impact stats --}}
<section class="py-20 bg-saffron-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 pattern-dots opacity-10"></div>
    <div class="container mx-auto px-4 relative">
        <div class="text-center mb-12">
            <p class="uppercase text-xs tracking-widest text-saffron-300 mb-2">Impact in numbers</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold mb-3">Real-Time Goshala Growth</h2>
            <p class="text-saffron-100/80 max-w-xl mx-auto">Each number is a story — of rescue, recovery and renewal.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach ([
                [number_format($impact['cows_sheltered']) . '+', 'Cows Sheltered'],
                [number_format($impact['rescued']) . '+', 'Rescued from Distress'],
                [number_format($impact['fodder_kg']) . ' kg', 'Daily Fodder Served'],
                [number_format($impact['trees_planted']) . '+', 'Trees Planted'],
                [number_format($impact['villages']) . '+', 'Villages Supported'],
                ['₹' . number_format($impact['total_raised']), 'Total Raised'],
            ] as $stat)
                <div class="text-center">
                    <div class="font-display text-3xl md:text-4xl font-bold text-saffron-200">{{ $stat[0] }}</div>
                    <div class="text-xs uppercase tracking-widest text-saffron-300 mt-1">{{ $stat[1] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 6. Donation programs --}}
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
            <div class="max-w-2xl">
                <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Seva Programs</p>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 mb-3 heading-underline">Donation &amp; Seva Programs</h2>
                <p class="text-saffron-900/70">Choose a program that matches your bhakti and budget. 100% of every rupee goes to Gau Seva.</p>
            </div>
            <a href="{{ route('donations.index') }}" class="btn btn-secondary">View All Programs →</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($donationPrograms as $program)
                <div class="card-soft overflow-hidden flex flex-col">
                    @if ($program->image)
                        <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="h-48 bg-gradient-to-br from-saffron-200 to-saffron-400 flex items-center justify-center text-5xl">{{ $program->icon ?: '🐄' }}</div>
                    @endif
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="font-display text-xl font-semibold text-saffron-900 mb-2">{{ $program->name }}</h3>
                        <p class="text-sm text-saffron-900/70 leading-relaxed mb-4 flex-1">{{ $program->short_description }}</p>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-xs text-saffron-700 uppercase tracking-widest">From</div>
                                <div class="font-display text-2xl font-bold text-saffron-700">₹{{ number_format($program->default_amount) }}</div>
                            </div>
                            <a href="{{ route('donations.create', ['category' => $program->id, 'amount' => $program->default_amount]) }}" class="btn btn-primary text-sm">Sponsor</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-saffron-900/60 col-span-full text-center py-12">Donation programs are being prepared. Please check back soon.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- 7. Featured campaign --}}
@if ($featuredCampaign)
<section class="py-20 bg-saffron-gradient">
    <div class="container mx-auto px-4 grid md:grid-cols-2 gap-10 items-center">
        <div class="rounded-3xl overflow-hidden shadow-xl">
            @if ($featuredCampaign->image)
                <img src="{{ asset('storage/' . $featuredCampaign->image) }}" alt="{{ $featuredCampaign->title }}" class="w-full h-full object-cover aspect-[4/3]">
            @else
                <div class="aspect-[4/3] bg-gradient-to-br from-saffron-300 to-saffron-600 flex items-center justify-center text-white text-6xl">🛖</div>
            @endif
        </div>
        <div>
            @if ($featuredCampaign->is_emergency)
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold uppercase mb-3">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span></span>
                    Urgent Appeal
                </span>
            @endif
            <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 mb-4">{{ $featuredCampaign->title }}</h2>
            <p class="text-saffron-900/80 leading-relaxed mb-6">{{ $featuredCampaign->short_description }}</p>

            <div class="mb-6">
                <div class="flex items-center justify-between text-sm font-medium mb-2">
                    <span class="text-saffron-700">Raised: ₹{{ number_format($featuredCampaign->raised_amount) }}</span>
                    <span class="text-saffron-900/60">Goal: ₹{{ number_format($featuredCampaign->goal_amount) }}</span>
                </div>
                <div class="w-full h-3 bg-white rounded-full overflow-hidden border border-saffron-200">
                    <div class="h-full bg-gradient-to-r from-saffron-500 to-amber-500" style="width: {{ $featuredCampaign->progress_percentage }}%"></div>
                </div>
                <div class="text-xs text-saffron-700 mt-1">{{ $featuredCampaign->progress_percentage }}% raised</div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('donations.create', ['campaign' => $featuredCampaign->id]) }}" class="btn btn-primary">Help us reach the goal</a>
                <a href="{{ route('campaigns.show', $featuredCampaign->slug) }}" class="btn btn-secondary">Read more</a>
            </div>
        </div>
    </div>
</section>
@endif

{{-- 8. Featured cows --}}
@if ($featuredCows->isNotEmpty())
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Meet our family</p>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 heading-underline">Cows Looking for a Sponsor</h2>
            </div>
            <a href="{{ route('goshala') }}" class="btn btn-secondary">View All Cows →</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($featuredCows as $cow)
                <div class="card-soft overflow-hidden">
                    @if ($cow->image)
                        <img src="{{ asset('storage/' . $cow->image) }}" alt="{{ $cow->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="h-48 bg-gradient-to-br from-amber-200 to-saffron-300 flex items-center justify-center text-5xl">🐄</div>
                    @endif
                    <div class="p-5">
                        <h3 class="font-display text-lg font-semibold text-saffron-900">{{ $cow->name }}</h3>
                        <p class="text-xs text-saffron-700 mb-2">{{ $cow->breed ?? 'Indian Desi' }} • {{ $cow->age ?? 'Adult' }}</p>
                        <p class="text-sm text-saffron-900/70 line-clamp-3 mb-4">{{ $cow->rescue_story ?? $cow->description }}</p>
                        <a href="{{ route('donations.create', ['cow' => $cow->id, 'amount' => $cow->monthly_sponsorship_amount]) }}"
                           class="btn btn-primary text-sm w-full">Sponsor for ₹{{ number_format($cow->monthly_sponsorship_amount) }}/mo</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 9. Festivals & events --}}
@if ($upcomingEvents->isNotEmpty())
<section class="py-20 bg-saffron-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 max-w-2xl mx-auto">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Festivals &amp; Events</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 mb-3 heading-underline heading-underline-center inline-block">Celebrate with Gau Seva</h2>
            <p class="text-saffron-900/70">Make your birthday, anniversary or festival unforgettable by performing Gau Pujan.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($upcomingEvents as $event)
                <div class="card-soft overflow-hidden">
                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-44 object-cover">
                    @else
                        <div class="h-44 bg-gradient-to-br from-saffron-200 to-amber-300 flex items-center justify-center text-5xl">🪔</div>
                    @endif
                    <div class="p-5">
                        <div class="text-xs uppercase tracking-widest text-saffron-700 mb-1">{{ \Illuminate\Support\Str::title($event->type) }}</div>
                        <h3 class="font-display text-lg font-semibold text-saffron-900 mb-2">{{ $event->title }}</h3>
                        <p class="text-sm text-saffron-900/70 mb-1">📅 {{ $event->starts_at->format('D, d M Y') }}</p>
                        @if ($event->venue)<p class="text-sm text-saffron-900/70 mb-3">📍 {{ $event->venue }}</p>@endif
                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-secondary text-sm">Learn more</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 10. Testimonials --}}
@if ($testimonials->isNotEmpty())
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 max-w-2xl mx-auto">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Voices of Bhakti</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 heading-underline heading-underline-center inline-block">Words from Donors &amp; Devotees</h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($testimonials as $t)
                <figure class="card-soft p-6">
                    <div class="flex text-saffron-500 mb-3">
                        @for ($i = 0; $i < $t->rating; $i++) <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.151c.969 0 1.371 1.24.588 1.81l-3.357 2.44a1 1 0 00-.364 1.118l1.287 3.95c.3.922-.755 1.688-1.54 1.118l-3.358-2.44a1 1 0 00-1.175 0l-3.358 2.44c-.784.57-1.838-.196-1.539-1.118l1.287-3.95a1 1 0 00-.364-1.118L2.05 9.377c-.783-.57-.38-1.81.588-1.81h4.15a1 1 0 00.951-.69l1.286-3.95z"/></svg>@endfor
                    </div>
                    <blockquote class="text-saffron-900/80 italic leading-relaxed mb-5">"{{ $t->quote }}"</blockquote>
                    <figcaption class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-saffron-200 flex items-center justify-center text-saffron-700 font-semibold">{{ \Illuminate\Support\Str::of($t->name)->substr(0,1)->upper() }}</div>
                        <div>
                            <div class="font-semibold text-saffron-900 text-sm">{{ $t->name }}</div>
                            <div class="text-xs text-saffron-700">{{ $t->role }}{{ $t->location ? ' • '.$t->location : '' }}</div>
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 11. Latest blog --}}
@if ($latestPosts->isNotEmpty())
<section class="py-20 bg-saffron-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Latest from the blog</p>
                <h2 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 heading-underline">गौ सेवा ज्ञान</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary">All articles →</a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($latestPosts as $post)
                <article class="card-soft overflow-hidden flex flex-col">
                    @if ($post->cover_image)
                        <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-44 object-cover">
                    @else
                        <div class="h-44 bg-gradient-to-br from-amber-200 to-saffron-300 flex items-center justify-center text-4xl">📿</div>
                    @endif
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="text-xs uppercase tracking-widest text-saffron-700 mb-2">{{ optional($post->category)->name ?? 'Knowledge' }}</div>
                        <h3 class="font-display text-lg font-semibold text-saffron-900 mb-2">{{ $post->title }}</h3>
                        <p class="text-sm text-saffron-900/70 line-clamp-3 mb-4 flex-1">{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-saffron-700 font-semibold text-sm hover:text-saffron-900">Read article →</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
