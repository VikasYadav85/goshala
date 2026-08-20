@extends('public.layout')
@section('title', 'Blog — गौ सेवा ज्ञान')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'गौ सेवा ज्ञान',
    'title' => 'Wisdom from the <span class="text-saffron-200">Goshala</span>.',
    'subtitle' => 'Articles on Vedic significance of Gau Seva, panchgavya, organic farming, and the spiritual role of the cow.',
])

<section class="py-12">
    <div class="container mx-auto px-4 grid lg:grid-cols-4 gap-8">
        <main class="lg:col-span-3">
            <form method="GET" class="flex flex-col sm:flex-row gap-2 mb-6">
                <label for="blog_search" class="sr-only">Search articles</label>
                <input id="blog_search" name="q" value="{{ request('q') }}" placeholder="Search articles…" class="form-input min-w-0">
                @if (request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                <button class="btn btn-primary w-full sm:w-auto">Search</button>
            </form>

            <div class="grid sm:grid-cols-2 gap-6">
                @forelse ($posts as $post)
                    <article class="card-soft overflow-hidden flex flex-col">
                        @if ($post->cover_image)
                            <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-44 object-cover">
                        @else
                            <div class="h-44 bg-gradient-to-br from-amber-200 to-saffron-300 flex items-center justify-center text-4xl">📿</div>
                        @endif
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="text-xs uppercase tracking-widest text-saffron-700">{{ optional($post->category)->name ?? 'Knowledge' }} · {{ optional($post->published_at)->diffForHumans() }}</div>
                            <h3 class="font-display text-lg font-semibold text-saffron-900 mt-1 mb-2">{{ $post->title }}</h3>
                            <p class="text-sm text-saffron-900/70 line-clamp-3 mb-4 flex-1">{{ $post->excerpt }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-saffron-700 font-semibold text-sm hover:text-saffron-900">Read article →</a>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full text-saffron-900/60 text-center py-12">No articles match your search.</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $posts->links() }}</div>
        </main>

        <aside class="space-y-6">
            <div class="card-soft p-5">
                <h3 class="font-display text-lg font-semibold text-saffron-900 mb-3">Categories</h3>
                <ul class="space-y-1 text-sm">
                    <li><a href="{{ route('blog.index') }}" class="text-saffron-700 hover:text-saffron-900">All articles</a></li>
                    @foreach ($categories as $cat)
                        <li><a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="text-saffron-700 hover:text-saffron-900">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            @if ($featured->isNotEmpty())
                <div class="card-soft p-5">
                    <h3 class="font-display text-lg font-semibold text-saffron-900 mb-3">Featured</h3>
                    <ul class="space-y-3 text-sm">
                        @foreach ($featured as $f)
                            <li><a href="{{ route('blog.show', $f->slug) }}" class="text-saffron-900 font-semibold hover:text-saffron-700">{{ $f->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>
</section>

@endsection
