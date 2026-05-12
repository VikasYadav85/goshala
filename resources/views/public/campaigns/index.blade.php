@extends('public.layout')
@section('title', 'Campaigns & Projects')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Campaigns & Projects',
    'title' => 'Each Campaign is a Story of <span class="text-saffron-200 text-devanagari">सेवा</span>.',
    'subtitle' => 'Track active campaigns, urgent rescue appeals and completed projects — every rupee accounted for.',
])

@if ($emergency->isNotEmpty())
<section class="py-12 bg-red-50 border-b border-red-100">
    <div class="container mx-auto px-4">
        <h2 class="font-display text-2xl font-bold text-red-700 mb-6 flex items-center gap-2">
            <span class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span></span>
            Urgent rescue appeals
        </h2>
        <div class="grid md:grid-cols-2 gap-6">
            @foreach ($emergency as $c)
                @include('public.partials.campaign-card', ['c' => $c, 'urgent' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="font-display text-3xl font-bold text-saffron-900 mb-8 heading-underline">Active campaigns</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($active as $c)
                @include('public.partials.campaign-card', ['c' => $c])
            @empty
                <p class="col-span-full text-saffron-900/60 text-center py-12">No active campaigns at the moment. Please check back soon.</p>
            @endforelse
        </div>
    </div>
</section>

@if ($upcoming->isNotEmpty())
<section class="py-16 bg-saffron-50">
    <div class="container mx-auto px-4">
        <h2 class="font-display text-3xl font-bold text-saffron-900 mb-8 heading-underline">Upcoming projects</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($upcoming as $c)
                @include('public.partials.campaign-card', ['c' => $c])
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($completed->isNotEmpty())
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="font-display text-3xl font-bold text-saffron-900 mb-8 heading-underline">Completed projects</h2>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach ($completed as $c)
                @include('public.partials.campaign-card', ['c' => $c])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
