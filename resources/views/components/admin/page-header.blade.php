@props(['title', 'subtitle' => null, 'cta' => null])
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-display text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if ($subtitle)<p class="text-sm text-gray-500 mt-0.5">{{ $subtitle }}</p>@endif
    </div>
    @if ($cta){{ $cta }}@endif
</div>
