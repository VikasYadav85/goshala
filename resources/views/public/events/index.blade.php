@extends('public.layout')
@section('title', 'Events & Festivals')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Festivals & events',
    'title' => 'Celebrate Your Special Days with <span class="text-devanagari text-saffron-200">गौ सेवा</span>.',
    'subtitle' => 'Janmashtami, Gopashtami, Govardhan Puja, Annadan, Cow Pujan and more — be part of our annual calendar of devotion.',
])

<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="font-display text-3xl font-bold text-saffron-900 mb-8 heading-underline">Upcoming events</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($upcoming as $event)
                <article class="card-soft overflow-hidden flex flex-col">
                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="h-48 bg-gradient-to-br from-saffron-200 to-amber-300 flex items-center justify-center text-5xl">🪔</div>
                    @endif
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs uppercase tracking-widest text-saffron-700 mb-1">{{ \Illuminate\Support\Str::title($event->type) }}</div>
                        <h3 class="font-display text-xl font-semibold text-saffron-900 mb-2">{{ $event->title }}</h3>
                        <div class="text-sm text-saffron-900/70 mb-1">📅 {{ $event->starts_at->format('D, d M Y • h:i A') }}</div>
                        @if ($event->venue)<div class="text-sm text-saffron-900/70 mb-2">📍 {{ $event->venue }}</div>@endif
                        <p class="text-sm text-saffron-900/70 mb-4 flex-1">{{ $event->short_description }}</p>
                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-secondary text-sm">View details</a>
                    </div>
                </article>
            @empty
                <p class="col-span-full text-saffron-900/60 text-center py-12">No upcoming events at the moment.</p>
            @endforelse
        </div>
    </div>
</section>

@if ($past->isNotEmpty())
<section class="py-16 bg-saffron-50">
    <div class="container mx-auto px-4">
        <h2 class="font-display text-2xl font-bold text-saffron-900 mb-6 heading-underline">Past events</h2>
        <div class="grid md:grid-cols-4 gap-4">
            @foreach ($past as $event)
                <a href="{{ route('events.show', $event->slug) }}" class="card-soft overflow-hidden block">
                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="" class="w-full h-32 object-cover">
                    @else
                        <div class="h-32 bg-gradient-to-br from-saffron-200 to-saffron-400 flex items-center justify-center text-3xl">🎊</div>
                    @endif
                    <div class="p-4">
                        <div class="text-xs uppercase tracking-widest text-saffron-700">{{ $event->starts_at->format('M Y') }}</div>
                        <div class="font-semibold text-saffron-900 text-sm line-clamp-2">{{ $event->title }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
