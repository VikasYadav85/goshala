@extends('public.layout')
@section('title', $event->title)

@section('content')

<section class="relative">
    @if ($event->image)
        <img src="{{ asset('storage/' . $event->image) }}" alt="" class="w-full h-72 md:h-96 object-cover">
    @else
        <div class="h-72 md:h-96 bg-gradient-to-br from-saffron-300 to-saffron-700"></div>
    @endif
</section>

<section class="py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <span class="badge badge-warning">{{ \Illuminate\Support\Str::title($event->type) }}</span>
        <h1 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 mt-3 mb-3 heading-underline">{{ $event->title }}</h1>

        <div class="grid sm:grid-cols-3 gap-3 my-6 card-soft p-5">
            <div><div class="text-xs uppercase text-saffron-700">Date &amp; time</div><div class="font-semibold text-saffron-900">{{ $event->starts_at->format('D, d M Y • h:i A') }}</div></div>
            @if ($event->venue)<div><div class="text-xs uppercase text-saffron-700">Venue</div><div class="font-semibold text-saffron-900">{{ $event->venue }}</div></div>@endif
            @if ($event->capacity)<div><div class="text-xs uppercase text-saffron-700">Capacity</div><div class="font-semibold text-saffron-900">{{ $event->capacity }} attendees</div></div>@endif
        </div>

        <div class="prose max-w-none text-saffron-900/85 leading-relaxed">
            {!! nl2br(e($event->description)) !!}
        </div>

        @if ($event->location_url)
            <a href="{{ $event->location_url }}" target="_blank" rel="noopener" class="btn btn-secondary mt-6">📍 View on map</a>
        @endif

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('contact.index') }}" class="btn btn-primary">RSVP / Contact us</a>
            <a href="{{ route('donations.index') }}" class="btn btn-secondary">Sponsor this event</a>
        </div>
    </div>
</section>

@endsection
