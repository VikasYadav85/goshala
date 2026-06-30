@extends('public.layout')
@section('title', 'Seva & Donation — Make an Impact')
@section('meta_description', 'Donate online to feed, heal and shelter rescued cows. Every ₹100 provides a day of fodder. 80G tax exempt. Razorpay, UPI and bank transfer accepted.')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Seva & Donation',
    'title' => 'Make an Impact —<br>One <span class="text-devanagari text-saffron-200">सेवा</span> at a Time.',
    'subtitle' => 'Every ₹100 you contribute provides a day of fodder for one cow. Your support is the difference between hunger on the streets and dignity in our Goshala.',
])

{{-- Why donate --}}
<section class="py-16 bg-cream">
    <div class="container mx-auto px-4 grid md:grid-cols-3 gap-6">
        @foreach ([
            ['🌾', '₹100 = 1 day fodder', 'Feed one rescued cow with fresh fodder, oil cakes and clean water for a full day.'],
            ['🩺', '₹2,100 = monthly care', 'Cover monthly medicines, vaccinations and care for one Gau Mata.'],
            ['🏠', '₹51,000 = shelter sponsor', 'Help build or maintain a weather-proof shed for our elderly and rescued calves.'],
        ] as $box)
            <div class="card-soft p-7">
                <div class="text-4xl mb-3">{{ $box[0] }}</div>
                <div class="font-display text-xl font-bold text-saffron-700">{{ $box[1] }}</div>
                <p class="text-saffron-900/80 mt-2">{{ $box[2] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Donation categories --}}
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 max-w-2xl mx-auto">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Pick your seva</p>
            <h2 class="font-display text-3xl font-bold text-saffron-900 mb-3 heading-underline heading-underline-center inline-block">Donation Categories</h2>
            <p class="text-saffron-900/70">Choose the seva that resonates with your bhakti.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($categories as $category)
                <div class="card-soft overflow-hidden flex flex-col">
                    @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="" class="w-full h-44 object-cover">
                    @else
                        <div class="h-44 bg-gradient-to-br from-saffron-200 to-saffron-400 flex items-center justify-center text-5xl">{{ $category->icon ?: '🐄' }}</div>
                    @endif
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="font-display text-xl font-semibold text-saffron-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-sm text-saffron-900/70 mb-4 flex-1">{{ $category->short_description }}</p>

                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach (($category->suggested_amounts ?? []) as $amt)
                                <a href="{{ route('donations.create', ['category' => $category->id, 'amount' => $amt]) }}"
                                   class="px-3 py-1 text-sm rounded-full border border-saffron-200 text-saffron-700 hover:bg-saffron-50">
                                    ₹{{ number_format($amt) }}
                                </a>
                            @endforeach
                        </div>

                        <a href="{{ route('donations.create', ['category' => $category->id, 'amount' => $category->default_amount]) }}" class="btn btn-primary text-sm w-full">Donate ₹{{ number_format($category->default_amount) }}</a>
                    </div>
                </div>
            @empty
                <p class="text-saffron-900/60 col-span-full text-center py-12">Donation categories will appear here once configured by the trustees.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Custom amount --}}
<section class="py-16 bg-saffron-gradient">
    <div class="container mx-auto px-4">
        <div class="card-soft p-8 max-w-2xl mx-auto text-center">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Or give what feels right</p>
            <h3 class="font-display text-2xl font-bold text-saffron-900 mb-4">Make a Custom Donation</h3>
            <form action="{{ route('donations.create') }}" method="GET" class="flex flex-wrap gap-3 items-end justify-center">
                <div class="flex-1 min-w-[200px] text-left">
                    <label class="form-label">Amount (₹)</label>
                    <input type="number" name="amount" min="10" value="1100" class="form-input">
                </div>
                <button class="btn btn-primary">Continue →</button>
            </form>
            <p class="text-xs text-saffron-700 mt-4">100% of donations are utilised toward Gau Seva. 80G tax receipt issued on every successful donation.</p>
        </div>
    </div>
</section>

{{-- Payment methods --}}
<section class="py-16">
    <div class="container mx-auto px-4 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Multiple payment methods</p>
            <h2 class="font-display text-3xl font-bold text-saffron-900 mb-4 heading-underline">Donate the Way that Works for You</h2>
            <ul class="space-y-3 text-saffron-900/80">
                <li class="flex items-start gap-3"><span class="text-2xl">💳</span><span><strong>Razorpay</strong> — Cards, UPI, Net banking, Wallets (instant)</span></li>
                <li class="flex items-start gap-3"><span class="text-2xl">📱</span><span><strong>UPI / QR</strong> — Google Pay, PhonePe, Paytm</span></li>
                <li class="flex items-start gap-3"><span class="text-2xl">🏦</span><span><strong>Bank Transfer</strong> — NEFT / RTGS / IMPS</span></li>
                <li class="flex items-start gap-3"><span class="text-2xl">🌍</span><span><strong>International</strong> — Foreign donors (FCRA-aligned coming soon)</span></li>
            </ul>
        </div>
        <div class="card-soft p-6">
            <h3 class="font-display text-lg font-semibold text-saffron-900 mb-3">Bank Transfer Details</h3>
            <dl class="text-sm space-y-2 text-saffron-900/80">
                <div class="flex justify-between"><dt class="text-saffron-700">Account Name</dt><dd>Gopal Seva Samarpan Trust</dd></div>
                <div class="flex justify-between"><dt class="text-saffron-700">Account No.</dt><dd>50100-XXX-XXX-XX</dd></div>
                <div class="flex justify-between"><dt class="text-saffron-700">IFSC</dt><dd>HDFC0001234</dd></div>
                <div class="flex justify-between"><dt class="text-saffron-700">Branch</dt><dd>Vrindavan, Mathura</dd></div>
                <div class="flex justify-between"><dt class="text-saffron-700">UPI ID</dt><dd>gopalseva@hdfcbank</dd></div>
            </dl>
            <p class="text-xs text-saffron-700 mt-4">After transferring, share your screenshot on WhatsApp at {{ $publicSettings['whatsapp'] }} to receive your 80G tax receipt instantly.</p>
        </div>
    </div>
</section>

{{-- FAQs --}}
@if ($faqs->isNotEmpty())
<section class="py-16 bg-saffron-50">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="text-center mb-10">
            <p class="uppercase text-xs tracking-widest text-saffron-600 mb-2">Donation FAQs</p>
            <h2 class="font-display text-3xl font-bold text-saffron-900 heading-underline heading-underline-center inline-block">Common Questions</h2>
        </div>
        <div class="space-y-3" x-data="{ open: null }">
            @foreach ($faqs as $i => $faq)
                <div class="card-soft p-4">
                    <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between text-left">
                        <span class="font-semibold text-saffron-900">{{ $faq->question }}</span>
                        <svg :class="open === {{ $i }} ? 'rotate-180' : ''" class="w-5 h-5 text-saffron-700 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open === {{ $i }}" x-collapse class="text-sm text-saffron-900/80 mt-3" style="display:none">
                        {!! $faq->answer !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
