@extends('public.layout')
@section('title', 'Testimonials')

@section('content')

@include('public.partials.page-hero', [
    'eyebrow' => 'Voices of bhakti',
    'title' => 'Words from Donors, Devotees &amp; Volunteers.',
    'subtitle' => 'Hear what our community shares about their experience with Gopal Samarpan Sewa Charitable Trust.',
])

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($testimonials as $t)
                <figure class="card-soft p-6">
                    <div class="flex text-saffron-500 mb-3">
                        @for ($i = 0; $i < $t->rating; $i++)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.151c.969 0 1.371 1.24.588 1.81l-3.357 2.44a1 1 0 00-.364 1.118l1.287 3.95c.3.922-.755 1.688-1.54 1.118l-3.358-2.44a1 1 0 00-1.175 0l-3.358 2.44c-.784.57-1.838-.196-1.539-1.118l1.287-3.95a1 1 0 00-.364-1.118L2.05 9.377c-.783-.57-.38-1.81.588-1.81h4.15a1 1 0 00.951-.69l1.286-3.95z"/></svg>
                        @endfor
                    </div>
                    <blockquote class="italic text-saffron-900/80 leading-relaxed mb-5">"{{ $t->quote }}"</blockquote>
                    <figcaption>
                        <div class="font-semibold text-saffron-900">{{ $t->name }}</div>
                        <div class="text-xs text-saffron-700">{{ $t->role }}{{ $t->location ? ' • ' . $t->location : '' }}</div>
                    </figcaption>
                </figure>
            @empty
                <p class="col-span-full text-center text-saffron-900/60 py-12">Testimonials will appear here.</p>
            @endforelse
        </div>
        <div class="mt-8">{{ $testimonials->links() }}</div>
    </div>
</section>

@endsection
