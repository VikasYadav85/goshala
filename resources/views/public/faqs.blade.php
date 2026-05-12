@extends('public.layout')
@section('title', 'Frequently Asked Questions')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Help & support',
    'title' => 'Frequently Asked Questions.',
    'subtitle' => 'Find answers to common questions about donations, volunteering, visiting and tax exemption.',
])

<section class="py-12">
    <div class="container mx-auto px-4 max-w-3xl space-y-10">
        @forelse ($faqs as $group => $items)
            <div>
                <h2 class="font-display text-2xl font-bold text-saffron-900 mb-4 heading-underline capitalize">{{ $group }}</h2>
                <div class="space-y-3" x-data="{ open: null }">
                    @foreach ($items as $i => $faq)
                        <div class="card-soft p-4">
                            <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between text-left">
                                <span class="font-semibold text-saffron-900">{{ $faq->question }}</span>
                                <svg :class="open === {{ $i }} ? 'rotate-180' : ''" class="w-5 h-5 text-saffron-700 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open === {{ $i }}" class="text-sm text-saffron-900/80 mt-3 leading-relaxed" style="display:none">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-center text-saffron-900/60 py-12">FAQs are being prepared. Please contact us directly for any queries.</p>
        @endforelse
    </div>
</section>

@endsection
