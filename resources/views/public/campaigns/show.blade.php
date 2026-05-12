@extends('public.layout')
@section('title', $campaign->title)
@section('meta_description', $campaign->short_description)

@section('content')

<section class="relative">
    @if ($campaign->image)
        <img src="{{ asset('storage/' . $campaign->image) }}" alt="" class="w-full h-72 md:h-96 object-cover">
    @else
        <div class="h-72 md:h-96 bg-gradient-to-br from-saffron-300 to-saffron-700"></div>
    @endif
</section>

<section class="py-16">
    <div class="container mx-auto px-4 grid lg:grid-cols-3 gap-10">
        <article class="lg:col-span-2">
            @if ($campaign->is_emergency)
                <span class="badge badge-danger mb-3">Urgent</span>
            @endif
            <h1 class="font-display text-3xl md:text-4xl font-bold text-saffron-900 mb-3 heading-underline">{{ $campaign->title }}</h1>
            <p class="text-lg text-saffron-900/80 leading-relaxed mb-6">{{ $campaign->short_description }}</p>
            <div class="prose max-w-none text-saffron-900/85 leading-relaxed">
                {!! nl2br(e($campaign->description)) !!}
            </div>

            @if ($campaign->updates->isNotEmpty())
                <h2 class="font-display text-2xl font-bold text-saffron-900 mt-12 mb-6 heading-underline">Campaign updates</h2>
                <div class="space-y-6">
                    @foreach ($campaign->updates as $update)
                        <div class="card-soft p-6">
                            <div class="text-xs uppercase tracking-widest text-saffron-700 mb-1">{{ optional($update->published_at)->diffForHumans() }}</div>
                            <h3 class="font-display text-lg font-semibold text-saffron-900 mb-2">{{ $update->title }}</h3>
                            <p class="text-saffron-900/80 leading-relaxed">{{ $update->body }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </article>

        <aside class="space-y-4">
            <div class="card-soft p-6 sticky top-24">
                <div class="text-xs uppercase tracking-widest text-saffron-700">Raised so far</div>
                <div class="font-display text-3xl font-bold text-saffron-700">₹{{ number_format($campaign->raised_amount) }}</div>
                <div class="text-sm text-saffron-900/60 mb-4">of ₹{{ number_format($campaign->goal_amount) }} goal</div>

                <div class="w-full h-3 bg-saffron-50 rounded-full overflow-hidden border border-saffron-100 mb-2">
                    <div class="h-full bg-gradient-to-r from-saffron-500 to-amber-500" style="width: {{ $campaign->progress_percentage }}%"></div>
                </div>
                <div class="text-xs text-saffron-700 mb-5">{{ $campaign->progress_percentage }}% raised</div>

                <a href="{{ route('donations.create', ['campaign' => $campaign->id]) }}" class="btn btn-primary w-full">Donate to this campaign</a>
            </div>
        </aside>
    </div>
</section>

@endsection
