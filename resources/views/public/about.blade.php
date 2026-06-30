@extends('public.layout')
@section('title', 'About Us — Our Heart and Heritage')
@section('meta_description', 'Born from devotion, driven by duty — read the story of Gopal Seva Samarpan Trust, our trustees and our certifications.')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Our heart & heritage',
    'title' => 'Born from <span class="text-saffron-200 text-devanagari">सेवा</span>,<br>Driven by Duty.',
    'subtitle' => 'Gopal Seva Samarpan Trust was founded with a single vision — to revive the culture of Gau Seva in its purest form.',
])

{{-- Story --}}
<section class="py-20">
    <div class="container mx-auto px-4 grid md:grid-cols-2 gap-12 items-start">
        <div>
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Our story</p>
            <h2 class="font-display text-3xl font-bold text-saffron-900 mb-5 heading-underline">From a Small Rescue to a Sacred Sanctuary</h2>
            <p class="text-saffron-900/80 leading-relaxed mb-4">
                What started as a small rescue operation has grown into a structured sanctuary —
                ensuring that "discarded" cattle find a family that loves them until their last breath.
            </p>
            <p class="text-saffron-900/80 leading-relaxed mb-4">
                Today, the Trust runs a full-service Goshala with veterinary infrastructure, an organic
                fodder cycle, and a compassionate community of volunteers. Every cow receives 24/7
                care, daily आरती and the dignity she deserves.
            </p>
            <p class="text-saffron-900/80 leading-relaxed">
                Our seva extends to surrounding villages where we run rescue, vaccination and
                <span class="text-devanagari">अन्नदान</span> programs throughout the year.
            </p>
        </div>

        <div class="card-soft p-8 bg-saffron-50">
            <p class="uppercase text-xs tracking-widest text-saffron-700 mb-2">Founder's message</p>
            <blockquote class="font-display text-xl text-saffron-900 leading-relaxed mb-4">
                "Gau Mata is the mother of the universe. To serve her is to serve the Divine.
                Our trust is merely a medium for your compassion to reach those who cannot
                speak for themselves."
            </blockquote>
            <div class="flex items-center gap-3 mt-6">
                <div class="w-12 h-12 rounded-full bg-saffron-300 flex items-center justify-center text-saffron-900 font-bold text-lg">G</div>
                <div>
                    <div class="font-semibold text-saffron-900">— Gopal Das ji Maharaj</div>
                    <div class="text-xs text-saffron-700">Founder &amp; Chairman</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission/Vision --}}
<section class="py-16 bg-saffron-gradient">
    <div class="container mx-auto px-4 grid md:grid-cols-3 gap-6">
        @foreach ([
            ['Mission', 'To provide medical care, nutritious fodder and a dignified shelter for every rescued cow while promoting the spiritual significance of Gau Seva.', '🌿'],
            ['Vision', 'A Bharat where no cow is abandoned and every individual recognises the spiritual and ecological necessity of cow protection.', '🕉️'],
            ['Values', 'Compassion (करुणा), Service (सेवा), Integrity (सत्यता), Sustainability and Spiritual Devotion (भक्ति).', '🪔'],
        ] as $box)
            <div class="card-soft p-7">
                <div class="text-3xl mb-3">{{ $box[2] }}</div>
                <h3 class="font-display text-xl font-semibold text-saffron-900 mb-2">{{ $box[0] }}</h3>
                <p class="text-saffron-900/80">{{ $box[1] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Certifications --}}
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10 max-w-2xl mx-auto">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Your trust, our responsibility</p>
            <h2 class="font-display text-3xl font-bold text-saffron-900 mb-3 heading-underline heading-underline-center inline-block">Legal Transparency</h2>
            <p class="text-saffron-900/70">We are a government-registered NGO. All donations qualify for tax exemption under <strong>Section 80G</strong>. Annual audited reports and impact summaries are public.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach ([['80G', 'Tax exemption certificate'],['12A', 'Income-tax registration'],['NGO Darpan', 'Govt. of India registered'],['CSR-1', 'Corporate giving enabled']] as $c)
                <div class="card-soft p-6 text-center">
                    <div class="font-display text-3xl text-saffron-700 font-bold">{{ $c[0] }}</div>
                    <div class="text-xs text-saffron-900/70 mt-1">{{ $c[1] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Trustees & Team --}}
@if ($trustees->isNotEmpty() || $team->isNotEmpty())
<section class="py-20 bg-saffron-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Trustees &amp; team</p>
            <h2 class="font-display text-3xl font-bold text-saffron-900 heading-underline heading-underline-center inline-block">The Hands Behind the Seva</h2>
        </div>

        @if ($trustees->isNotEmpty())
            <h3 class="font-display text-xl text-saffron-800 mb-4">Trustees</h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach ($trustees as $member)
                    <div class="card-soft overflow-hidden text-center">
                        @if ($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full aspect-[3/4] object-cover object-center bg-saffron-100">
                        @else
                            <div class="aspect-[3/4] bg-gradient-to-br from-saffron-200 to-saffron-400 flex items-center justify-center text-saffron-900 font-display text-3xl">{{ \Illuminate\Support\Str::of($member->name)->substr(0, 1) }}</div>
                        @endif
                        <div class="p-4">
                            <div class="font-display font-semibold text-saffron-900">{{ $member->name }}</div>
                            <div class="text-xs text-saffron-700 uppercase tracking-widest mt-1">{{ $member->role }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($team->isNotEmpty())
            <h3 class="font-display text-xl text-saffron-800 mb-4">Team &amp; Veterinarians</h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($team as $member)
                    <div class="card-soft overflow-hidden text-center">
                        @if ($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full aspect-[3/4] object-cover object-center bg-saffron-100">
                        @else
                            <div class="aspect-[3/4] bg-gradient-to-br from-saffron-100 to-saffron-300 flex items-center justify-center text-saffron-900 font-display text-2xl">{{ \Illuminate\Support\Str::of($member->name)->substr(0, 1) }}</div>
                        @endif
                        <div class="p-4">
                            <div class="font-semibold text-saffron-900">{{ $member->name }}</div>
                            <div class="text-xs text-saffron-700">{{ $member->role }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif

{{-- Why Gau Seva matters --}}
<section class="py-20">
    <div class="container mx-auto px-4 grid md:grid-cols-3 gap-8">
        @foreach ([
            ['Spiritual', 'Cows are revered in the Vedas as embodiments of all 33 koti devatas — serving them is serving the Divine.', '🕉️'],
            ['Social', 'A goshala becomes a community space — for festivals, pujan, and unity across faiths and ages.', '🤝'],
            ['Ecological', 'Cow-based products — gobar, urine, panchgavya — restore soil and reduce chemical farming.', '🌱'],
        ] as $card)
            <div class="card-soft p-7">
                <div class="text-3xl mb-3">{{ $card[2] }}</div>
                <h3 class="font-display text-xl font-semibold text-saffron-900 mb-2">{{ $card[0] }}</h3>
                <p class="text-saffron-900/80">{{ $card[1] }}</p>
            </div>
        @endforeach
    </div>
</section>

@endsection
