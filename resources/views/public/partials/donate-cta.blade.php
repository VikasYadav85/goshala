<section class="relative overflow-hidden bg-gradient-to-r from-saffron-700 via-saffron-600 to-amber-600 text-white py-16">
    <div class="absolute inset-0 pattern-dots opacity-20"></div>
    <div class="container mx-auto px-4 relative grid md:grid-cols-2 items-center gap-8">
        <div>
            <p class="text-saffron-100 uppercase tracking-widest text-xs mb-3">Become a part of Gau Seva</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold leading-tight mb-3">
                Your <span class="text-devanagari">सेवा</span> feeds, heals and shelters our Gau Mata.
            </h2>
            <p class="text-saffron-50/90 max-w-xl">
                Every <strong>₹100</strong> provides a day of green fodder for one cow. Every monthly sponsorship
                covers her medicine, vaccinations and bedding. Will you join us?
            </p>
        </div>
        <div class="flex flex-wrap gap-3 md:justify-end">
            <a href="{{ route('donations.index') }}" class="btn !bg-white !text-saffron-700 hover:!bg-saffron-50">Donate Now</a>
            <a href="{{ route('goshala') }}" class="btn !border !border-white/40 !text-white hover:!bg-white/10">Sponsor a Cow</a>
            <a href="{{ route('volunteer.index') }}" class="btn !border !border-white/40 !text-white hover:!bg-white/10">Volunteer</a>
        </div>
    </div>
</section>
