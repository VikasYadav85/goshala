@extends('public.layout')
@section('title', 'Our Goshala — The Sanctuary')
@section('meta_description', 'Meet our rescued cows, learn about daily care routines, sustainability practices and sponsor a Gau Mata at the Gopal Samarpan Sewa Charitable Trust Goshala.')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'The sanctuary',
    'title' => 'Every Cow Has a Name.<br>Every Cow Has a Story.',
    'subtitle' => 'Step inside our Goshala — where rescued cows live with dignity, surrounded by Vedic chants, fresh fodder and unconditional love.',
])

{{-- Care routine timeline --}}
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Daily care routine</p>
            <h2 class="font-display text-3xl font-bold text-saffron-900 mb-3 heading-underline heading-underline-center inline-block">A Day at the Goshala</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                ['05:30', 'Morning Aarti', 'Vedic chants and lamp lighting begin our day, blessing the cows.'],
                ['07:00', 'Feeding & Cleaning', 'Fresh green fodder, oil cakes, hygiene checks for every shed.'],
                ['11:00', 'Medical Rounds', 'On-site vets perform checkups, vaccination, and treat injuries.'],
                ['18:30', 'Sandhya Aarti', 'Evening prayer, gratitude rituals and cow-bell symphony.'],
            ] as $slot)
                <div class="card-soft p-6 relative">
                    <div class="font-display text-3xl text-saffron-600 font-bold">{{ $slot[0] }}</div>
                    <h3 class="font-display text-lg font-semibold text-saffron-900 mt-2">{{ $slot[1] }}</h3>
                    <p class="text-sm text-saffron-900/70 mt-1">{{ $slot[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Cows --}}
<section class="py-16 bg-saffron-gradient">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Meet our cows</p>
                <h2 class="font-display text-3xl font-bold text-saffron-900 heading-underline">Choose a Gau Mata to Sponsor</h2>
            </div>
            <p class="text-sm text-saffron-900/70 max-w-md">Sponsor a specific cow's monthly fodder, medicine and care. You'll receive periodic photos and updates.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($cows as $cow)
                <div class="card-soft overflow-hidden">
                    @if ($cow->image)
                        <img src="{{ asset('storage/' . $cow->image) }}" alt="{{ $cow->name }}" class="w-full h-56 object-cover">
                    @else
                        <div class="h-56 bg-gradient-to-br from-amber-200 to-saffron-300 flex items-center justify-center text-6xl">🐄</div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-display text-xl font-semibold text-saffron-900">{{ $cow->name }}</h3>
                            @if ($cow->is_featured)
                                <span class="badge badge-warning">Featured</span>
                            @endif
                        </div>
                        <p class="text-xs text-saffron-700 uppercase tracking-widest mb-3">{{ $cow->breed ?? 'Indian Desi' }} • {{ $cow->age ?? 'Adult' }} • {{ \Illuminate\Support\Str::title($cow->gender) }}</p>
                        @if ($cow->rescue_story)
                            <p class="text-sm text-saffron-900/70 line-clamp-3 mb-4">{{ $cow->rescue_story }}</p>
                        @endif
                        <div class="flex items-end justify-between mt-4">
                            <div>
                                <div class="text-xs text-saffron-700 uppercase tracking-widest">Monthly</div>
                                <div class="font-display text-2xl font-bold text-saffron-700">₹{{ number_format($cow->monthly_sponsorship_amount) }}</div>
                            </div>
                            <a href="{{ route('donations.create', ['cow' => $cow->id, 'amount' => $cow->monthly_sponsorship_amount]) }}" class="btn btn-primary text-sm">Sponsor</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-saffron-900/60 col-span-full text-center py-12">Cow profiles will appear here once added by the trustees.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $cows->links() }}
        </div>
    </div>
</section>

{{-- Sustainability --}}
<section class="py-20">
    <div class="container mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        <div>
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Beyond protection</p>
            <h2 class="font-display text-3xl font-bold text-saffron-900 mb-4 heading-underline">The Cycle of Life</h2>
            <p class="text-saffron-900/80 leading-relaxed mb-4">
                We don't just shelter cows — we honour the gifts they share. Our Goshala produces
                <strong>Gobar Gas</strong>, <strong>Organic Compost (Khad)</strong>, and
                <strong>Panchgavya</strong> products, making the sanctuary eco-friendly and sustainable.
            </p>
            <ul class="space-y-3 text-saffron-900/80">
                @foreach ([
                    ['🌾','Organic farming using gobar khad on 12+ acres of trust land'],
                    ['💡','Gobar gas plant powering kitchen and lighting in the sanctuary'],
                    ['🌿','Panchgavya & Ayurvedic preparations distributed to nearby villages'],
                    ['💧','Rainwater harvesting, solar pumps and zero chemical waste'],
                ] as $item)
                    <li class="flex items-start gap-3"><span class="text-2xl">{{ $item[0] }}</span><span>{{ $item[1] }}</span></li>
                @endforeach
            </ul>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <img src="{{ asset('img/goshala/goshala-1.jpg') }}" alt="Rescued cow on the goshala's tree-lined path" class="rounded-2xl shadow-md aspect-square object-cover" loading="lazy">
            <img src="{{ asset('img/goshala/goshala-2.jpg') }}" alt="Young rescued calf in the sanctuary" class="rounded-2xl shadow-md aspect-square object-cover mt-8" loading="lazy">
            <img src="{{ asset('img/goshala/goshala-3.jpg') }}" alt="Healthy desi cow cared for at the goshala" class="rounded-2xl shadow-md aspect-square object-cover" loading="lazy">
            <img src="{{ asset('img/goshala/goshala-4.jpg') }}" alt="Indigenous breed cow rescued and rehabilitated" class="rounded-2xl shadow-md aspect-square object-cover mt-8" loading="lazy">
        </div>
    </div>
</section>

@endsection
