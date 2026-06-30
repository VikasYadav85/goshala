@extends('admin.layout')
@section('title', 'Gallery')

@section('content')
<x-admin.page-header title="Gallery albums" subtitle="Photos and videos grouped by topic.">
    <x-slot:cta><a href="{{ route('admin.gallery.create') }}" class="btn btn-primary text-sm">+ New album</a></x-slot:cta>
</x-admin.page-header>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($albums as $album)
        <div class="admin-card overflow-hidden">
            @if ($album->cover_image)<img src="{{ asset('storage/' . $album->cover_image) }}" class="w-full aspect-video object-cover" alt="">@else<div class="aspect-video bg-gradient-to-br from-saffron-100 to-saffron-300 flex items-center justify-center text-3xl">🖼️</div>@endif
            <div class="p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-semibold">{{ $album->name }}</div>
                        <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::title($album->category) }} · {{ $album->items_count }} items</div>
                    </div>
                    @if ($album->is_published)<span class="badge badge-success">Live</span>@else<span class="badge badge-neutral">Draft</span>@endif
                </div>
                <div class="flex gap-2 mt-3">
                    <a href="{{ route('admin.gallery.edit', $album) }}" class="btn btn-secondary text-xs flex-1">Manage</a>
                    <form method="POST" action="{{ route('admin.gallery.destroy', $album) }}" onsubmit="return confirm('Delete album & items?')" class="flex-1">
                        @csrf @method('DELETE')<button class="btn text-xs bg-red-50 text-red-700 w-full">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="col-span-full text-gray-500 text-center py-12">No albums yet.</p>
    @endforelse
</div>
<div class="mt-4">{{ $albums->links() }}</div>
@endsection
