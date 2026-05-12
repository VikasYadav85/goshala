@extends('public.layout')
@section('title', 'Gallery — Moments from the Goshala')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Gallery',
    'title' => 'Moments from the <span class="text-saffron-200">Goshala</span>.',
    'subtitle' => 'Browse photos and videos of cow feeding, rescue missions, festivals, devotee visits and seva moments.',
])

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('gallery.index') }}" class="px-4 py-2 rounded-full text-sm border {{ ! request('category') ? 'bg-saffron-700 text-white border-saffron-700' : 'border-saffron-200 text-saffron-900 hover:bg-saffron-50' }}">All</a>
            @foreach ($categories as $cat)
                <a href="{{ route('gallery.index', ['category' => $cat]) }}" class="px-4 py-2 rounded-full text-sm border {{ request('category') === $cat ? 'bg-saffron-700 text-white border-saffron-700' : 'border-saffron-200 text-saffron-900 hover:bg-saffron-50' }}">{{ \Illuminate\Support\Str::title($cat) }}</a>
            @endforeach
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($albums as $album)
                <a href="{{ route('gallery.show', $album->slug) }}" class="card-soft overflow-hidden block group">
                    @if ($album->cover_image)
                        <img src="{{ asset('storage/' . $album->cover_image) }}" alt="{{ $album->name }}" class="w-full h-56 object-cover group-hover:scale-105 transition">
                    @else
                        <div class="h-56 bg-gradient-to-br from-saffron-200 to-saffron-400 flex items-center justify-center text-5xl">🖼️</div>
                    @endif
                    <div class="p-5">
                        <div class="text-xs uppercase tracking-widest text-saffron-700">{{ \Illuminate\Support\Str::title($album->category) }} · {{ $album->items_count }} photos</div>
                        <h3 class="font-display text-lg font-semibold text-saffron-900">{{ $album->name }}</h3>
                        @if ($album->description)<p class="text-sm text-saffron-900/70 line-clamp-2 mt-1">{{ $album->description }}</p>@endif
                    </div>
                </a>
            @empty
                <p class="col-span-full text-saffron-900/60 text-center py-12">Albums will appear here once added.</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $albums->links() }}</div>
    </div>
</section>

@endsection
