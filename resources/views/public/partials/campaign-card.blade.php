@props(['c', 'urgent' => false])
<article class="card-soft overflow-hidden flex flex-col {{ $urgent ? 'border-2 border-red-300' : '' }}">
    @if ($c->image)
        <img src="{{ asset('storage/' . $c->image) }}" alt="{{ $c->title }}" class="w-full h-48 object-cover">
    @else
        <div class="h-48 bg-gradient-to-br from-saffron-200 to-saffron-400 flex items-center justify-center text-5xl">📦</div>
    @endif
    <div class="p-6 flex-1 flex flex-col">
        <h3 class="font-display text-xl font-semibold text-saffron-900 mb-2">{{ $c->title }}</h3>
        <p class="text-sm text-saffron-900/70 mb-4 flex-1 line-clamp-3">{{ $c->short_description }}</p>

        <div class="mb-3">
            <div class="flex items-center justify-between text-xs font-medium mb-1">
                <span class="text-saffron-700">₹{{ number_format($c->raised_amount) }}</span>
                <span class="text-saffron-900/60">of ₹{{ number_format($c->goal_amount) }}</span>
            </div>
            <div class="w-full h-2 bg-saffron-50 rounded-full overflow-hidden border border-saffron-100">
                <div class="h-full bg-gradient-to-r from-saffron-500 to-amber-500" style="width: {{ $c->progress_percentage }}%"></div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('campaigns.show', $c->slug) }}" class="btn btn-secondary text-sm flex-1">Read more</a>
            <a href="{{ route('donations.create', ['campaign' => $c->id]) }}" class="btn btn-primary text-sm flex-1">Donate</a>
        </div>
    </div>
</article>
