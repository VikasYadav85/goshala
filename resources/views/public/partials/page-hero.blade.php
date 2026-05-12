@props(['eyebrow' => null, 'title', 'subtitle' => null, 'image' => null])
<section class="relative overflow-hidden border-b border-saffron-100">
    <div class="absolute inset-0">
        <img src="{{ $image ?? asset('img/home-hero.jpg') }}"
             alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-hero-gradient"></div>
    </div>
    <div class="relative container mx-auto px-4 py-20 md:py-28 text-white">
        @if ($eyebrow)
            <p class="uppercase tracking-[0.3em] text-saffron-200 text-xs mb-3">{{ $eyebrow }}</p>
        @endif
        <h1 class="font-display text-4xl md:text-5xl font-bold leading-tight max-w-3xl">{!! $title !!}</h1>
        @if ($subtitle)
            <p class="text-saffron-50/95 max-w-2xl mt-4 leading-relaxed">{!! $subtitle !!}</p>
        @endif
    </div>
</section>
