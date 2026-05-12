@extends('public.layout')
@section('title', $post->seo_title ?: $post->title)
@section('meta_description', $post->seo_description ?: $post->excerpt)

@section('content')

<section class="relative">
    @if ($post->cover_image)
        <img src="{{ asset('storage/' . $post->cover_image) }}" alt="" class="w-full h-72 md:h-96 object-cover">
    @endif
</section>

<article class="py-16">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="text-xs uppercase tracking-widest text-saffron-700 mb-2">{{ optional($post->category)->name ?? 'Knowledge' }} · {{ optional($post->published_at)->format('d M Y') }}</div>
        <h1 class="font-display text-4xl font-bold text-saffron-900 mb-3 heading-underline">{{ $post->title }}</h1>
        @if ($post->excerpt)<p class="text-lg text-saffron-900/80 leading-relaxed mb-6">{{ $post->excerpt }}</p>@endif

        <div class="prose max-w-none text-saffron-900/85 leading-relaxed">
            {!! nl2br(e($post->body)) !!}
        </div>

        @if (! empty($post->tags))
            <div class="mt-8 flex flex-wrap gap-2">
                @foreach ($post->tags as $tag)
                    <span class="px-3 py-1 rounded-full bg-saffron-50 text-saffron-700 text-xs">#{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        @if ($related->isNotEmpty())
            <div class="mt-16">
                <h2 class="font-display text-2xl font-bold text-saffron-900 mb-6 heading-underline">Related reading</h2>
                <div class="grid sm:grid-cols-3 gap-4">
                    @foreach ($related as $r)
                        <a href="{{ route('blog.show', $r->slug) }}" class="card-soft overflow-hidden block">
                            @if ($r->cover_image)<img src="{{ asset('storage/' . $r->cover_image) }}" class="w-full h-32 object-cover" alt="">@endif
                            <div class="p-4">
                                <div class="font-semibold text-saffron-900 text-sm line-clamp-2">{{ $r->title }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>

@endsection
