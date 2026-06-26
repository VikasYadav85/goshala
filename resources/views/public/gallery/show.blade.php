@extends('public.layout')
@section('title', $album->name)

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Gallery / ' . \Illuminate\Support\Str::title($album->category),
    'title' => $album->name,
    'subtitle' => $album->description,
])

<section class="py-12">
    <div class="container mx-auto px-4">
        <a href="{{ route('gallery.index') }}" class="btn btn-ghost mb-6">← Back to all albums</a>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($album->items as $item)
                <figure class="card-soft overflow-hidden">
                    @if ($item->type === 'image' && $item->file_path)
                        <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->alt_text ?? $item->caption }}" class="w-full h-72 object-contain bg-saffron-50">
                    @elseif ($item->type === 'youtube' && $item->external_url)
                        <div class="aspect-video">
                            <iframe class="w-full h-full" src="{{ $item->external_url }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    @endif
                    @if ($item->caption)
                        <figcaption class="p-3 text-sm text-saffron-900/80">{{ $item->caption }}</figcaption>
                    @endif
                </figure>
            @empty
                <p class="col-span-full text-saffron-900/60 text-center py-12">This album is empty.</p>
            @endforelse
        </div>
    </div>
</section>

@endsection
